#include <iostream>
#include <cstring>
#include <fstream>
using namespace std;

// My name is ....
int main() {
	string str;
	int spaces(0), CarriageReturns(0), Lincoln(0);
	ifstream outFile("Gettysburg.txt");
	cout << endl << " My name is ...." << endl << endl;
	
	while ( getline(outFile,str) ) {
		cout << str << endl;
		if( !str.empty() ) {
			for (int i = 0; i < str.size()-1; i++) {
				if ((str[i] == ' ') && (str[i+1] != ' ')) {
					spaces++;
				}
			}
			for (int i = 0; i < str.length()-1; i++) {
				if ((str[i] == '-') && (str[i+1] != '-')) {
					Lincoln++;
				}
			}	
		}
		CarriageReturns++;
	}
	
	cout << endl << "Spaces - " << spaces;
	cout << endl << "Carriage returns - " << CarriageReturns;
	cout << endl << " '-' - " << Lincoln;
	cout << endl << "Numbers from a string: " << spaces - Lincoln;
	outFile.close();
	cin.get();
	
	return 0;
}





