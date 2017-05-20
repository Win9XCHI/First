#include "stdafx.h"
#include <iostream>
#include <string>

using namespace std;

template<class T>
int sequential_search(T array[], int size, T value);

template<class T>
int binary_searchR(T array[], int first, int last, T value);

template<class T>
int binary_searchNR(T array[], int first, int last, T value);

template<class T>
int fibonacci_search(T array[], int size, T key);

int _fib[] = {
	0, 1, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144,
	233, 377, 610, 987, 1597, 2584, 4181, 6765,
	10946, 17711, 28657, 46368, 75025, 121393,
	196418, 317811, 514229, 832040, 1346269, 2178309,/Users/fu/Desktop/1.docx
	3524578, 5702887, 9227465, 14930352, 24157817,
	39088169, 63245986, 102334155, 165580141,
	267914296, 433494437, 701408733, 1134903170,
	1836311903 };

int main() {

	int list[10];

	for (int k = 0; k < 10; k++)
		list[k] = 2 * k + 1;

	for (int k = 0; k < 10; k++)
	{
		int key = 2 * k + 7;

		cout << "Search for " << key << endl;

		cout << "Sequential search results: " << sequential_search<int>(list, 10, key) << endl;
		cout << "Binary search results (non-recursive): " << binary_searchNR<int>(list, 0, 9, key) << endl;
		cout << "Binary search results (recursive): " << binary_searchR<int>(list, 0, 9, key) << endl;
		cout << "Fibonacci search results: " << fibonacci_search<int>(list, 10, key) << endl;
		cout << endl;
	}

	string names[] = { "abc", "bcd", "cde", "def", "efg", "fgh", "ghi", "hij", "ijk", "jkl" };

	for (int i = 0; i < 5; i++)
	{
		string key = names[i];

		cout << "Search for " << key << endl;

		cout << "Sequential search results: " << sequential_search<string>(names, 10, key) << endl;
		cout << "Binary search results (non-recursive): " << binary_searchNR<string>(names, 0, 9, key) << endl;
		cout << "Binary search results (recursive): " << binary_searchR<string>(names, 0, 9, key) << endl;
		cout << "Fibonacci search results: " << fibonacci_search<string>(names, 10, key) << endl;
		cout << endl;
	}

	system("pause");
	return 0;
}

template<class T>
int sequential_search(T array[], int size, T value)
{
	for (int i = 0; i < size; i++) {
		if (array[i] == value) {
			return i;
		}
	}

	return -1;
}

template<class T>
int fibonacci_search(T array[], int size, T key)
{
	int i, t=0, p, q;
    int *F = new int[size];
    
    F[0] = 0;
    F[1] = 1;
    
    for (i=2; i<=size; i++) {    
		if (F[i - 1] > size) {
			break;
		}
        F[i] = F[i - 1] + F[i - 2];
    }
	i--;
	if (F[i] > size) {
		i--;
	}
	p = F[i - 1];   
    q = F[i - 2]; 
    i = F[i];     
	  
    while (key != array[i])  {   
        if (key < array[i]) {
            if (q == 0) {
				delete []F;
                return -1;
            }
			if (i == 2) {
				i--;
			}
			else {
				i = i - q;
				p = p - q;
				t = p;
				p = q;
				q = t;
			}
        }
        else {
            if (p == 1) {
				delete []F;
                return -1;
            }
			if (i == size - 2) {
				i = size - 1;
			}
			else {
				i = i + q;
				p = p - q;
				q = q - p;
			}
        }
    }
	delete []F;
	return i;
}

template<class T>
int binary_searchR(T array[], int first, int last, T key)
{
	int m;
	m = (first + last) / 2;
	if (first > last) {
		return -1;
	}
	else {
		if (array[m] == key) {
			return m;
		}
		if (array[m] > key) {
			return binary_searchR(array,first,m-1,key);
		}
		else {
			return binary_searchR(array,m+1,last,key);
		}
	}
}

template<class T>
int binary_searchNR(T array[], int first, int last, T key)
{
	int m;
	m = (first + last) / 2;
	while (array[m] != key) {
		if (array[m] > key) {
			last = m - 1;
		}
		else {
			first = m + 1;
		}
		if (first > last) {
			return -1;
		}
		m = (first + last) / 2;
	}

	return m;
}


