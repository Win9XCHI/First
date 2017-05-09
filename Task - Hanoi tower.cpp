#include "stdafx.h"
#include <iostream>
#include <cmath>
using namespace std;

//prototype
void moveDisks(int count, int needle1, int needle3, int needle2, int*);

int main() {
	int NumMovements[9];
	for	(int i = 0; i < 9; i++) {
		NumMovements[i] = 0;
	}
	cout << "Moves for 3 disk problem." << endl;
	
	moveDisks(9, 1, 3, 2, &NumMovements[0]);
	
	if (NumMovements[0] >= (pow(2.,9) - 1)) {
		for (int i = 0; i < 9; i++) {
			cout << endl << "Number of movements of the disk " << i+1 << ": " << NumMovements[i];
		}
	}
	else {
		cout << endl << "Minimum disk movement is not reached";
	}
	cin.get();
	return 0;
}

//tower of hanoi
void moveDisks(int count, int needle1, int needle3, int needle2, int *NumMovements) {
	if (count > 0) {
		moveDisks(count - 1, needle1, needle2, needle3, &NumMovements[0]);
		NumMovements[count - 1]++;
		cout << "Move disk " << count << " from " << needle1 << " to " << needle3 << "." << endl;
		
		moveDisks(count - 1, needle2, needle3, needle1, &NumMovements[0]);
		NumMovements[count - 1]++;
	}
}
