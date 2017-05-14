#include <iostream>
#include <cstring>
#include <fstream>
#include <list>
#include <random>
#include <algorithm>
#include <iterator>
#include <ctime>
#include <stdlib.h>
#include <unistd.h>
#include <numeric>
using namespace std;

template < class T >	
void RandNumber(list<T> &theList ) {
	theList.clear();
	for ( int i = 0; i < 20; i++ ) {
		srand( time( NULL ) + i );
		theList.push_back(1000 + (T)rand() / ( (T)RAND_MAX / (T)(2000 - 1000) ) );
	}
}

template < class T >	
void SumNumber(list<T> &theList ) {
  T sum = 0;
  sum = accumulate(theList.begin(), theList.end(), 0);
	cout << endl << "Sum: " << sum;
}

template < class T >	
void AverageNumber(list<T> &theList ) {
  T sum = 0;
	sum = accumulate(theList.begin(), theList.end(), 0);
	sum = sum / 20;
	cout << endl << "Average value: " << sum;
}

int pred(int i) {
	return (i >= 1500 && i <= 1900); 
}

double dive(double i) {
	return (i / 2. ); 
}

template < class T >	
void DivideNumber(list<T> &theList ) {
	transform(theList.begin(), theList.end(), theList.begin(), dive);
}

template < class T , class ForwardIterator>	
void SwapNumber(list<T> &theList, ForwardIterator beg, ForwardIterator end ) {
	for (ForwardIterator it1 = beg; it1 != end; ++it1) {
		iter_swap(theList.begin(), it1);
	}
}

template < class T >	
void MinMaxNumber(list<T> &theList ) {
	cout << endl << "Max: " << *max_element(theList.begin(), theList.end());
	cout << endl << "Min: " << *min_element(theList.begin(), theList.end());
}

template < class T >	
void SortNumber(list<T> &theList ) {
	theList.sort();
}

template < class T >	
void FileInNumber(list<T> &theList ) {
	ofstream File;
	File.open("InFile.txt");
	copy(theList.begin(), theList.end(), ostream_iterator<T>(File, " "));
	File.close();
}

template < class T >	
void DelNumber(list<T> &theList ) {
	theList.clear();
}

template < class T >	
int FileOutNumber(list<T> &theList ) {
	int check(0);
	string temp;
	theList.clear();
	ifstream File("InFile.txt");
	
	while (getline(File, temp)) {
		if (temp.length() > 0 && temp[0] != ' ') {
			check++;
			break;
		}
	}

	File.close();
	File.open("InFile.txt");

	if (check != 0) {
		copy(istream_iterator<T>(File), istream_iterator<T>(), back_inserter(theList));
	}

	File.close();

	return check;
}

void show(double i)  {
    cout << i << " "; 
}

template < class T >	
void PrintNumber(list<T> &theList ) {
	for_each(theList.begin(), theList.end(), show);
}

