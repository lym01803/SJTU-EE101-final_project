/*
class myHash<V>:
    myHash(maxsize); maxsize is the maximum number of different first keys;
    add(char*, V);
    del(char*);
    in(char* str); Return whether str is in Hash table;
    query(char* str);
example:
    myHash<int> Table = myHash<int>();
    Table.add("apple", 1);
*/
#ifndef CHAIN_HASH_H
#define CHAIN_HASH_H

#include "chain.h"
#include <cstdlib>
//h_node<V>
template<typename V>
struct h_node
{
    unsigned int key;
    V value;
    h_node(unsigned int k, V v) { key = k; value = v; }
};

const int hashSeed[2] = {129, 131};
unsigned int HASH(char* str, int type, unsigned int mod = 1000000);
//myHash<V>
template<typename V>
class myHash
{
private:
    chain< h_node<V> >* first;
    int maxsize;
public:
    myHash(int _max = 1000000)
    {
        maxsize = _max;
        first = (chain< h_node<V> >*)malloc(sizeof(chain< h_node<V> >) * _max);
        for(int i = 0; i < _max; ++i)
            first[i] = chain< h_node<V> >();
    }
    void add(char* str, V val);
    void del(char* str);
    bool in(char* str);
    V query(char* str);
    V& operator [](char* str);
};

#define chain_runover for(auto cur = first[fi].begin(); cur != nullptr; cur = cur->next)
#define cal_hash unsigned int fi = HASH(str, 0, maxsize), se = HASH(str, 1, 0xfffffff);
//cal_HASH
unsigned int HASH(char* str, int type, unsigned int mod)
{
    unsigned int res = 0;
    for(char* ptr = str; (*ptr) != '\0'; ++ptr)
        res = res * hashSeed[type] + (*ptr);
    return (mod < 0xfffffff) ? (res % mod) : res;
}
template<typename V>
V& myHash<V>::operator [](char* str)
{
    int notin = 1;
    cal_hash
    chain_runover
    {
        if(cur->item.key == se)
        {
            notin = 0;
            return cur->item.value;
        }
    }
    if(notin)
    {
		//printf("fuck\n");
        first[fi].add(h_node<V>(se, V(0)));
        return first[fi].end()->item.value;
    }
}
//add
template<typename V>
void myHash<V>::add(char* str, V val)
{
    cal_hash
    int notin = 1;
    chain_runover
    {
        if(cur->item.key == se)
        {
            cur->item.value = val;
            notin = 0;
            break;
        }
    }
    if(notin)
        first[fi].add(h_node<V>(se, val));
    return ;
}
//delete
template<typename V>
void myHash<V>::del(char* str)
{
    cal_hash
    chain_runover
    {
        if(cur->item.key == se)
        {
            first[fi].del(cur);
            break;
        }
    }
    return ;
}
//is_in
template<typename V>
bool myHash<V>::in(char* str)
{
    cal_hash
    chain_runover
        if(cur->item.key == se)
            return 1;
    return 0;
}
//query
template<typename V>
V myHash<V>::query(char* str)
{
    cal_hash
    chain_runover
        if(cur->item.key == se)
            return cur->item.value;
    return V(0);
}

#endif // CHAIN_HASH_H
