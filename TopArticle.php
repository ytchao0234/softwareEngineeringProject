<?php
    require_once 'noSecurity.php'; //連線資料庫 

        /* 前端 to 後端:
            let cmd = {};
            cmd["act"] = "TopArticle";
			cmd["articleID"] = "aaad94i30jdsfg3435"
        */
		
        /* 後端 to 前端
            dataDB.status
            dataDB.errorCode
            若 status = true:
                dataDB.data[0]	// BoardID
				dataDB.data[1]	// BoardName
				dataDB.data[2]	// UserID
				dataDB.data[3]	// Rule
				dataDB.data[4]	// TopArticleID
				dataDB.data[5]	// ArticleID
            否則
                dataDB.data = ""
         */
        global $input,$conn;
        $sql="SELECT `TopArticleID`, `UserID` FROM `Board` NATURAL JOIN  Article WHERE `TopArticleID`='".$input['articleID']."'";
        $result=$conn->query($sql);
        if(!$result){
            die($conn->error);
        }

        if($result->num_rows > 0){
            $rtn = array();
            $rtn["status"] = false;
            $rtn["errorCode"] = "版面已新增";
            $rtn["data"] = "";
        }
        else{
            $new="INSERT INTO  `Board`(`BoardID`,`BoardName`,`UserID`,`Rule`,`TopArticleID`) VALUES('".$input['BoardID']."','".$input['BoardName']."'.'".$input['UserID']."'.'".$input['Rule']."'.'".$input['TopArticleID']."')";
            $resultNEW=$conn->query($new);
            if(!$resultNEW){
                die($conn->error);
            }
            $sql="SELECT `BoardID`,`BoardName`,`UserID`,`Rule`,`TopArticleID` FROM `Board` WHERE `BoardName`='".$input['boardName']."' AND `UserID`='".$input['userID']."'";
            $result=$conn->query($sql);
            if(!$result){
                die($conn->error);
            }
            if($result->num_rows <= 0){
                $rtn = array();
                $rtn["status"] = false;
                $rtn["errorCode"] = "註冊失敗，資料庫異常";
                $rtn["data"] = "";
            }
            else{
                $row=$result->fetch_row();
                $rtn = array();
                $rtn["status"] = true;
                $rtn["errorCode"] = "";
                $rtn["data"] = $new[0];
            }
        }
        echo json_encode($rtn);
?>