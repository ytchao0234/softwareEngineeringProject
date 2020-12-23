<?php
    /*
    前端 to 後端:
    let cmd = {};
    cmd["act"] = "verifyForgetPwd";
    cmd["token"] = "email裡的";

    後端 to 前端
    dataDB.status
    若 status = true:
        dataDB.info = "success to verify"
        dataDB.data = token
    否則
        dataDB.errorCode = "fail to verify"
        dataDB.data = ""
    */
    function doVerifyPwd($input){
        global $conn;
        if(isset($_SESSION[$input['token']])){
            $arr = $_SESSION[$input['token']];
            $token = base64_encode($arr['account']);
            $last = $arr['time'];   //過期時間
            $now = date ("Y-m-d H:i:s" , mktime(date('H')+7, date('i'), date('s'), date('m'), date('d'), date('Y')));   //現在時間
            unset($_SESSION[$input['token']]);
            if((strtotime($last) - strtotime($now)) < 900){    //沒過期
                $rtn = successCode("success to verify",$token);
            }else{
                errorCode("fail to verify");
            }
        }
        echo json_encode($rtn);
    }
?>
