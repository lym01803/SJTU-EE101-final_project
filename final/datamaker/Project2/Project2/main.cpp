#define _CRT_SECURE_NO_DEPRECATE
#define _CRT_SECURE_NO_WARNING
#include <cstdio>
#include "chain_hash.h"
int main()
{
	myHash<int> idx;
	FILE *fin, *nodefile, *edgefile;
	fin = fopen("paper_reference.txt", "r");
	nodefile = fopen("node.txt", "w");
	edgefile = fopen("edge.txt", "w");
	char source[25], target[25];
	int sourceid, targetid;
	int id = 0;
	while(fscanf(fin, "%s%s", source, target) == 2)
	{
		if(!(sourceid = idx[source]))
		{
			idx[source] = sourceid = ++id;
			fprintf(nodefile, "%d\n", sourceid);
		}
		if(!(targetid = idx[target]))
		{
			idx[target] = targetid = ++id;
			fprintf(nodefile, "%d\n", targetid);
		}
		fprintf(edgefile, "%d,%d\n", sourceid, targetid);
	}
	fclose(fin);
	fclose(nodefile);
	fclose(edgefile);
	return 0;
}