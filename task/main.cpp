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
	bool flag = false;
	string FileOut = "";
	string FileIn = "";
	regex reg("\Q+|-|*|/\E");

	while (getline(outFile, FileOut)) {
		for(int i = 0; i < FileOut.length(); i++) {
			if (regex_match(FileOut[i], reg)) {
				if ( Object.GetNowSize() == STACKSIZE ) {
					fputs( "Error: stack overflow\n", stderr );
					abort();
				}
				else {
					if (flag) {
						if ( Object.GetNowSize() == 0 ) {
								fputs( "Error: stack underflow\n", stderr );
								abort();
						}
						else {
							FileIn += Object.pop();
							FileIn += " ";
							Object.push(FileOut[i]);
						}
						flag = false;
					}
					else {
						Object.push(FileOut[i]);
					}
				}
			}
			else {
				FileIn += FileOut[i];
				FileIn += " ";
				flag = true;
			}
		}

		while ( Object.GetNowSize() != 0) {
			FileIn += Object.pop();
			FileIn += " ";
		}
		inFile << FileIn << endl;
	}

	outFile.close();
	inFile.close();
	cin.get();
	return 0;
}

