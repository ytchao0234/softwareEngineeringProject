<?php
    require_once 'connectDB.php'; //連線資料庫 
/* 前端 to 後端:
            let cmd = {};
            cmd["act"] = "editArticle";
			cmd["articleID"] = "ArticleID"
            cmd["account"] = "AuthorID"
            cmd["blockID"] ="美食版"
            cmd["title"] = "Title"
            cmd["content"] = "Content"
            cmd["picture"] = "Image"
            cmd["hashTag"] ="HashTag"
            cmd["timer"] ="Time"
        */
		
       /* 後端 to 前端
            dataDB.status
            dataDB.errorCode
            若 status = true:
				
            否則
                dataDB.data = ""
         */
    $del="DELETE FROM `Article` WHERE `ArticleID` = $input["articleID"] AND  AuthorID = $input["authorID"]";
    $result=$conn->query($del);
        if(!$result){
            die($conn->error);
        }
    $sql="SELECT `ArticleID`','`Title`,`Content`','`Image`','`HashTag`','`Time` FROM `Article` WHERE `Title`=".$input['title']."','`Content`=".$input['content']."AND `Image`=".$input['picture']."AND `HashTag`=".$input['hashTag']."AND `Time`=".$input['timer']."";
        $result=$conn->query($sql);
        if($result->num_rows >= 0){
        $rtn = array();
        $rtn["status"] = false;
        $rtn["errorCode"] = "刪除失敗，資料庫異常";
        $rtn["data"] = "";
    }
    else{
        $rtn = array();
        $rtn["status"] = true;
        $rtn["errorCode"] = "";
    }
    echo json_encode($rtn);
?>