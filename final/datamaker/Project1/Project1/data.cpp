#define _CRT_SECURE_NO_WARNINGS
#include "chain_hash.h"
#include <iostream>
#include <vector>
#include <cstring>
using namespace std;
char conf[][10] = {"46A05BB0",
"47C39427",
"45083D2F",
"43001016",
"45701BF3",
"465F7C62",
"436976F3",
"43319DD4",
"46DAB993",
"47167ADC",
"45F914AD",
"43FD776C",	
"43ABF249"};
char Y[][10]={"1996","1997","1998","1999","2000","2001","2002","2003","2004","2005","2006","2007","2008","2009","2010","2011","2012","2013","2014","2015"};
int cal1(char s[])
{
	for(int i = 0; i < 13; ++i)
	{
		if(strcmp(s, conf[i]) == 0)
		{
			return i;
		}
	}
	return -1;
}
int cal2(char s[])
{
	for(int i = 0; i < 20; ++i)
	{
		if(strcmp(s, Y[i]) == 0)
		{
			return i;
		}
	}
	return -1;
}
int main()
{
	myHash<int> conferenceid;
	myHash<int> year; 
	FILE *fin = fopen("papers.txt", "r");
	char buf[2500];
while(fgets(buf, 2480, fin) > 0)
{
	char a[50], b[50];
	a[0] = b[0] = '\0';
	//fgets(buf, 2480, fin);
	int buflen = strlen(buf) - 1;
	while(buf[buflen] == ' ' || buf[buflen] == '\t' || buf[buflen] == '\n') --buflen;
	int la = 0, lb = 0;
	while(buf[buflen] != ' ' && buf[buflen] != '\t')
	{
		b[lb++] = buf[buflen--];
	}
	b[lb] = '\0';
	while(buf[buflen] == ' ' || buf[buflen] == '\t') --buflen;
	while(buf[buflen] != ' ' && buf[buflen] != '\t')
	{
		a[la++] = buf[buflen--];
	}
	a[la] = '\0';
	for(int i = 0; i < la - i - 1; ++i)
	{
		a[i] ^= a[la-i-1] ^= a[i] ^= a[la-i-1];
	}
	for(int i = 0; i < lb - i - 1; ++i)
	{
		b[i] ^= b[lb-i-1] ^= b[i] ^= b[lb-i-1];
	}
	char paperid[40];
	sscanf(buf, "%s", paperid);
	conferenceid[paperid] = cal1(b);
	year[paperid] = cal2(a);
}
	fclose(fin);
	freopen("affiliations.txt","r",stdin);
	int Cnt = 0;
	int CNT[30][30];
while(fgets(buf, 1024, stdin) > 0)
{
	freopen("CON","w",stdout);
	printf("---%d---\n", ++Cnt);
	if(Cnt < 3000)
	{
		continue;
	}
	char affiliationid[100];
	sscanf(buf, "%s", affiliationid);
	memset(buf, 0, sizeof(buf));
	fin = fopen("paper_author_affiliation.txt", "r");
	memset(CNT,0,sizeof(CNT));
	int cnt = 0;
	while(fgets(buf, 1024, fin) > 0)
	{
		//printf("----%d----\n", ++cnt);
		char a[100], b[100], c[100], d[100];
		sscanf(buf,"%s%s%s%s",a,b,c,d);
		if(strcmp(c, affiliationid) == 0)
		{
			//printf("hhh\n");
			int k1 = conferenceid[a], k2 = year[a];
			if(k1 >= 0 && k2 >= 0)
			{
				++CNT[k1][k2];
			}
		}
	}
	char filename[100];
	sprintf(filename, "%s.json", affiliationid);
	freopen(filename,"w",stdout);
	printf("[\n");
	for(int i = 0; i < 13; ++i)
	{
		for(int j = 0; j < 20; ++j)
		{
			if(i == 12 && j == 19)
			{
				printf("[%d,%d,%d]\n",i,j,CNT[i][j]);
			}
			else
			{
				printf("[%d,%d,%d],\n",i,j,CNT[i][j]);
			}
		}
	}
	printf("]");
	fclose(stdout);
}
	fclose(stdin);
	fclose(fin);
	return 0;
}
