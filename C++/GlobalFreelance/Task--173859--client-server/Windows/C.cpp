#include <iostream>
#include <windows.h> 
using namespace std;

int main(void) {
    HANDLE hPipe;
    DWORD dwWritten;
    DWORD dwRead;
    char name[20];
    char password[20];
    char buff[255];
    int j = 0;

    hPipe = CreateFile(TEXT("\\\\.\\pipe\\Pipe"), 
                       GENERIC_READ | GENERIC_WRITE, 
                       0,
                       NULL,
                       OPEN_EXISTING,
                       0,
                       NULL);
                       
    if (hPipe != INVALID_HANDLE_VALUE) {
    	cout << "Enter name" << endl;
    	cin.getline(buff,255);
    	strncpy(name,buff,19);
    	name[19] = '\0';
    	cout << "Enter password" << endl;
    	cin.getline(buff,255);
    	strncpy(password,buff,19);
    	password[19] = '\0';
    	buff[0] = '\0';
    	strcpy(buff, name);
    	buff[strlen(name)] = ' ';
    	j = strlen(name) + 1;
    	for (int i = 0; i < strlen(password); i++) {
    		buff[j] = password[i];
    		j++;
		}
    	buff[strlen(name) + strlen(password) + 1] = '\0';
        WriteFile(hPipe,
                  buff,
                  strlen(buff) + 1,   
                  &dwWritten,
                  NULL);

		while (ReadFile(hPipe, buff, sizeof(buff) - 1, &dwRead, NULL) != FALSE) {
		   	cout << endl << buff;
		   	cin.get();
		   	break;
		}

        CloseHandle(hPipe);
        DisconnectNamedPipe(hPipe);
    }

    return (0);
}
