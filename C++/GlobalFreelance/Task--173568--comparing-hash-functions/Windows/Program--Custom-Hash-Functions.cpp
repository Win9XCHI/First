#include <iostream>
#include <string>
#include <cstring>
#include <fstream>
#include <map>
#include <vector>

using namespace std;

unsigned long djb2(unsigned char *str);
unsigned long sdbm(unsigned char *str);
unsigned long loselose(unsigned char *str);

//*** Your hash function ***
unsigned int MyHash(unsigned char *str)
{

	unsigned int hash = 0;

	for(; *str; str++)
	{
		hash += (unsigned char)(*str);
		hash -= (hash << 13) | (hash >> 19);
	}

	return hash;

}

void printCollisions(string name, map<unsigned long, vector<string> > & keys);

int main()
{
	const int MAX_CHARS_PER_LINE = 512;

	unsigned long hkey = 0;

	map<unsigned long, vector<string> > djb2Keys;
	map<unsigned long, vector<string> > sdbmKeys;
	map<unsigned long, vector<string> > loseloseKeys;
	map<unsigned long, vector<string> > MyHashKeys;

	ifstream fin;
	char buf[MAX_CHARS_PER_LINE];

	fin.open("dictionary.txt");

	if (!fin.good())
	{
		cerr << "Error Opening File" << endl;
		return 1;
	}

	while (!fin.eof())
	{
		fin.getline(buf, MAX_CHARS_PER_LINE);
		string word = (char *)buf;

		hkey = djb2((unsigned char *)buf);
		djb2Keys[hkey].push_back(word);

		hkey = sdbm((unsigned char *)buf);
		sdbmKeys[hkey].push_back(word);

		hkey = loselose((unsigned char *)buf);
		loseloseKeys[hkey].push_back(word);

		hkey = MyHash((unsigned char *)buf);
		MyHashKeys[hkey].push_back(word);
		//*** Test your has functions here ***

	}

	fin.close();

	printCollisions("djb2", djb2Keys);
	printCollisions("sdbm", sdbmKeys);
	printCollisions("loselose", loseloseKeys);
	printCollisions("MyHash", MyHashKeys);

	//system("pause");
	return 0;
}

// *** Hash Functions ***

// http://www.cse.yorku.ca/~oz/hash.html

// First reported by Dan Berstein in the news group comp.lang.c.
unsigned long djb2(unsigned char *str)
{
	unsigned long hash = 5381;
	int c;

	while (c = *str++)
		hash = ((hash << 5) + hash) + c; /* hash * 33 + c */

	return hash;
}

// Created for sdbm (a public-domain reimplementation of ndbm) database library.  
// It was found to do well in scrambling bits causing better distribution of the keys and fewer splits.
unsigned long sdbm(unsigned char *str)
{
	unsigned long hash = 0;
	int c;

	while (c = *str++)
		hash = c + (hash << 6) + (hash << 16) - hash;

	return hash;
}

// First appeared in K&R (1st edition).  Terrible hashing algorithm because it produces many collisions.
unsigned long loselose(unsigned char *str)
{
	unsigned long hash = 0;
	int c;

	while (c = *str++)
		hash += c;

	return hash;
}

void printCollisions(string name, map<unsigned long, vector<string> > & keys)
{
	int collisions = 0;

	cout << name << " Collision List:" << endl << endl;

	for (map<unsigned long, vector<string>>::iterator it = keys.begin();
	     it != keys.end(); ++it) {

		if (it->second.size() > 1) {
			collisions += (it->second.size() - 1);

			cout << "Hash key " << it->first << " occurs "
			     << it->second.size() << " times." << endl;

			for (vector<string>::iterator itr = it->second.begin();
			     itr != it->second.end(); ++itr) {
				cout << *itr << " ";
			}

			cout << endl << endl;
		}
	}

	cout << "Total " << name << " collisions = " << collisions << endl << endl;
}
