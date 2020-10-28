/*
class chain:
    begin(); Return the head pointer;
    end(); Return the tail pointer;
    add(); Add a node to the tail of the chain, and return head pointer;
    del(); Delete a node int the chain, and return next pointer;
*/
#ifndef CHAIN_H
#define CHAIN_H

#include <cstdlib>

//chain_node
template<typename T>
struct c_node
{
    T item;
    c_node* next;
    c_node* last;
};
//new_node
template<typename T>
c_node<T>* newNode(T item, c_node<T>* nxt = nullptr, c_node<T>* lst = nullptr);
//chain
template<typename T>
class chain
{
public:
    c_node<T>* begin() { return head; }
    c_node<T>* end() { return tail; }
    chain()
    {
        head = nullptr;
        tail = nullptr;
    }
    c_node<T>* add(T item);
    c_node<T>* del(c_node<T>* cur);
private:
    c_node<T>* head;
    c_node<T>* tail;
};
//new_node
template<typename T>
c_node<T>* newNode(T item, c_node<T>* nxt, c_node<T>* lst)
{
    c_node<T>* ptr = (c_node<T>*)malloc(sizeof(c_node<T>));
    ptr->item = item;
    ptr->next = nxt;
    ptr->last = lst;
    return ptr;
}
//add
template<typename T>
c_node<T>* chain<T>::add(T item)
{
    if(head == nullptr)
    {
        head = newNode<T>(item, nullptr, nullptr);
        tail = head;
        return head;
    }
    tail->next = newNode<T>(item, nullptr, tail);
    tail = tail->next;
    return head;
}
//delete
template<typename T>
c_node<T>* chain<T>::del(c_node<T>* cur)
{
    if(cur == nullptr)
        return head;
    if(cur == head)
    {
        head = head->next;
        free(cur);
        return head;
    }
    cur->last->next = cur->next;
    if(cur->next == nullptr)
        tail = cur->last;
    else
        cur->next->last = cur->last;
    c_node<T>* ptr = cur->next;
    free(cur);
    return ptr;
}

#endif // CHAIN_H