template < class T , class ForwardIterator>
int Menu(list<T> &theList, ForwardIterator itB, ForwardIterator itE) {
	char buff[255];
	int menu = 1;
	
	cout << endl << "--------------- Menu --------------";
	cout << endl << "1  - New random numbers";
	cout << endl << "2  - Sum of all numbers";
	cout << endl << "3  - Calculation of the mean of all numbers";
	cout << endl << "4  - Search for the first number in the range of [1500, 1900]";
	cout << endl << "5  - Division all numbers by 2";
	cout << endl << "6  - Swap";
	cout << endl << "7  - Search max and min";
	cout << endl << "8  - Sort";
	cout << endl << "9  - Write file";
	cout << endl << "10 - Empty the list of contents";
	cout << endl << "11 - Read file";
	cout << endl << "12 - Print all numbers";
	cout << endl << "0  - Exit";
	cout << endl << "\nEnter : ";
	cin.getline(buff,255);
	menu = atoi(buff);
		
	switch (menu) {
			case 1: {
				RandNumber(theList);
				system("clear");
				cout << endl << "The numbers are generated";
				break;
			}
			case 2: {
				if (theList.size() != 0) {
					SumNumber(theList);
				}
				else {
					system("clear");
					cout << endl << "List is NULL";
					usleep(2000);
					system("clear");
				}
				break;
			}
			case 3: {
				if (theList.size() != 0) {
					AverageNumber(theList);
				}
				else {
					system("clear");
					cout << endl << "List is NULL";
					usleep(2000);
					system("clear");
				}
				break;
			}
			case 4: {
				if (theList.size() != 0) {
					menu = 4;
				}
				else {
					system("clear");
					cout << endl << "List is NULL";
					usleep(2000);
					system("clear");
				}
				break;
			}
			case 5: {
				if (theList.size() != 0) {
					DivideNumber(theList);
					system("clear");
					cout << endl << "The numbers are divided";
				}
				else {
					system("clear");
					cout << endl << "List is NULL";
					usleep(2000);
					system("clear");
				}
				break;
			}
			case 6: {
				if (theList.size() != 0) {
					SwapNumber(theList, itB, itE);
					system("clear");
					cout << endl << "The numbers are moved";
				}
				else {
					system("clear");
					cout << endl << "List is NULL";
					usleep(2000);
					system("clear");
				}
				break;
			}
			case 7: {
				if (theList.size() != 0) {
					MinMaxNumber(theList);
				}
				else {
					system("clear");
					cout << endl << "List is NULL";
					usleep(2000);
					system("clear");
				}
				break;
			}
			case 8: {
				if (theList.size() != 0) {
					SortNumber(theList);
					system("clear");
					cout << endl << "The numbers are sorted";
				}
				else {
					system("clear");
					cout << endl << "List is NULL";
					usleep(2000);
					system("clear");
				}
				break;
			}
			case 9: {
				if (theList.size() != 0) {
					FileInNumber(theList);
					system("clear");
					cout << endl << "File written";
				}
				else {
					system("clear");
					cout << endl << "List is NULL";
					usleep(2000);
					system("clear");
				}
				break;
			}
			case 10: {
				if (theList.size() != 0) {
					DelNumber(theList);
					system("clear");
					cout << endl << "Numbers deleted";
				}
				else {
					system("clear");
					cout << endl << "List is NULL";
					usleep(2000);
					system("clear");
				}
				break;
			}
			case 11: {
				if (FileOutNumber(theList) == 0) {
					system("clear");
					cout << endl << "File is empty";
					usleep(2000);
					system("clear");
				}
				else {
					system("clear");
					cout << endl << "The numbers are read";
				}
				break;
			}
			case 12: {
				if (theList.size() != 0) {
					PrintNumber(theList);
				}
				else {
					system("clear");
					cout << endl << "List is NULL";
					usleep(2000);
					system("clear");
				}
				break;
			}
			case 0: {
				menu = 0;
				break;
			}
	}

	return menu;
}

int main() {
	char buff[255];
	int type = 0, menu = 1;
	
	while (type < 1 || type > 2) {
		cout << endl << "Enter the type of data (1 - int, 2 - double) : ";
		cin.getline(buff,255);
		type = atoi(buff);
	}

	if (type == 1) {
		list<int> myList;
		list<int>::iterator it = myList.begin();

		while (menu != 0) {
			system("clear");
			menu = Menu(myList, myList.begin(), myList.end());

			if (menu == 4) {
    		it = find_if(myList.begin(), myList.end(), &pred); 
				
				if (it == myList.end()) {
					cout << "Not element" << endl << endl;
				} 
				else {
					cout << "The first element in the range: " << *it << endl << endl;
				}
			}

			if (menu != 0) {
				cout << endl << endl << "Press enter to return to Menu";
				cin.get();
			}
		}
	}
	else {
		 list<double> myList;
		 list<double>::iterator it=myList.begin();

		 while (menu != 0) {
		 	system("clear");
			menu = Menu(myList, myList.begin(), myList.end());

			if (menu == 4) {
				it = find_if(myList.begin(), myList.end(), &pred); 

				if (it == myList.end()) {
					cout << "Not element" << endl << endl;
				} 
				else {
					cout << "The first element in the range: " << *it << endl << endl;
				}
			}

			if (menu != 0) {
				cout << endl << endl << "Press enter to return to Menu";
				cin.get();
			}
		}
	}

	return 0;
}
