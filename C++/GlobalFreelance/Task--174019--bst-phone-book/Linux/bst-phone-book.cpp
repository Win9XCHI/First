#include <iostream>
#include "BSTree.h"
#define QUIT_CODE 6

using namespace std;
int menu();

int main()
{
	char contact_phone[255];
	BSTree bst;

	int choice = 0;
	while (choice != QUIT_CODE)
	{
		choice = menu();
		char contact_name[100];

		switch(choice)
		{
			case 1: {
				cout << "Enter name to insert: ";
				cin >> contact_name;
				cin.clear();

				while (cin.get() != '\n');
					cout << "Enter phone number to insert: ";
				
				cin.getline(contact_phone, 255);
				
				if (bst.insertContact(contact_name, contact_phone))
					cout << "Insert successful" << endl;
				else
					cout << "Insert failed";
				break;
			}
			case 2: {
				cout << "Enter name to find: ";
				cin.clear();

				while (cin.get() != '\n');
					cin.getline(contact_name, 255);

				if (bst.findContact(contact_name))
					cout << "Name found, set to current" << endl;
				else
					cout << "Name not found" << endl;
				break;
			}
			case 3: {
				cout << endl << "The Current item has name: " << bst.displayCurrent()->Name << endl
					 << "The Current item has phone: " << bst.displayCurrent()->Phone << endl;
				break;
			}
			case 4: {
				cout << endl << "The current list is:" << endl;
				bst.outputSortList();
				break;
			}
			case 5: {
				bst.deleteCurrent();
				break;
			}
			case QUIT_CODE:
				break;
			default:
				cout << choice << "Enter incorrect key!" << endl;
		}
	}

	return EXIT_SUCCESS;
}

int menu()
{
	cout << endl << endl
		 << "1. Add contact" << endl
		 << "2. Find contact" << endl
		 << "3. Display current contact" << endl
		 << "4. Display list" << endl
		 << "5. Delete"	<< endl
		 << "6. Quit" << endl;
	int choice;
	cin >> choice;
	return choice;
}
