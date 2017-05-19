#include <iostream>
#include <fstream>
#include <windows.h> 
using namespace std;

int main(void) {
    HANDLE hPipe;
    char buffer[1024];
    DWORD dwWritten;
    DWORD dwRead;
	string temp;
	char name[20];
	char password[20];
	int point = 0;
	int j;
	bool flag;

    hPipe = CreateNamedPipe(TEXT("\\\\.\\pipe\\Pipe"),
                            PIPE_ACCESS_DUPLEX | PIPE_TYPE_BYTE | PIPE_READMODE_BYTE,   
                            PIPE_WAIT,
                            1,
                            1024 * 16,
                            1024 * 16,
                            NMPWAIT_USE_DEFAULT_WAIT,
                            NULL);
                            
    while (hPipe != INVALID_HANDLE_VALUE) {
        if (ConnectNamedPipe(hPipe, NULL) != FALSE)   {
        	ifstream outFile("File.txt");
            while (ReadFile(hPipe, buffer, sizeof(buffer) - 1, &dwRead, NULL) != FALSE) {
            	j = 0;
				
            	for (int i = 0; i < dwRead-1; i++) {
            			if (buffer[i] == ' ') {
            				point = i;
            				i++;
						}
            			if (point == 0) {
            				name[i] = buffer[i];
						}
						if (point != 0) {
							password[j] = buffer[i];
							j++;
						}
				}
				
            	while ( getline(outFile,temp) ) {
					point = 0;
					j = 0;
					char *name1 = new char[20];
					char *password1 = new char[20];
					
					for (int i = 0; i < temp.size(); i++) {
						if (temp[i] == ' ') {
	            				point = i;
	            				i++;
							}
            			if (point == 0) {
            				name1[i] = temp[i];
						}
						if (point != 0) {
							password1[j] = temp[i];
							j++;
						}
					}
					cout << endl << name1 << " " << name << endl << password1 << " " << password;
					if (strcmp(name, name1) == 0 && strcmp(password, password1) == 0) {
						flag = true;
						break;
					}
					else {
						flag = false;
					}
					delete []name1;
					delete []password1;
            	}
            	if (flag) {
            		WriteFile(hPipe,
			                  "Authenticated",
			                  14,   
			                  &dwWritten,
			                  NULL);
				}
				else {
					WriteFile(hPipe,
			                  "Wrong username/password",
			                  14,   
			                  &dwWritten,
			                  NULL);
				}
            }
            outFile.close();
        }
        DisconnectNamedPipe(hPipe);
    }

    return 0;
}
