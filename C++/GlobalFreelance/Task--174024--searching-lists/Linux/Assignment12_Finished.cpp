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
int fibonacci_search(T array[], int size, T value);

int _fib[] = {
	0, 1, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144,
	233, 377, 610, 987, 1597, 2584, 4181, 6765,
	10946, 17711, 28657, 46368, 75025, 121393,
	196418, 317811, 514229, 832040, 1346269, 2178309,
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

	return 0;
}

/**
 * Time complexity of sequential_search: O(n).
 */
template<class T>
int sequential_search(T array[], int size, T value)
{
	for ( int i = 0; i < size; i++ ) {
		if ( array[i] == value ) {
			return i;
		}
	}

	return -1;
}

/**
 * Time complexity of fibonacci search: O(log(n)).
 */
template<class T>
int fibonacci_search(T array[], int size, T key)
{ 
	int inf = 0, pos, k;
	static int kk= -1, nn = -1;

	if (nn != size)
	{ 
		k = 0;

		while ( _fib[k] < size )
		{
			k++;
		}

		kk = k;
		nn = size;
	}
	else {
		k = kk;
	}

	while ( k > 0 )
	{
		pos = inf + _fib[--k];
		if ( (pos >= size) || (key < array[pos]) );
		else if ( key > array[pos] )
		{
			inf = pos + 1;
			k--;
		}
		else {
			return pos;
		}
	}

	return -1;
}

/**
 * Time complexity of binary_search (recursive): O(log(n)).
 */
template<class T>
int binary_searchR(T array[], int first, int last, T key)
{
	int m;
	m = ( first + last ) / 2;

	if ( first > last ) {
		return -1;
	}
	else {
		if ( array[m] == key ) {
			return m;
		}

		if ( array[m] > key ) {
			return binary_searchR( array, first, m - 1, key );
		}
		else {
			return binary_searchR( array, m + 1, last, key );
		}
	}
}

/**
 * Time complexity of binary_search (non-recursive): O(log(n)).
 */
template<class T>
int binary_searchNR(T array[], int first, int last, T key)
{
	int m;
	m = ( first + last ) / 2;

	while ( array[m] != key ) {
		if ( array[m] > key ) {
			last = m - 1;
		}
		else {
			first = m + 1;
		}

		if ( first > last ) {
			return -1;
		}

		m = ( first + last ) / 2;
	}

	return m;
}
