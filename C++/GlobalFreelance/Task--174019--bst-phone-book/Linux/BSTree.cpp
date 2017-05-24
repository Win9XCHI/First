#include "BSTree.h"

using namespace std;

BSTree::BSTree()
{
	current = nullptr;
	Root = nullptr;
}

BSTree::~BSTree()
{
	deleteAll(Root);
}

bool BSTree::findContact(char *name)
{
	return find(name, Root);
}

bool BSTree::find(char *name, Contact *root)
{
	if (root != nullptr)
	{
		if (strcmp(root->Name, name) == 0)
		{
			current = root;
			return true;
		}
		else if (strcmp(root->Name, name) > 0)
		{
			return find(name, root->left);
		}
		else if (strcmp(root->Name, name) < 0)
		{
			return find(name, root->right);
		}
			
	}
	return false;
}

//call with Root to search entire tree "findParent(child,Root)"
Contact* BSTree::findParent(Contact *child, Contact *root)
{
	if (child == root || root==nullptr)
		return nullptr;
	else if (root->left==child || root->right==child)
	{
		return root;
	}
	else if (strcmp(child->Name, Root->Name) <	 0)
	{
		return findParent(child, root->left);
	}
	else
	{
		return findParent(child, root->right);
	}
}

bool BSTree::insertContact(char *name, char *phone)
{
	return insert(name, phone, Root);
}

bool BSTree::insert(char *name, char *phone, Contact *root)
{
	if (root == nullptr)
	{
		Root = new Contact(name, phone);
		return true;
	}
	else if (strcmp(root->Name, name) > 0)
	{
		if (root->left == nullptr)
		{
			root->left = new Contact(name, phone);
			return true;
		}
		else
			return insert(name, phone, root->left);
	}
	else if (strcmp(root->Name, name) < 0)
	{
		if (root->right == nullptr)
		{
			root->right = new Contact(name, phone);
			return true;
		}
		else
			return insert(name, phone, root->right);
	}
	
	return false;
}

Contact *BSTree::displayCurrent()
{
	if (current != nullptr) {
		return current;
	}
	return 0;
}

void BSTree::outputSortList()
{
	sortList(Root);
}

void BSTree::sortList(Contact *root)
{
	if (root != nullptr)
	{
		sortList(root->left);
		cout << "Name:" << root->Name << " \tPhone: " << root->Phone << endl;
		sortList(root->right);
	}
}


void BSTree::deleteCurrent()
{
    deleteContact(current);
	current = Root;
}

void BSTree::deleteContact(Contact *toDelete)
{
	if (toDelete != nullptr)
	{
		
		if (toDelete->left == nullptr && toDelete->right == nullptr)
		{
			Contact *parent = findParent(toDelete, Root);
			if (parent->left == toDelete)
				parent->left = nullptr;
			else
				parent->right = nullptr;
            delete toDelete;
			toDelete = nullptr;
		}
		else if (toDelete->right == nullptr)
		{
			Contact *parent = findParent(toDelete, Root);
			if (parent->left == toDelete)
				parent->left = toDelete->left;
			else
				parent->right = toDelete->left;
            delete toDelete;
			toDelete = nullptr;

		}
		else if (toDelete->left == nullptr)
		{
			Contact *parent = findParent(toDelete, Root);
			if (parent->right == toDelete)
				parent->right = toDelete->right;
			else
				parent->left = toDelete->right;
            delete toDelete;
			toDelete = nullptr;
		}
		else
		{
		    strcpy(findSmallDecendent(current->right), current->Name);
            strcpy(findSmallDecendent(current->right), current->Phone);
		}
	}
}

char *BSTree::findSmallDecendent(Contact*decendent)
{
	if (decendent->left != nullptr)
		return findSmallDecendent(decendent->left);
	else
	{
		char *decendentName = decendent->Name;
		deleteContact(decendent);
		return decendentName;
	}
}

void BSTree::deleteAll(Contact *root)
{
	if (root != nullptr)
	{
		deleteAll(root->left);
		deleteAll(root->right);
		delete root;
	}
}
