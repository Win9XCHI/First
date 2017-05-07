#include "StdAfx.h"
#include "StackType.h"
//#include <iostream>
#include <string>

StackType::StackType(int s) {
	SIZE = s;
	NowSize = 0;
	Workspace = "";
}

StackType::~StackType() {

}

void StackType::push(char c) {
	Workspace[NowSize++] = c;
	NowSize++;
}

char StackType::pop() {
	char c = Workspace[NowSize];
	//Workspace[NowSize] = "";
	NowSize--;
	return c;
}

int StackType::GetNowSize() {
	return NowSize;
}
