#ifndef _MYSELF
#define _MYSELF

#include <iostream>
#include <cmath>
using namespace std;

int function1(const int arr[], unsigned int size) {
	int Even(0);
	
	for (unsigned int i = 0; i < size; i++) {
		if (arr[i] % 2 == 0) {
			Even++;
		}
	}
	return Even;
} 

int function2(const int arr1[], const int arr2[], unsigned int size) {
	bool flag = false;
	int point = -1;
	
	for (unsigned int i = 0; i < size; i++) {
		if (arr1[i] != arr2[i]) {
			flag = true;
		}
		else {
			point = i;
		}
	}
	if (flag && point == -1) {
		return 0;
	}
	else {
		if (flag && point != -1) {
			return 1;
		}
		else {
			return 2;
		}
	}
}

void function3(int arr[], unsigned int size, int value1, int value2) {
	
	for (unsigned int i = 0; i < size; i++) {
		if (value1 == arr[i]) {
			arr[i] = value2;
		}
	}
}

int testMyself() {
	int arr[10] = {2,5,1,-4,2,0,5,1,9,4};
	int arr2[10] = {2,5,1,-4,1,0,5,1,9,4};
	bool flag = false;
	int Even = function1(arr,10);
	if (Even != 5) {
		return 0;
	}
	if (function2(arr,arr2,10) != 1) {
		return 0;
	}
	function3(arr,10,1,-9);
	
	for (int i = 0; i < 10; i++) {
		if (arr[i] == -9) {
			flag = true;
		}
	}
	if (!flag) {
		return 0;
	}
	return 1;
}


#endif
