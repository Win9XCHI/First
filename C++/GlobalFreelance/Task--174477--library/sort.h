#ifndef _SORT
#define _SORT

#include <iostream>

#define SHORT_SIZE_ARRAY 6
#define MEDIUM_SIZE_ARRAY 10
#define LONG_SIZE_ARRAY 17

using namespace std;

void quickSort(int arr[], int first, int last) {
	int i = first, j = last;
	int tmp, x = arr[first];  
 	
 	do {
		while (arr[i] < x) i++; 
   	while (arr[j] > x) j--;    
   	if (i < j)  {  
			tmp = arr[i]; 
			arr[i] = arr[j];
			arr[j] = tmp;
			if(arr[i] == arr[j] && i != j) {
				i++;
				j--;
			}
		}      
    	else { 
			i++;  
			j--;
		}  
 	} while (i < j);

 	if (i < last)  quickSort(arr, i, last); 
 	if (first < j) quickSort(arr, first, j); 
}

void shellSort(int arr[], int size) {
	int d = size / 2; 

	while (d >= 1)  { 
	   for (int j = 0; j <= size - d - 1; j++)
		   if (arr[j] > arr[j]) {
			  int a = arr[j]; 
			   arr[j] = arr[j+d]; 
			   arr[j+d] = a; 
			}
	   d = d / 2;
	 }

	for (int i = 1; i < size; i++)  { 
	  for (int j = 0; j <= i - 1; j++)
	  	if (arr[i] < arr[j]) {
		    int a = arr[j]; 
		   arr[j] = arr[i]; 
		   arr[i] = a; 
		 }
	}
}

void bubbleSort(int arr[], int size) {
	for (int i = 0; i < size; i++) {
    for (int j = size - 1; j > i; j--) {
      if (arr[j] < arr[j - 1]) {
       	int tmp = arr[j];
       	arr[j] = arr[j - 1];
       	arr[j - 1] = tmp;
      }
    }
	}
}

void selectionSort(int arr[], int size) {
	for (int i = 1; i < size; i++)  { 
	  for (int j = 0; j <= i - 1; j++) {
	  	if (arr[i] < arr[j]) {
				int a = arr[j]; 
				arr[j] = arr[i]; 
				arr[i] = a; 
			}
		}
	}
}

int testSort(int check) {
	int short_origin[SHORT_SIZE_ARRAY] = { 9, -3, 3, 8, 2, 1 };
	int medium_origin[MEDIUM_SIZE_ARRAY] = { 9, 3, -1, 0, 2, -9, 4, 5, 1, 7 };
	int long_origin[LONG_SIZE_ARRAY] = { 3, -1, 5, -8, 2, 9, 1, 10, 0, -2, -5, 7, 2, -3, 1, 8, 11 };
		
	cout << endl << "Before sorting:" << endl;
	cout << "Short array: ";
	for (int n = 0; n < SHORT_SIZE_ARRAY; n++)
 		printf ("%d ", short_origin[n]);
 	cout << endl;
 	cout << "Medium array: ";
	for (int n = 0; n < MEDIUM_SIZE_ARRAY; n++)
  	printf ("%d ", medium_origin[n]);
  cout << endl;
  cout << "Long array: ";
  for (int n = 0; n < LONG_SIZE_ARRAY; n++)
  	printf ("%d ", long_origin[n]);
	cout << endl << endl;

	int result = 0;
	switch (check) {
		case 1: {
			quickSort(short_origin, 0, 5);
			quickSort(medium_origin, 0, 9);
			quickSort(long_origin, 0, 16);

			cout << "After Quick sort:";
  		cout << endl << "Short array: ";
     	for (int n = 0; n < SHORT_SIZE_ARRAY; n++)
     		printf ("%d ", short_origin[n]);

  		cout << endl << "Medium array: ";
     	for (int n = 0; n < MEDIUM_SIZE_ARRAY; n++)
     		printf ("%d ", medium_origin[n]);

			cout << endl << "Long array: ";
     	for (int n = 0; n < LONG_SIZE_ARRAY; n++)
     		printf ("%d ", long_origin[n]);
     	cout << endl;
			
			result = 1;
			break;
		}

		case 2: {
			shellSort(short_origin, SHORT_SIZE_ARRAY);
			shellSort(medium_origin, MEDIUM_SIZE_ARRAY);
			shellSort(long_origin, LONG_SIZE_ARRAY);
			
			cout << "After Shell sort:";
  		cout << endl << "Short array: ";
     	for (int n = 0; n < SHORT_SIZE_ARRAY; n++)
     		printf ("%d ", short_origin[n]);

  		cout << endl << "Medium array: ";
     	for (int n = 0; n < MEDIUM_SIZE_ARRAY; n++)
     		printf ("%d ", medium_origin[n]);

			cout << endl << "Long array: ";
     	for (int n = 0; n < LONG_SIZE_ARRAY; n++)
     		printf ("%d ", long_origin[n]);
     	cout << endl;

			result = 1;
			break;
		}

		case 3: {
			bubbleSort(short_origin, SHORT_SIZE_ARRAY);
			bubbleSort(medium_origin, MEDIUM_SIZE_ARRAY);
			bubbleSort(long_origin, LONG_SIZE_ARRAY);
			
			cout << "After Bubble sort:";
  		cout << endl << "Short array: ";
     	for (int n = 0; n < SHORT_SIZE_ARRAY; n++)
     		printf ("%d ", short_origin[n]);

  		cout << endl << "Medium array: ";
     	for (int n = 0; n < MEDIUM_SIZE_ARRAY; n++)
     		printf ("%d ", medium_origin[n]);

			cout << endl << "Long array: ";
     	for (int n = 0; n < LONG_SIZE_ARRAY; n++)
     		printf ("%d ", long_origin[n]);
     	cout << endl;

			result = 1;
			break;
		}

		case 4: {
			selectionSort(short_origin, SHORT_SIZE_ARRAY);
			selectionSort(medium_origin, MEDIUM_SIZE_ARRAY);
			selectionSort(long_origin, LONG_SIZE_ARRAY);
			
			cout << "After Selection sort:";
  		cout << endl << "Short array: ";
     	for (int n = 0; n < SHORT_SIZE_ARRAY; n++)
     		printf ("%d ", short_origin[n]);

  		cout << endl << "Medium array: ";
     	for (int n = 0; n < MEDIUM_SIZE_ARRAY; n++)
     		printf ("%d ", medium_origin[n]);

			cout << endl << "Long array: ";
     	for (int n = 0; n < LONG_SIZE_ARRAY; n++)
     		printf ("%d ", long_origin[n]);
     	cout << endl;

			result = 1;
			break;
		}
	}

	return result;
}

#endif
