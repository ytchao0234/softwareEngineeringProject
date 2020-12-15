<?php
	/* 
	前端 to 後端:
	let cmd = {};
	cmd["act"] = "toAllNotification";
	cmd["content"] = "Content";

    後端 to 前端:
    dataDB = JSON.parse(data);
	dataDB.status
	若 status = true:
		dataDB.errorCode = ""
		dataDB.data = "Successfully send notice to everyone."
	否則
		dataDB.errorCode = "Without any user." / "Failed to send Notification to everyone,Database exception."
		dataDB.data = ""
	*/
    function doToAllNotification($input){    
        global $conn;
        $sql="SELECT `UserID` FROM `Users`";
        $result = query($conn,$sql,array(),"SELECT");
        $resultCount = count($result);
        if($resultCount <= 0){
            errorCode("Without any user.");
        }
        else{
            foreach($result as $userID){
            // for($i=0;$i<$resultCount;$i++){
            //     $userID = $result[$i];
                $sql="INSERT INTO `Notice`(`UserID`,`Content`) VALUES(?,?)";
                $arr = array($userID, $input['content']);
                query($conn,$sql,$arr,"INSERT");
        
                $sql="SELECT `UserID`,`Times`,`Content` FROM `Notice` WHERE `UserID`=? AND`Content`=?";
                $arr = array($userID, $input['content']);
                $result = query($conn,$sql,$arr,"SELECT");
                $resultCount = count($result);
                if($resultCount <= 0){
                    errorCode("Failed to send Notification to everyone,Database exception.");
                }
            }
            $rtn = successCode("Successfully send notice to everyone.");
        }
        echo json_encode($rtn);
    }
?>