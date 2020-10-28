#define _CRT_SECURE_NO_DEPRECATE
#define _CRT_SECURE_NO_WARNING
#include <cstdio>
#include <cstring>
#include "chain_hash.h"
char strlist[255000][80];
int main()
{
	FILE *fin;
	fin = fopen("title.csv", "r");
	myHash<int> hash;
	int i = 0;
	char str[80];
	while(fscanf(fin, "%s", str) == 1)
	{
		printf("%s", str);
		if((++hash[str]) == 1)
		{
			strcmp(strlist[++i], str);
			printf("---%d---\n", i);
		}
	}
	freopen("count.json", "w", stdout);
	printf("{\n");
	for(int p = 1; p <= i; ++p)
	{
		printf("\"%s\" : %d,\n", strlist[p], hash[strlist[p]]);
	}
	fclose(fin);
	return 0;
}