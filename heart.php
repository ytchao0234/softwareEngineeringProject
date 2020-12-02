<!-- 前端 to 後端:
let cmd = {};
cmd["act"] = "heart";
cmd["account"] = "0123456";
cmd["articleId"] = 540654;

<!--
後端 to 前端
dataDB.status
dataDB.errorCode
若 status = true:
dataDB.data="success"
否則
dataDB.data = "" -->

<!--FollowHeart[ArticleID,UserID]-->

<?php
require_once 'connectDB.php'; //連線資料庫

global $input,$conn;
$sql="SELECT `UserID`,`ArticleID` FROM `FollowHeart` WHERE `UserID`='".$input['account']."' AND `ArticleID`='".$input['articleId']."'";
$result=$conn->query($sql);
if(!$result){
	die($conn->error);
}
//HEART
if($result->num_rows <= 0){	//新增HEART
	$heartsql="INSERT INTO `FollowHeart` VALUES(".$input['articleId'].", '".$input['account']."')";
	$heartresult = $conn->query($heartsql);
	if(!$heartresult){
		die($conn->error);
	}
	$rtn = array();
	$rtn["status"] = true;
	$rtn["errorCode"] = "";
	$rtn["data"] = "success to add heart";
}
	else{	//DELETE HEART
		$row=$result->fetch_row();
		$heartsql="DELETE FROM `FollowHeart` WHERE `UserID`='".$input['account']."' AND `ArticleID`='".$input['articleId']."'";
		$heartresult = $conn->query($heartsql);
		if(!$heartresult){
		die($conn->error);
		}
		$rtn = array();
		$rtn["status"] = true;
		$rtn["errorCode"] = "";
		$rtn["data"] = "success to delete heart";
	}
echo json_encode($rtn);