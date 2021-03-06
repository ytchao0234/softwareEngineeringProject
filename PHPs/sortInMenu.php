<?php
    /* 
    前端 to 後端:
    let cmd = {};
    cmd["act"] = "sortInMenu";
    cmd["account"] = "00757033"; //cmd["token"] (若是訪客則不用)
    cmd["sort"] = "time/hot/collect/comment";

    後端 to 前端:
    dataDB = JSON.parse(data);
    dataDB.status
    若 dataDB.status = true:
        dataDB.info = "Without any article now. / Successfully sort in home.";
        dataDB.data[i] //有i筆文章
        (
            dataDB.data[i].title //第i筆文章的標題
            dataDB.data[i].blockName //第i筆文章的所屬看板
            dataDB.data[i].articleID
            dataDB.data[i].like //第i筆文章的總愛心數
            dataDB.data[i].keep//第i筆文章的總收藏數
            dataDB.data[i].time //第i筆文章的時間
            dataDB.data[i].hasLike//是否按過愛心
            dataDB.data[i].hasKeep //是否收藏
        )

    否則
        dataDB.errorCode = "Failed to sort."
        dataDB.data = ""
    */ 
    function doSortMenu($input){
        global $conn;
        if ($input['sort'] == "time" || $input['sort'] == "hot" || $input['sort'] == "collect" || $input['sort'] == "comment" ) {
            if ($input['sort'] == "time") {
                $sql="SELECT `Title`,`BoardName`,`ArticleID`, `cntHeart` ,`cntKeep` FROM `HomeHeart` NATURAL JOIN `HomeKeep` ORDER BY `Times` DESC";
            } else if ($input['sort'] == "hot"){
                $sql="SELECT `Title`,`BoardName`,`ArticleID`, `cntHeart` ,`cntKeep` FROM `HomeHeart` NATURAL JOIN `HomeKeep` ORDER BY `cntHeart` DESC";
			} else if ($input['sort'] == "collect"){
                $sql="SELECT `Title`,`BoardName`,`ArticleID`, `cntHeart` ,`cntKeep` FROM `HomeHeart` NATURAL JOIN `HomeKeep` ORDER BY `cntKeep` DESC";
            } else {
				$sql = "SELECT `Title`,`BoardName`,`ArticleID`, `cntHeart` ,`cntKeep` FROM `HomeHeart` NATURAL JOIN `HomeKeep` LEFT JOIN `HomeComment` USING (ArticleID)  ORDER BY `cntComment` DESC";
			}
            $result = query($conn,$sql,array(),"SELECT");
            $resultCount = count($result);
            if ($resultCount <= 0) {
                $rtn = successCode("Without any article now.", array());
            } else {
                $articleList = array();
                for($i=0;$i<$resultCount;$i++){
                    $row = $result[$i];
                    $articleID = $row['ArticleID'];
                    // if(isset($input['token'])){
                    //     $token =$input['token'];
                    //     if(!isset($_SESSION[$token])){
                    //         errorCode("token doesn't exist.");
                    //     }
                    //     $userInfo = $_SESSION[$token];
                    //     $user = $userInfo['account'];
                    // } 
                    if(isset($input['account'])){
                        $user = $input['account'];

                        $sql = "SELECT EXISTS(SELECT 1 FROM `FollowHeart` WHERE `ArticleID`=? AND`UserID`=? LIMIT 1)";
                        $heart = query($conn, $sql, array($articleID, $user), "SELECT");
                        $hasLike = $heart[0][0];

                        $sql = "SELECT EXISTS(SELECT 1 FROM `FollowKeep` WHERE `ArticleID`=? AND`UserID`=? LIMIT 1)";
                        $keep = query($conn, $sql, array($articleID, $user), "SELECT");
                        $hasKeep = $keep[0][0];
                    } 
                    else{
                        $hasLike = 0 ;
                        $hasKeep = 0 ;
                    }
                    $articleList[$i] = array("title" => $row[0], "boardName" => $row[1], "articleID" => $articleID , "like" => $row[3], "keep" => $row[4], "hasLike" => $hasLike, "hasKeep" =>$hasKeep);
                }
                $rtn = successCode("Successfully sort in home.",$articleList);
            }
        }else{
            errorCode("Failed to sort.");
        }
        echo json_encode($rtn);
    }
?>