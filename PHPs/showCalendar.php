<?php
	/* 
	前端 to 後端:
	let cmd = {};
    cmd["act"] = "showOnCalendar";
    cmd["type"] = "list"/"calendar";
	
    後端 to 前端:
    dataDB = JSON.parse(data);
	dataDB.status
    若 status = true:
        若有行程 {
            dataDB.data.comment.info = "Successfully show boardName list."
		    dataDB.data[i].title //第i筆標題
            dataDB.data[i].startTime //第i筆startTime
            dataDB.data[i].endTime//第i筆endTime
            dataDB.data[i].text//第i筆text
            dataDB.data[i].id//第i筆text
        }
        否則 {
            dataDB.data.comment.info = "Non't any activity.";
            dataDB.data.comment.data = "";
        }
	否則
		dataDB.errorCode = 
		dataDB.data = ""
	*/
    function doShowCalendar($input){
        global $conn;
        $sql="SELECT `Title`,`Start`,`END`,`Text`,`ID` FROM `Calendars` Where `IsValid`=? order by `Start` ASC ";
        if($input['type']=="calendar")
            $arr = array(true);
        else
            $arr = array(false);
        $result = query($conn,$sql,$arr,"SELECT");
        $resultCount = count($result);
        if($resultCount <= 0){
            $rtn = successCode("Non't any activity.", array());
        }
        else{
			$arr=array();
			for($i=0;$i<$resultCount;$i++){
                $row = $result[$i];
                $arr[$i]=array("title"=>$row[0],"startTime"=>$row[1],"endTime"=>$row[2],"text"=>$row[3],"id"=>$row[4]);
            }
            $rtn = successCode("Successfully show boardName list.",$arr);
        }
        echo json_encode($rtn);
    }
?>
