#include <iostream>
#include <fstream>
#include <regex>
#include "StackType.h"
#define STACKSIZE 10
using namespace std;

int main() {
	StackType Object(STACKSIZE);
	ofstream inFile;
	ifstream outFile("Prefix.in");
	inFile.open("Postfix.out");
	bool flag_c;
	string FileOut = "";
	string FileIn = "";
	string buff = "";
	regex reg("[+|-|*|/]");
	int check,u;
	bool *flag = new bool[STACKSIZE+1];
	for (int i = 0; i < STACKSIZE+1; i++) {
		flag[i] = false;
	}
	
	while (getline(outFile, FileOut)) {
		FileIn = "";
		check = 0;
		flag_c = false;
		u = 0;
		cout << endl << " Prefix: " << FileOut;
		
		for (int i = 0; i < FileOut.length(); i++) {
			buff = FileOut[i];
			if (buff != " ") {
				if (regex_match(buff, reg)) {
					if ( Object.isFullStack() ) {
						fputs( "Error: stack overflow\n", stderr );
						abort();
					}
					else {
						Object.push(FileOut[i]);
						if (flag_c) {
							check = 0;
						}
					}
				}
				else {
					flag_c = true;
					if ( Object.GetNowSize() != 0 && check != 1) {
						flag[Object.GetNowSize()] = true;
					}
					check++;
					FileIn += FileOut[i];
					FileIn += " ";
					if (check % 2 == 0) {
						int s = Object.GetNowSize()+1;
						
						for (int n = 1; n < s; n++) {
							if (flag[n]) {
								if ( Object.isEmptyStack() ) {
									fputs( "Error: stack underflow\n", stderr );
									abort();
								}
								else {
									FileIn += Object.pop();
									FileIn += " ";
									flag[n] = false;
									if (u == 0) {
										flag[n-1] = true;
										u++;
									}
								}
							}
						}
						check = 1;
					}
				}
			}
			buff = "";
		}
		cout << endl << "Postfix: " << FileIn;
		inFile << FileIn;
		inFile << endl;
	}
	delete []flag;
	outFile.close();
	inFile.close();
	
	return 0;
}
