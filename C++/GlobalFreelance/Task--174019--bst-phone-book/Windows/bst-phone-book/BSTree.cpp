#include "stdafx.h"
#include "BSTree.h"
#include <cstring>

BSTree::BSTree()
{
	current = nullptr;
	Root = nullptr;
}

BSTree::~BSTree()
{
	deleteAll(Root);
}

bool BSTree::findNode(char *i)
{
	return find(i,Root);

}

bool BSTree::find(char *i, node *root)
{
	if (root != nullptr)
	{
		if (/*root->data == i*/ strcmp(root->Name,i) == 0)
		{
			current = root;
			return true;
		}
		if (/*i < root->data*/ strcmp(root->Name,i) > 0)
		{
			return find(i, root->left);
		}
		else
		{
			return find(i, root->right);
		}
			
	}
	return false;
}

//call with Root to search entire tree "findParent(child,Root)"
node* BSTree::findParent(node *child,node *root)
{
	if (child == root|| root==nullptr)
		return nullptr;
	else if (root->left==child || root->right==child)
	{
		return root;
	}
	else if (child->data < root->data) 
	{
		return findParent(child, root->left);
	}
	else
	{
		return findParent(child, root->right);
	}
}

bool BSTree::insertNode(int i, char *name)
{
	return insert(i, name, Root);
}

bool BSTree::insert(int i, char *name, node *root)
{
	if (root == nullptr)
	{
		Root = new node(i, name);
		return true;
	}
	else if (i < root->data)
	{
		if (root->left == nullptr)
		{
			root->left = new node(i, name);
			return true;
		}
		else
			return insert(i, name, root->left);
	}
	else if (i > root->data)
	{
		if (root->right == nullptr)
		{
			root->right = new node(i, name);
			return true;
		}
		else
			return insert(i, name, root->right);
	}
	
	return false;
}

int BSTree::displayCurrent()
{
	if(current!=nullptr) {
		return current->data;
	}
	return 0;
}

char *BSTree::displayCurrentName()
{
	if(current!=nullptr) {
		return current->Name;
	}
	return 0;
}

void BSTree::outputSortList()
{
	sortList(Root);
}

void BSTree::sortList(node *root)
{
	if (root != nullptr)
	{
		sortList(root->left);
		std::cout << root->Name << " " << root->data << std::endl;
		sortList(root->right);
	}
}


void BSTree::deleteCurrent()
{
	deleteNode(current);
	current = Root;
}

void BSTree::deleteNode(node *toDelete)
{
	if (toDelete != nullptr)
	{
		
		if (toDelete->left == nullptr && toDelete->right == nullptr)
		{
			node *parent = findParent(toDelete, Root);
			if (parent->left == toDelete)
				parent->left = nullptr;
			else
				parent->right = nullptr;
			delete toDelete;
			toDelete = nullptr;
		}
		else if (toDelete->right == nullptr)
		{
			node *parent = findParent(toDelete, Root);
			if (parent->left == toDelete)
				parent->left = toDelete->left;
			else
				parent->right = toDelete->left;
			delete toDelete;
			toDelete = nullptr;

		}
		else if (toDelete->left == nullptr)
		{
			node *parent = findParent(toDelete, Root);
			if (parent->right == toDelete)
				parent->right = toDelete->right;
			else
				parent->left = toDelete->right;
			delete toDelete;
			toDelete = nullptr;
		}
		else
		{
			current->data = findSmallDecendent(current->right);
		}

	}
}

int  BSTree::findSmallDecendent(node*decendent)
{
	if (decendent->left != nullptr)
		return findSmallDecendent(decendent->left);
	else
	{
		int i = decendent->data;
		deleteNode(decendent);
		return i;
	}
}

void BSTree::deleteAll(node *root)
{
	if (root != nullptr)
	{
		deleteAll(root->left);
		deleteAll(root->right);
		delete root;
	}
}