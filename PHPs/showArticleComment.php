<?php
    /* 
    前端 to 後端:
    let cmd = {};
    cmd["act"] = "showArticleComment";
    cmd["articleID"] = ArticleID;
    cmd["account"] = "00757003";(訪客不用傳) //cmd["token"]

    後端 to 前端:
    dataDB = JSON.parse(data);
    dataDB.status
    若 status = true:
        dataDB.info = ""
        dataDB.data[i].comment //有i筆留言
        (
            dataDB.data.comment[i].content  //第i筆留言的內文
            dataDB.data.comment[i].floor    //第i筆留言的樓層
            dataDB.data.comment[i].nickname //第i筆留言的作者暱稱
            dataDB.data.comment[i].color    //第i筆留言的作者顏色
            dataDB.data.comment[i].time     //第i筆留言的時間
        ) 
        dataDB.data.title   //文章的標題
        dataDB.data.content //文章的內容
        dataDB.data.like    //文章的總愛心數
        dataDB.data.keep    //文章的總收藏數
        dataDB.data.hasLike//是否被user按過愛心
        dataDB.data.hasKeep //是否被user收藏
        dataDB.data.time    //文章的發布時間
		dataDB.data.hashTag
        dataDB.data.authorNickname 
        dataDB.data.authorColor    
    否則
        dataDB.errorCode = "Article doesn't exit."
        dataDB.data = ""
    */
    function doShowArticleComment($input){
        global $conn;
        $articleID = $input['articleID'];
        $sql="SELECT `AuthorID`,`Title`,`Content`,`cntHeart`,`cntKeep`,`Times`,`Hashtag` FROM HomeHeart NATURAL JOIN HomeKeep WHERE `ArticleID`=?"; //文章 相關資訊
        $result = query($conn,$sql,array($articleID),"SELECT");
        $resultCount = count($result);
        if($resultCount <= 0){
            errorCode("Article doesn't exit.");
        }
        else{
            $sql="SELECT `Nickname`,`Color` FROM `Users` WHERE `UserID`=?"; //作者 相關資訊
            $resultAuthor = query($conn,$sql,array($result[0][0]),"SELECT");
            $resultCount = count($resultAuthor);
            if($resultCount <= 0){
                errorCode("AuthorID doesn't exit.");
            }
            else{
                $hashTag = json_decode($result[0][6]);
                // if(isset($input['token'])){// 非訪客
                //     if(!isset($_SESSION[$token])){
                //         errorCode("account doesn't exist.");
                //     }else{
                //         $userInfo = $_SESSION[$token];
                //         $user = $userInfo['account'];
                //     }
                // }
                if(isset($input['account'])){
                    $user=$input['account'];
                    $sql = "SELECT `UserID` FROM `FollowHeart` WHERE `ArticleID`=? AND`UserID`=?";
                    $heart = query($conn, $sql, array($articleID, $user), "SELECT");
                    $hasLike = count($heart)> 0 ? 1 : 0;
    
                    $sql = "SELECT `UserID` FROM `FollowKeep` WHERE `ArticleID`=? AND`UserID`=?";
                    $keep = query($conn, $sql, array($articleID, $user), "SELECT");
                    $hasKeep = count($keep)> 0 ? 1 : 0;
                }else{
                    $hasLike = 0 ;
                    $hasKeep = 0 ;
                }
                $arr = array("title"=>$result[0][1],"content"=>$result[0][2],"like"=>$result[0][3],"keep"=>$result[0][4],"time"=>$result[0][5],"hashTag"=>$hashTag,"authorNickName"=>$resultAuthor[0][0] ,"authorColor"=>$resultAuthor[0][1], "hasLike" => $hasLike, "hasKeep" =>$hasKeep);
                
                $sql ="SELECT `Nickname`, `Color`,`Content`,`Floor`,`Times` FROM Comments JOIN Users ON Users.UserID=Comments.AuthorID WHERE `ArticleID`=? order by Floor ASC " ; //留言 相關資訊 
                $comment = query($conn,$sql,array($articleID),"SELECT");
                $commentCount = count($comment);
                $commentArr = array();
                if($commentCount > 0){
                    for($i=0;$i<$commentCount;$i++){
                        $row = $comment[$i];
                        $commentArr[$i]=array("nickname"=>$row[0],"color"=>$row[1],"content"=>$row[2],"floor"=>$row[3],"time"=>$row[4]);
                    }
                }
                $arr['comment']=$commentArr;
                $rtn = successCode("Successfully show article and comment.",$arr);
            }
        }
        echo json_encode($rtn);
    }
?>
