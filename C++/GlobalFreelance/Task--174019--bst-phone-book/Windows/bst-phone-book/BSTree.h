#ifndef _BSTree
#define _BSTree

#include <iostream>
#include <vector>
#include <cstring>
#define nullptr NULL

class node
{
public:
	node() { data = 0;left = nullptr;right = nullptr; }
	node(int i, char *name) {strncpy(Name, name, 49);Name[49] = '\0';data = i;left = nullptr;right = nullptr;}
	int data;
	char Name[50];
	node *left;
	node *right;
};


class BSTree
{
public:
	BSTree();
	~BSTree();
	node* findParent(node *child,node*root);
	bool findNode(char *name);
	bool insertNode(int i, char *name);
	int displayCurrent();
	char *displayCurrentName();
	void outputSortList();
	void sortList(node *root);
	int  findSmallDecendent(node*decendent);

	void deleteCurrent();
	
private:
	node *Root;
	node *current;
	bool find(char *i, node *root);
	bool insert(int i, char *name, node *root);
	void deleteAll(node *root);

	void deleteNode(node *toDelete);
};

#endif
