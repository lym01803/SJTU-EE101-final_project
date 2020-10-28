<?php
    $author_id = $_POST["author_id"];
    $author_name = $_POST["author_name"];
    $retval = array();
    $retval["name"] = ucwords($author_name);
    $retval["children"] = array();
    $link = mysqli_connect("db.lifanz.cn:3306", 'ee101_user', 'ee1012019', 'ee101');
    $result = mysqli_query($link, "SELECT g.PaperID as paperid, g.AuthorName as authorname, g.AuthorSequence as seq from ((SELECT d.PaperID , e.AuthorName , d.AuthorSequence from (SELECT b.PaperID, c.AuthorID, c.AuthorSequence from((SELECT a.PaperID from paper_author_affiliation a where a.AuthorID = '$author_id') b inner join paper_author_affiliation c on b.PaperID = c.PaperID)) d inner join authors e on d.AuthorID = e.AuthorID) g inner join paper_count h on g.PaperID = h.PaperID) order by h.refcount desc");
    $tmp_p = "";
    $paper_node = array();
    $idx = 1;
    while($row = mysqli_fetch_array($result)){
        if($tmp_p != $row["paperid"]){
            if($tmp_p != ""){
                $retval["children"][] = $paper_node;
            }
            $paper_node = array();
            $paper_node["name"] = "No.".$idx;
            $idx += 1;
            $paper_node["children"] = array();
        }
        $tmp_p = $row["paperid"];
        if($row["authorname"] != $author_name){
            $authornode = array("name" => "No.".$row["seq"]." ".ucwords($row["authorname"]), "children" => array());
            $paper_node["children"][] = $authornode;
        }
    }
    if($tmp_p != ""){
        $retval["children"][] = $paper_node;
    }
    echo json_encode($retval);
?>