#include <iostream>
#include "sort.h"
#include "file.h"
#include "array.h"
#include "myself.h"

using namespace std;

int main() {
	// Start test sorting.
	testSort(1);
	testSort(2);
	testSort(3);
	testSort(4);
	// Finish test sorting.
	
	cout << endl << "------------------------------------------------" << endl;
	
	// Start test file.
	if (testFile() == 0) {
		cout << endl << "File error!";
	}
	else {
		cout << endl << "The file is read and wrote successfully!" << endl;
	}
	// Finish test file.
	
	cout << endl << "------------------------------------------------" << endl;
	
	// Start test array.
	if (testArray() == 0) {
		cout << endl;
		cout << endl << "Array error!";
	}
	else {
		cout << endl;
		cout << endl << "Array successfully!";
	}
	// Finish test array.
	
	cout << endl << "------------------------------------------------" << endl;
	
	// Start test myself.
	if (testMyself() == 0) {
		cout << endl << "Error myself!";
	}
	else {
		cout << endl << "Myself successfully!" << endl << endl;
	}
	// Finish test myself.

	return 0;
}
