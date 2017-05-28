#ifndef _SORT
#define _SORT

#include <iostream>
#include <cstdlib>
using namespace std;

void quickSort(int arr[], int first, int last) {
	int i = first, j = last; int tmp, x = arr[first];  
 	do {
		while (arr[i] < x)      i++; 
    	while (arr[j] > x)      j--;    
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
 	if (first < j) quickSort(arr, first,j); 
}

void shellSort(int arr[], int size) {
	int d=size/2; 
	
	while (d >= 1)  { 
	
	   for (int j = 0; j <= size - d - 1; j++)
		   if (arr[j] > arr[j]) {
			  int a = arr[j]; 
			   arr[j] = arr[j+d]; 
			   arr[j+d] = a; 
			}
	   d=d/2;
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
	
	  for (int j = 0; j <= i - 1; j++)
	  	if (arr[i] < arr[j]) {
		    int a = arr[j]; 
		   arr[j] = arr[i]; 
		   arr[i] = a; 
		 }
	}
}

int compare(const void * x1, const void * x2) {
  return ( *(int*)x1 - *(int*)x2 );              
}

int testSort(int check) {
	int medium[10] = {9,3,-1,0,2,-9,4,5,1,7};
	int lon_g[17] = {3,-1,5,-8,2,9,1,10,0,-2,-5,7,2,-3,1,8,11};
	int shor_t[6] = {9,-3,3,8,2,1};
	int medium2[10];
	int lon_g2[17];
	int shor_t2[6];
	int size = 10;
	
	for (int i = 0; i < size; i++) {
		if (size == 10) {
			medium2[i] = medium[i];
			if (i == 9) {
				size = 17;
				i = -1;
			}
		}
		if (size == 17) {
			lon_g2[i] = lon_g[i];
			if (i == 16) {
				size = 6;
				i = -1;
			}
		}
		if (size == 6) {
			shor_t2[i] = shor_t[i];
		}
		
	}
	
	switch (check) {
		case 1: {
			quickSort(medium2,0,9);
			qsort(medium, 10, sizeof(int), compare); 
			quickSort(lon_g2,0,16);
			qsort(lon_g, 17, sizeof(int), compare); 
			quickSort(shor_t2,0,5);
			qsort(shor_t, 6, sizeof(int), compare); 
			size = 10;
			
			for (int i = 0; i < size; i++) {
				if (size == 10) {
					if (medium2[i] != medium[i]) {
						return 0;
					}
					if (i == 9) {
						size = 17;
						i = -1;
					}
				}
				if (size == 17) {
					if (lon_g2[i] != lon_g[i]) {
						return 0;
					}
					if (i == 16) {
						size = 6;
						i = -1;
					}
				}
				if (size == 6) {
					if (shor_t2[i] != shor_t[i]) {
						return 0;
					}
				}	
			}
			return 1;
			break;
		}
		case 2: {
			shellSort(medium2,10);
			qsort(medium, 10, sizeof(int), compare); 
			shellSort(lon_g2,17);
			qsort(lon_g, 17, sizeof(int), compare); 
			shellSort(shor_t2,6);
			qsort(shor_t, 6, sizeof(int), compare); 
			size = 10;
			
			for (int i = 0; i < size; i++) {
				if (size == 10) {
					if (medium2[i] != medium[i]) {
						return 0;
					}
					if (i == 9) {
						size = 17;
						i = -1;
					}
				}
				if (size == 17) {
					if (lon_g2[i] != lon_g[i]) {
						return 0;
					}
					if (i == 16) {
						size = 6;
						i = -1;
					}
				}
				if (size == 6) {
					if (shor_t2[i] != shor_t[i]) {
						return 0;
					}
				}	
			}
			return 1;
			break;
		}
		case 3: {
			bubbleSort(medium2,10);
			qsort(medium, 10, sizeof(int), compare); 
			bubbleSort(lon_g2,17);
			qsort(lon_g, 17, sizeof(int), compare); 
			bubbleSort(shor_t2,6);
			qsort(shor_t, 6, sizeof(int), compare); 
			size = 10;
			
			for (int i = 0; i < size; i++) {
				if (size == 10) {
					if (medium2[i] != medium[i]) {
						return 0;
					}
					if (i == 9) {
						size = 17;
						i = -1;
					}
				}
				if (size == 17) {
					if (lon_g2[i] != lon_g[i]) {
						return 0;
					}
					if (i == 16) {
						size = 6;
						i = -1;
					}
				}
				if (size == 6) {
					if (shor_t2[i] != shor_t[i]) {
						return 0;
					}
				}	
			}
			return 1;
			break;
		}
		case 4: {
			selectionSort(medium2,10);
			qsort(medium, 10, sizeof(int), compare); 
			selectionSort(lon_g2,17);
			qsort(lon_g, 17, sizeof(int), compare); 
			selectionSort(shor_t2,6);
			qsort(shor_t, 6, sizeof(int), compare); 
			size = 10;
			
			for (int i = 0; i < size; i++) {
				if (size == 10) {
					if (medium2[i] != medium[i]) {
						return 0;
					}
					if (i == 9) {
						size = 17;
						i = -1;
					}
				}
				if (size == 17) {
					if (lon_g2[i] != lon_g[i]) {
						return 0;
					}
					if (i == 16) {
						size = 6;
						i = -1;
					}
				}
				if (size == 6) {
					if (shor_t2[i] != shor_t[i]) {
						return 0;
					}
				}	
			}
			return 1;
			break;
		}
	}

	return 0;
}

#endif
