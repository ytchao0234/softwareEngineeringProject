<?php
/* 前端 to 後端:
            let cmd = {};
            cmd["act"] = "showModerator";
        */
       /*前端 to 後端:
        let cmd = {};
        cmd["act"] = "editMode";
        cmd["boardID"] = "BoardID"
        cmd["boardName"] = "BoardName"
        cmd["userID"] = "UserID"
        cmd["rule"] = "Rule"
        cmd["topArticleID"] = "TopArticleID"
    */ 
    /* 後端 to 前端
            dataDB.state
            dataDB.errorCode
            若 state = true:
                dataDB.data[i] //有i筆文章
                (
                    dataDB.data[i].UserID 
                    dataDB.data[i].UserColor 
                    dataDB.data[i].BoardName 
                ) 
            否則
                
         */
    function doShowModerator($input){
        global $conn;
        $sql ="SELECT `UserID`, `Color`, `BoardName` FROM `Board` NATURAL JOIN `Users`  WHERE `UserID` not in ('admin') order by `UserID` ASC " ;
        $result=$conn->query($sql);
        if(!$result){
            die($conn->error);
        }
        if($result->num_rows <= 0){
            $rtn = array();
            $rtn["status"] = false;
            $rtn["errorCode"] = "沒有";
            $rtn["data"] = "";
        }
        else{
            $arr=array();
            for($i=0;$i<$result->num_rows;$i++){
                $row=$result->fetch_row();
                $log=array("UserID"=>"$row[0]","UserColor"=>"$row[1]","BoardName"=>"$row[2]");
                $arr[$i]=$log;
            }
            $rtn = array();
            $rtn["status"] = true;
            $rtn["errorCode"] = "";
            $rtn["data"] =$arr;
        }
        echo json_encode($rtn);
    }

?>