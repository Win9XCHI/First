#ifndef _FILE
#define _FILE

#include <iostream>
#include <fstream>
using namespace std;

int readFromFile(int *data, unsigned int size, const char *filename) {
	ifstream outFile(filename);
	char buff[10];
	size = 0;
	
	while (outFile >> buff) {
		data[size] = atoi(buff);
		size++;
	}
	outFile.close();
	return size;
}

int writeToFile(const int *data, unsigned int size, const char *filename) {
	ofstream inFile;
	inFile.open(filename);
	unsigned int size_t = 0;
	
	while (inFile << data[size_t] && size != size_t + 1) {
		inFile << endl;
		size_t++;
	}
	inFile.close();
	return size_t;
}
 
int testFile() {
	int data[100];
	int size = 0;
	int data2[9] = {43,7,-1,35,2,9,0,321,6};
	
	for (int i = 0; i < 100; i++) {
		data[i] = 0;
	}
	size = readFromFile(data, size, "file.txt");
	if (size == 0) {
		return 0;
	}
	
	for (int i = 0; i < size; i++) {
		if (data[i] != data2[i]) {
			return 0;	
		}
	}
	size = writeToFile(data, size, "file.txt");
	if (size == 0) {
		return 0;
	}
	return 1;
}


#endif
