#include <iostream>
/*#include <cstring>
#include <fstream>
#include <list>
#include <random>
#include <algorithm>
#include <iterator>
#include <windows.h>
#include <ctime>*/
#include "sort.h"
#include "file.h"
#include "array.h"
#include "myself.h"
using namespace std;

int main() {
	//Start test sort
	if (testSort(1) == 0) {
		cout << endl << "Quick sort error!";
	}
	else {
		cout << endl << "Quick sort fulfilled successfully!";
	}
	if (testSort(2) == 0) {
		cout << endl << "Shell sort error!";
	}
	else {
		cout << endl << "Shell sort fulfilled successfully!";
	}
	if (testSort(3) == 0) {
		cout << endl << "Bubble sort error!";
	}
	else {
		cout << endl << "Bubble sort fulfilled successfully!";
	}
	if (testSort(4) == 0) {
		cout << endl << "Selection sort error!";
	}
	else {
		cout << endl << "Selection sort fulfilled successfully!";
	}
	//Finish test sort
	cout << endl;
	//Start test file
	if (testFile() == 0) {
		cout << endl << "Error file!";
	}
	else {
		cout << endl << "The file is read and written successfully!";
	}
	//Finish test file
	cout << endl;
	//Start test array
	if (testArray() == 0) {
		cout << endl;
		cout << endl << "Error array!";
	}
	else {
		cout << endl;
		cout << endl << "Array successfully!";
	}
	//Finish test array
	cout << endl;
	//Start test myself
	if (testMyself() == 0) {
		cout << endl << "Error myself!";
	}
	else {
		cout << endl << "Myself successfully!";
	}
	//Finish test myself
	return 0;
}








