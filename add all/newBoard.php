<?php
	/* 
	前端 to 後端:
	let cmd = {};
	cmd["act"] = "newBoard";
	cmd["boardName"] = "企鵝"
    cmd["rule"] = "Rule"
	後端 to 前端:
	dataDB.status
    若 status = true:
        dataDB.status = true
		dataDB.info = ""
		dataDB.data[0]	// BoardName
		dataDB.data[1]	// Rule
		dataDB.data[2]	// TopArticleID
    否則 status = false:
        dataDB.status = false
		dataDB.errorCode = "版面已新增" / "Failed to upload board ,Database exception."
		dataDB.data = ""
	*/
    function doNewBoard($input){
        global $conn;
        $sql="SELECT `boardName`, `UserID` FROM `Board` WHERE `BoardName`=?";
        $arr = array($input['boardName']);
        $result = query($conn,$sql,$arr,"SELECT");
        $resultCount = count($result);

        if($resultCount > 0){
            errorCode("Board exist.");
        }
        else{
            $sql="INSERT INTO `Board`(`BoardName`,`UserID`,`Rule`,`TopArticleID`) VALUES(?,?,?,?)";
            $arr = array($input['boardName'],"admin",$input['rule'],NULL);
            query($conn,$sql,$arr,"INSERT");

            $sql="SELECT `BoardName`,`Rule`,`TopArticleID` FROM `Board`  JOIN`Users` ON Users.UserID =Board.UserID WHERE `BoardName`=? AND Users.UserID=?";
            $arr = array($input['boardName'], "admin");
            $result = query($conn,$sql,$arr,"SELECT");
            $resultCount = count($result);
            if($resultCount <= 0){
                errorCode("Failed to upload board ,Database exception.");
            }
            else{
                $rtn = successCode("Successfully new the board.",$result[0]);
            }
        }
        echo json_encode($rtn);
    }
?>