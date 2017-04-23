#include <iostream>
#include <windows.h>
#include <ctime>
using namespace std;

struct thing {
	double vi;
	double wi;
	double value; 
};

struct application {
	int si;
	int fi;
	int v;
};

void Backpack(int,double);
void LectureHall(int);

int main() {
//	srand(time(NULL));
	setlocale(0,"");
	int exit(0),i(0),j(0);
	double backpack(0);
	int n(0);
	char buff[255];
	while(exit!=4) {
		system("cls");
		cout<<endl<<"1 - Задача про наплiчник";
		cout<<endl<<"2 - Задача про аудиторiї";
		//cout<<endl<<"3 - Пошук";
		cout<<endl<<"4 - Вихiд"<<endl;
		cin.getline(buff,255);
		exit = atoi(buff);
		switch(exit) {
			case 1:{
				while (n<=1 || n>=1000) {
					cout<<endl<<"Скiльки є товарiв? >";
					cin.getline(buff,255);
					n = atoi(buff);
				}
				while (backpack<=1 || backpack>=1000) {
					cout<<endl<<"Яка мiсткiсть наплечника? >";
					cin.getline(buff,255);
					backpack = atof(buff);
				}
				Backpack(n,backpack);
				n = 0;
				break;
				   }

			case 2: {
				while (n<=1 || n>=1000) {
					cout<<endl<<"Скiльки є заявок? >";
					cin.getline(buff,255);
					n = atoi(buff);
				}
				LectureHall(n);
				n = 0;
				break;
					}
				/*case 3: {
				if(flag) {
					if(FlagSort) {
						Object.SearchInt();
					}
					else {
						cout<<endl<<"Матриця не вiдсортована "<<endl;
					}
				}
				else {
					cout<<endl<<"У матрицi нема користувацьких чисел "<<endl;
				}
				break;
					}*/
		}
	}
	return 0;
}

void Backpack(int n,double backpack) {
	thing *p = new thing[n];
	int *Pack = new int[n];
	int Sum(0);
	for(int i=0;i<n;i++) {
		srand( time( NULL )+i );
		p[i].vi = rand()%1000+1;
		p[i].wi = rand()%100+1;
		p[i].value = p[i].vi/p[i].wi;
	}
	//cout<<endl<<"Товари"<<endl;
	//for(int i=0;i<n;i++) {
	//	cout<<endl<<i+1<<" товар"<<endl<<"Цiна - "<<p[i].vi<<", Вага - "<<p[i].wi;
	//	cout<<endl;
	//}
	int k(0);
		k=1;
		int i=n-1;
		while((k!=0)&&(i>1)) {
			k=0;
			for(int j = 0;j<=i-1;j++) {
				if(p[j].value <p[j+1].value) {
					thing buff = p[j];
					p[j] = p[j+1];
					p[j+1] = buff;
					k=1;
				}
			}
			i--;
		}
	cout<<endl<<"Товари"<<endl;
	for(int i=0;i<n;i++) {
		cout<<endl<<i+1<<" товар"<<endl<<"Питома цiннiсть - "<<p[i].value;
		cout<<endl<<"Цiна - "<<p[i].vi<<", Вага - "<<p[i].wi;
		cout<<endl;
	}
	i=0;
	while(backpack>p[i].wi) {
		backpack -= p[i].wi;
		Pack[i] = p[i].wi;
		Sum += p[i].vi;
		i++;
	}
	cout<<endl<<endl<<"Сумма у рюкзаку - "<<Sum<<endl<<endl<<"Вага вмiщених у наплечник товарiв"<<endl;
	for(int o=0;o<i;o++) {
		cout<<Pack[o]<<", ";
	}
	cin.get();
	delete []p;
	delete []Pack;
}

void LectureHall(int n) {
	application *p = new application[n];
	int **Pack = new int*[n];
	for(int i=0;i<n;i++) {
		Pack[i] = new int[24];
	}
	for(int i=0;i<n;i++) {
		for(int j=0;j<24;j++) {
			Pack[i][j] = 0;
		}
	}
	int Kol(0);
	for(int i=0;i<n;i++) {
		srand( time( NULL )+i );
		p[i].si = rand()%19;
		p[i].fi = p[i].si + 2;
		/*while(p[i].fi == p[i].si || p[i].fi>24) {
			p[i].fi = p[i].si + rand()%24;
		}*/
		p[i].v = -1;
		//cout<<endl<<i+1<<" заявка"<<endl<<"Початок - "<<p[i].si+1<<", Кiнець - "<<p[i].fi+1;
	}
	int k(0);
		k=1;
		int i=n-1;
		while((k!=0)&&(i>1)) {
			k=0;
			for(int j = 0;j<=i-1;j++) {
				if(p[j].si > p[j+1].si) {
					application buff = p[j];
					p[j] = p[j+1];
					p[j+1] = buff;
					k=1;
				}
			}
			i--;
		}
	for(int i=0;i<n;i++) {
		cout<<endl<<i+1<<" заявка"<<endl<<"Початок - "<<p[i].si+1<<", Кiнець - "<<p[i].fi+1;
	}
	/*cout<<endl<<"Товари"<<endl;
	for(int i=0;i<n;i++) {
		cout<<endl<<i+1<<" товар"<<endl<<"Питома цiннiсть - "<<p[i].value;
		cout<<endl<<"Цiна - "<<p[i].vi<<", Вага - "<<p[i].wi;
		cout<<endl;
	}*/
	bool flag = true;
	int bash(0);
	int NumAppl(0);
	int j(0);
	while(j<n || NumAppl<n) {
		bash=0;
		while(bash!=n) {
			for(int i=p[bash].si;i<p[bash].fi;i++) {
				if(Pack[j][i]==1 || p[bash].v!=-1) {
					flag = false;
					break;
				}
			}
			if(flag) {
				for(int i=p[bash].si;i<p[bash].fi;i++) {
					p[bash].v = j+1;
					Pack[j][i] = 1;
					NumAppl++;
				}
			}
			bash++;
			flag = true;
		}
		j++;
	}
	int max_kol = p[0].v;
	for(int i=0;i<n;i++) {
		if(max_kol<p[i].v) {
			max_kol = p[i].v;
		}
	}
	cout<<endl;
	for(int i=0;i<max_kol;i++) {
		cout<<endl<<i+1<<" аудиторiя"<<endl;
		for(int j=0;j<24;j++) {
			cout<<Pack[i][j]<<"|";
		}
		cin.get();
	}
	//cout<<endl<<endl<<"Сумма у рюкзаку - "<<Sum<<endl<<endl<<"Вага вмiщених у наплечник товарiв"<<endl;
	//for(int o=0;o<i;o++) {
	//	cout<<Pack[o]<<", ";
	//}
	cin.get();
	delete []p;
	for(int i=0;i<n;i++) {
		delete	Pack[i];
	}
	delete []Pack;
}


