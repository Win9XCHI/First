#ifndef STACKTYPE_H
#define STACKTYPE_H
#pragma once
#include <string>

class StackType {
private:
	std::string Workspace;
	int SIZE;
	int NowSize;
public:
	StackType(int);
	virtual ~StackType();
	void push(char);
	char pop();
	int GetNowSize();
};

#endif
