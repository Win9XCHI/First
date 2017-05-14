#include <iostream>
#include <cstring>
#include <fstream>
using namespace std;

// My name is ....
int main() {
	ofstream inFile;
	ifstream outFile("Gobblygook.txt");
	inFile.open("Gettysburg.txt");
	cout << endl << " My name is ...." << endl << endl;
	string temp;
	int code;
	cout << endl << "                     Encrypted file" << endl << endl;
	
	while ( getline(outFile,temp) ) {
        cout << temp << endl;
        for (int i = 0; i < temp.length(); i++) {
        	if (temp[i] != ' ') {
	        	code = (int)(temp[i]);
				code = code - 10;
				temp[i] = (char)(code);
			}
		}
		inFile << temp;
		inFile << endl;	
    } 

	cout << endl << endl;
	inFile.close();
	outFile.close();
	outFile.open("Gettysburg.txt");
	cout << endl << "                      Non-encrypted file" << endl << endl;
	
	while (getline(outFile,temp)) {
		cout << temp;
		cout << endl;
	}
	
	outFile.close();
	cin.get();
	
	return 0;
}








