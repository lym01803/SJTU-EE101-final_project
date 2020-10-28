import pymysql as sql
import re
connect = sql.connect(
    host = 'db.lifanz.cn',
    port = 3306,
    user = 'ee101_user',
    passwd = 'ee1012019',
    db = 'ee101',
    charset = 'utf8')

def work(str):
    cur = connect.cursor()
    cur.execute('SELECT d.name1 as name1, authors.AuthorName as name2, paperid from (SELECT c.tar, authors.AuthorName as name1, c.paperid as paperid from (SELECT a.AuthorID as src, b.AuthorID as tar, a.PaperID as paperid from (SELECT PaperID, AuthorID from paper_author_affiliation where AffiliationID = \'%s\') a inner join (SELECT PaperID, AuthorID from paper_author_affiliation where AffiliationID = \'%s\') b on a.PaperID = b.PaperID and NOT a.AuthorID = b.AuthorID) c inner join authors on c.src = authors.AuthorID) d inner join authors on d.tar = authors.AuthorID'%(str,str))
    res = cur.fetchall()
    #print(res)
    author = dict()
    acnt = 0
    paper = dict()
    pcnt = 0
    edge = list()
    for line in res:
        a = line[0]
        b = line[1]
        c = line[2]
        if a not in author:
            acnt += 1
            author[a] = acnt
        if b not in author:
            acnt += 1
            author[b] = acnt
        if c not in paper:
            pcnt += 1
            paper[c] = pcnt
        edge.append((author[a], paper[c]))
        edge.append((author[b], paper[c]))
    with open("./%s.json"%str, "w",encoding='utf-8') as fout:
        fout.write("{\nnodes:[\n")
        for key in author:
            fout.write("{name:\"%s\",value:\"2\",category:0,id:%d},\n"%(key, author[key]))
        for key in paper:
            fout.write("{name:\"%s\",value:\"3\",category:1,id:%d},\n"%(key, paper[key] + acnt))
        fout.write("],\nlinks:[\n")
        for item in edge:
            fout.write("{source:%d, target:%d},\n"%(item[0],item[1]+acnt))
        fout.write("],\ncategories:[{name:\"author\"},{name:\"paper\"}]\n}")

step = 0
with open("affiliations.txt","r") as filecontent:
    while(1):
        p = filecontent.readline()
        if(len(p) <= 1):
            break
        work(p.split('\t')[0])
        step += 1
        print(step)