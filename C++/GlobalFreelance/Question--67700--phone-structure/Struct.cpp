#include <iostream>
#include <fstream>
using namespace std;

struct Phone {
	int AreaCode;
	int Exchange;
	int Number;
};

int main() {
	Phone Object;
	Phone ObjectInput;
	char buff[255];
	Object.AreaCode = 212;
	Object.Exchange = 767;
	Object.Number = 8900;
	unsigned long long int inPhone(0);
	cout << "Enter numper phone" << endl;
	cin>>inPhone;
	if ((inPhone / 1000000000) > 9) {
		cout << endl << inPhone << " - It's too long" << endl;
	}
	else {
		if(inPhone < 100000000) {
			cout << endl << inPhone << " - Number is too short" << endl;
		}
		else {
			ObjectInput.AreaCode = inPhone / 10000000;
			ObjectInput.Exchange = (inPhone - (inPhone / 10000000) * 10000000) / 10000;
			ObjectInput.Number = (inPhone - ((inPhone / 10000) * 10000));
			cout << endl << "     First struct" << endl;
			cout << "Area code: " << Object.AreaCode << endl;
			cout << "Exchange: " << Object.Exchange << endl;
			cout << "Number: " << Object.Number << endl;
			cout << endl << "     Second struct" << endl;
			cout << "Area code: " << ObjectInput.AreaCode << endl;
			cout << "Exchange: " << ObjectInput.Exchange << endl;
			cout << "Number: " << ObjectInput.Number << endl;
		}
	}
	cin.get();
	return 0;
}






