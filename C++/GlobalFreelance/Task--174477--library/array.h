#ifndef _ARRAY
#define _ARRAY

#include <iostream>
#include <cstdlib>
#include <cmath>
#include <ctime>
using namespace std;

void printArr(const int arr[], int size) {
	cout << endl << "     Array" << endl;
	for (int i = 0; i < size; i++) {
		cout << arr[i];
		if (i != size - 1) {
			cout << ", ";
		}
	}
}

int compare_a(const void * x1, const void * x2) {
  return ( *(int*)x1 - *(int*)x2 );              
}

int getMedianOf(const int arr[], unsigned int size) {
	int *NewArray = new int[size];
	int median;
	
	for (unsigned int i = 0; i < size; i++) {
		NewArray[i] = arr[i];
	}
	qsort(NewArray, size, sizeof(int), compare_a);
	median = NewArray[(size / 2) - 1];
	delete []NewArray;
	return median;
}

double getMeanOf(const int arr[], unsigned int size) {
	return arr[(size / 2) - 1];
}

double getSDOf(const int arr[], unsigned int size) {
	double average = 0, dispersion = 0;
	int *NewArray = new int[size];
	
	for (unsigned int i = 0; i < size; i++) {
		average += arr[i];
	}
	average /= size;
	
	for (unsigned int i = 0; i < size; i++) {
		NewArray[i] = arr[i] - average;
		dispersion += pow(NewArray[i], 2);
	}
	dispersion /= size;
	delete []NewArray;
	return pow(dispersion, 1/2);
}

long long getSumOf(const int arr[], unsigned int size) {
	long long sum = 0;
	
	for (unsigned int i = 0; i < size; i++) {
		sum += arr[i];
	}
	return sum;
}

int isArrSorted(const int arr[], unsigned int size) {
	int *NewArray = new int[size];
	bool flag = false;
	
	for (unsigned int i = 0; i < size; i++) {
		NewArray[i] = arr[i];
	}
	qsort(NewArray, size, sizeof(int), compare_a);
	
	for (unsigned int i = 0; i < size; i++) {
		if (arr[i] == NewArray[i]) {
			
		}
		else {
			flag = true;
		}
	}
	if (!flag) {
		return 1;
	}
	else {
		flag = false;
	}
	int j = size;
	
	for (unsigned int i = 0; i < size; i++) {
		if (arr[j] == NewArray[i]) {
			j--;
		}
		else {
			flag = true;
		}
	}
	if (!flag) {
		return -1;
	}
	delete []NewArray;

	return 0;
}

int isArrSame(const int arr1[], const int arr2[], unsigned int size) {
	
	for (unsigned int i = 0; i < size; i++) {
		if (arr1[i] != arr2[i]) {
			return 0;
		}
	}

	return 1;
}

void fillWithRandom(int data[], unsigned int size, int min, int max) {

	for (unsigned int i = 0; i < size; i++ ) {
		srand( time( NULL )+i ); //Dynamic random
		data [i] = rand() % min + (max - min);
	}
}

void fillWithVal(int data[], unsigned int size, int value) {
	for (unsigned int i = 0; i < size; i++) {
		data[i] = value;
	}
}

int testArray() {
	int arr[10] = {2,5,1,-4,2,0,5,1,9,4};
	int arr1[10] = {1,6,0,-3,3,1,4,2,8,6};
	int arr2[10] = {1,6,0,-3,3,1,4,2,8,6};
	printArr(arr,10);
	cout << endl << "Median = " << getMedianOf(arr,10);
	cout << endl << "Mean = " << getMeanOf(arr,10);
	cout << endl << "SDO = " << getSDOf(arr,10);
	cout << endl << "Sum = " << getSumOf(arr,10);
	//
	//qsort(arr, 10, sizeof(int), compare_a);
	//
	if (isArrSorted(arr,10) == 1) {
		cout << endl << "Sorted in increasing order!";
	}
	else {
		if (isArrSorted(arr,10) == -1) {
			cout << endl << "Sorted in decreasing order!";
		}
		else {
			cout << endl << "Array is not sorted!";
		}
	}
	if (isArrSame(arr1,arr2,10) == 1) {
		cout << endl << "Arrays are the same!";
	}
	else {
		cout << endl << "Arrays not are the same!";
	}
	fillWithRandom(arr,10,200,400);
	
	for (int i = 0; i < 10; i++) {
		if (arr[i] < 200 || arr[i] > 400) {
			return 0;
		}
	}
	printArr(arr,10);
	fillWithVal(arr,10,-9);
	for (int i = 0; i < 10; i++) {
		if (arr[i] != -9) {
			return 0;
		}
	}
	printArr(arr,10);
	return 1;
}

#endif
