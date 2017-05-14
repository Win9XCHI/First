#include <iostream>
#include <fstream>
using namespace std;

int *FuncArray(int *,int);

int main() {
	ofstream inFile;
	ifstream outFile("GradeList.txt");
	inFile.open("filename.txt");
	int amount(0);
	int temp(0), *array;
	while (outFile >> temp) {
		amount++;
	}
	if(amount == 0) {
		cout << "Size array in file is zero!" << endl;
	}
	else {
		outFile.close();
		outFile.open("GradeList.txt");
		array = new int[amount];
		for(int i=0;i<amount;i++) {
			outFile >> array[i];
		}
		cout << "Size first array - " << amount;
		cout << endl << "     First array ";
		for(int i=0;i<amount;i++) {
			cout << endl<< array[i];
		}
		
		int *NewArray = FuncArray(&array[0], amount);
		
		cout << endl << "Size second array - " << amount + 1;
		cout << endl << "     Second array ";
		for(int i=0;i<amount+1;i++) {
			cout << endl << NewArray[i];
			inFile << NewArray[i] << endl;
		}
		delete []array;
		delete []NewArray;
}
	inFile.close();
	outFile.close();
	
	cin.get();
	return 0;
}

int *FuncArray(int *array,int size) {
	int *NewArray = new int[size + 1];
	for(int i=0;i<size;i++) {
			NewArray[i+1] = array[i];
	}
	NewArray[0] = 0;
	return NewArray;
}




