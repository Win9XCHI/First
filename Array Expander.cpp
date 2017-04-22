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
		
		cout << endl << "Size second array - " << amount * 2;
		cout << endl << "     Second array ";
		for(int i=0;i<amount*2;i++) {
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
	int *NewArray = new int[size * 2];
	for(int i=0;i<size*2;i++) {
		if(i<size) {
			NewArray[i] = array[i];
		}
		else {
			NewArray[i] = 0;
		}
	}
	return NewArray;
}




