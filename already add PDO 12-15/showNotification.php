<?php
	/* 
	前端 to 後端:
	let cmd = {};
	cmd["act"] = "showNotice";
	cmd["account"] = "00757007";

    後端 to 前端:
    dataDB = JSON.parse(data);
	dataDB.status
	若 status = true:
		dataDB.errorCode = ""
		dataDB.data[i] //有i筆通知
		(
			dataDB.data.time]	// Time
			dataDB.data.content	// Content
		)
	否則
		dataDB.errorCode = "No notifications right now."
		dataDB.data = ""
	*/
    function doShowNotification($input){
        global $conn;
        $sql="SELECT `Times`,`Content` FROM `Notice` WHERE `UserID`=? order by `Times`DESC ";
        $arr = array($input['account']);
        $result = query($conn,$sql,$arr,"SELECT");
        $resultCount = count($result);
        if($resultCount <= 0){
            errorCode("No notifications right now.");
        }
        else{
            $arr=array();
            foreach($result as $row){
            // for($i=0;$i<$resultCount;$i++){
            //     $row = $result[$i];
                $arr[$i]=array("time"=>$row[0],"content"=>$row[1]);
            }
            $rtn = successCode($arr);
        }
        echo json_encode($rtn);
    }
?>