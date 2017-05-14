#include <iostream>
#include <cstring>
//#include <cctupe>
#include <ctype.h>
using namespace std;

int NumperString(const char *);
// My name is ....
int main() {
	char str[255];
	cout<< endl<< " My name is ...." << endl << endl;
	cout<< "Enter string: " << endl;
	cin.getline(str,255);
	cout<< "Numbers from a string: " << NumperString(str);
	cin.get();
	return 0;
}

int NumperString(const char *c) {
	int point = 0;
	if(c[0]==' ') {
		point = 0;
	}
	else {
		point = 1;
	}
	for(int i = 0; i < strlen(c)-1; i++) {
		if((c[i] == ' ') && c[i+1] != ' ') {
			point++;
		}
	}
	return point;
}






