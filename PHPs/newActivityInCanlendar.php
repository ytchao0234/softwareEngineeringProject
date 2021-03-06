<?php 
	/* 
	前端 to 後端:
	let cmd = {};
	cmd["act"] = "newActivityInCanlendar";
	cmd["account"] = "00757033"; //cmd["token"]
    cmd["title"] = "聖誕節";
    cmd["startTime"] = "2020/12/25";
    cmd["endTime"] = "202012/25";
    cmd["text"] = "一起來吃聖誕大餐";

	後端 to 前端:
	dataDB = JSON.parse(data);
	dataDB.status
	若 status = true:
		dataDB.info = "Activity has been approved" / "Activity has not been approved yet." / "Successfully Apply the Board.";
		dataDB.data = 空array;
	否則
		dataDB.errorCode = "";
		dataDB.data = "";
	*/
    function doNewActivityInCanlendar($input){
        global $conn;
        // $token =$input['token'];
        // if(!isset($_SESSION[$token])){
        //     errorCode("token doesn't exist.");
        // }
        // $userInfo = $_SESSION[$token];
        // $user = $userInfo['account'];

        $user = $input['account'];
        $sql="SELECT `IsValid` FROM `Calendars`WHERE `Title`=? AND `Start`=? AND `END`=?";
        $arr = array($input['title'],$input['startTime'],$input['endTime']);
        $result = query($conn,$sql,$arr,"SELECT");
        $resultCount = count($result);
        if($resultCount > 0){
            if($result[0][0])
                $rtn = successCode("Activity has been approved",array());
            else
                $rtn = successCode("Activity has not been approved yet.",array());  
        }
		else{
			$sql="INSERT INTO `Calendars`(`UserID`,`Title`,`Start`,`END`,`Text`) VALUES(?,?,?,?,?)";
        	$arr = array($user,$input['title'],$input['startTime'],$input['endTime'],$input['text']);
            query($conn,$sql,$arr,"INSERT");
            writeRecord($user,"New activity","activity info :".$input['title']);
            $rtn = successCode("Successfully new the Activity.",array());
		}	
        echo json_encode($rtn);
    }
?>
