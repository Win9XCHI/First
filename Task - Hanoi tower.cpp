// Task - Hanoi tower.cpp : Defines the entry point for the console application.
//
#include "stdafx.h"
#include <iostream>
using namespace std;

//prototype
void moveDisks(int count, int needle1, int needle3, int needle2);

int main() {
	cout << "Moves for 3 disk problem." << endl;
	
	moveDisks(9, 1, 3, 2);
	
	cin.get();
	return 0;
}

//tower of hanoi
void moveDisks(int count, int needle1, int needle3, int needle2) {
	if (count > 0) {
		moveDisks(count - 1, needle1, needle2, needle3);

		cout << "Move disk " << count << " from " << needle1 << " to " << needle3 << "." << endl;
		
		moveDisks(count - 1, needle2, needle3, needle1);
	}
}
