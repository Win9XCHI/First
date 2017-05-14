#include <iostream>
#include <cstring>
//#include <cctupe>
#include <ctype.h>
using namespace std;

bool Check(const char *);
// My name is ....
int main() {
	bool flag=false;
	char buff[255];
	cout<< endl<< " My name is ...." << endl << endl;
	
	while (!flag) {	
		cout<< "Enter password: " << endl;
		cin.getline(buff,255);
		flag = Check(buff);
	}
	
	cout<< endl<< " Password accepted! " << endl ;
	cin.get();
	return 0;
}

bool Check(const char *c) {
	bool check = true,low=false,up=false,number=false;
	if(strlen(c)<6) {
		check = false;
		cout<< "--Short password!" << endl << "------" << endl << endl;
	}
	else {
		for(int i = 0; i < strlen(c); i++) {
			if(isupper(c[i])) {
				up = true;
			}
			if(islower(c[i])) {
				low = true;
			}
			if(isdigit(c[i])!=0) {
				number = true;
			}
		}
		if(!up) {
			check = false;
			cout<< "--The password does not contain an upper case!" << endl << "------" << endl;
			
		}
		if(!low) {
			check = false;
			cout<< "--Password does not contain lowercase!" << endl << "------" << endl;	
		}
		if(!number) {
			check = false;
			cout<< "--Password does not contain digits!" << endl << "------" << endl;
		}
	}
	return check;
}






