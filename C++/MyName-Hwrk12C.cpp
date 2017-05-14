#include <iostream>
#include <cstring>
#include <fstream>
using namespace std;

// My name is ....
int main() {
	string str;
	char strName[50];
	int spaces(0), CarriageReturns(0), Lincoln(0);
	bool flag = false;
	cout << endl << " My name is ...." << endl << endl;
	cout << " Enter name file " << endl << endl;
	cin.getline(strName,50);
	strcat(strName, ".txt");
	ifstream outFile(strName);
	
	while ( getline(outFile,str) ) {
		flag = true;
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
	
	if (flag) {
		cout << endl << "Spaces - " << spaces;
		cout << endl << "Carriage returns - " << CarriageReturns;
		cout << endl << " '-' - " << Lincoln;
		cout << endl << "Numbers from a string: " << spaces - Lincoln;
	}
	else {
		cout << endl << "The file is empty or does not exist!";
	}
	outFile.close();
	cin.get();
	
	return 0;
}
