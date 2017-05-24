#include "stdafx.h"
#include <iostream>
#include "BSTree.h"
#define nullptr NULL

using namespace std;
void test(BSTree &bst);
int menu();

int main()
{
	char buff[255];
	cout << "hello world"<<endl;
	BSTree bst;
	//test(bst);

	int choice = 0;
	while (choice != 9)
	{
		choice = menu();
		int input;
		switch(choice)
		{
		case 1:
			cout << "enter number to insert: ";
			cin >> input;
			cin.clear();
			while(cin.get() != '\n');
			cout << "enter number to name: ";
			cin.getline(buff,255);
			if (bst.insertNode(input, buff))
				cout << "insert succesful" << endl;
			else
				cout << "insert Failed";
			break;
		case 2:
			cout << "enter number to find: ";
			//cin >> input;
			cin.clear();
			while(cin.get() != '\n');
			cin.getline(buff,255);
			if (bst.findNode(/*input*/ buff))
				cout << "number found, set to current" << endl;
			else
				cout << "number not found" << endl;
			break;
		case 3:
			cout<<endl<<endl<<"The Current Node is "<<bst.displayCurrent()<<" Name: " <<bst.displayCurrentName()<<endl;
			break;
		case 4:
			cout << endl << "The current list is:" << endl;
			bst.outputSortList();
			break;
		case 5:
			bst.deleteCurrent();
			break;
		case 9:
			break;
		default:
			cout << choice << " is an invalid choice" << endl;
		}
	}

	
	return EXIT_SUCCESS;
}

int menu()
{
	cout <<endl<<endl<<endl<<endl
		 << "1. add integer"		<< endl
		 << "2. find node"			<< endl
		 << "3. display current"	<< endl
		 << "4. display list"		<< endl
		 << "5. delete"				<< endl
		 << "9. quit"				<< endl;
	int choice;
	cin >> choice;
	return choice;

}

void test(BSTree &bst)
{
	bst.insertNode(10, "Mark");
	bst.insertNode(2, "Fil");
	bst.insertNode(6, "Sara");
	bst.insertNode(3, "Lola");
	bst.insertNode(13, "St. Cravs");
	bst.findNode("Mark");
	bst.outputSortList();
}
