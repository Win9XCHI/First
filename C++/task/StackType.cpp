#include "StackType.h"
#include <iostream>
#include <string>
using namespace std;

StackType::StackType(int s) {
	SIZE = s+1;
	NowSize = 0;
	Workspace = "";
}

StackType::~StackType() {

}

void StackType::push(char c) {
	NowSize++;
	Workspace[NowSize] = c;
}

char StackType::pop() {
	char c = Workspace[NowSize];
	NowSize--;
	return c;
}

int StackType::GetNowSize() {
	return NowSize;
}

bool StackType::isEmptyStack() {
	if (NowSize == 0) {
		flag = true;
	}
	else {
		flag = false;
	}
	return flag;
}
bool StackType::isFullStack() {
	if (NowSize == SIZE) {
		flag = true;
	}
	else {
		flag = false;
	}
	return flag;
}
