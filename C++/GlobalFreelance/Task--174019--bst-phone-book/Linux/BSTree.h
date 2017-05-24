#ifndef _BSTree
#define _BSTree

#include <iostream>
#include <vector>
#include <cstring>

class Contact
{
public:
	Contact() { Name[0] = '\0', Phone[0] = '\0', left = nullptr; right = nullptr; }
	Contact(char *name, char *phone) { 
		strncpy(Name, name, 49); Name[49] = '\0';
		strncpy(Phone, phone, 49); Phone[49] = '\0';
		left = nullptr; right = nullptr;
	}
	char Name[50];
	char Phone[50];
	Contact *left;
	Contact *right;
};


class BSTree
{
public:
	BSTree();
	~BSTree();
	Contact* findParent(Contact *child, Contact*root);
	bool findContact(char *name);
	bool insertContact(char *name, char *phone);
	Contact* displayCurrent();
	void outputSortList();
	void sortList(Contact *root);
	char *findSmallDecendent(Contact *decendent);

	void deleteCurrent();
	
private:
	Contact *Root;
	Contact *current;
	bool find(char *name, Contact *root);
	bool insert(char *name, char *phone, Contact *root);
	void deleteAll(Contact *root);

	void deleteContact(Contact *toDelete);
};

#endif
