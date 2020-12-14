<?php
// 前端 to 後端:
// let cmd = {};
// cmd["act"] = "verify";
// cmd["token"] = "userId-Time";(email裡的)

// 後端 to 前端
// dataDB.status
// dataDB.errorCode
// 若 status = true:
// dataDB.data="success to verify"
// 否則
// dataDB.data = "fail to verify"

function doVerifyPwd($input){
    global $conn;
    // 解密，
    // 到session拿token

    if(isset($_SESSION[$input['token']])){
        $arr = $_SESSION[$input['token']];
        $last = $arr['time'];   //過期時間
        $now = date ("Y-m-d H:i:s" , mktime(date('H')+7, date('i'), date('s'), date('m'), date('d'), date('Y')));   //現在時間
        if((strtotime($last) - strtotime($now)) < 900){    //沒過期
            $rtn = successCode("success to verify");
        }else{
            errorCode("fail to verify");
        }
        unset($_SESSION[$input['token']]);
    }
    echo json_encode($rtn);
}

?>