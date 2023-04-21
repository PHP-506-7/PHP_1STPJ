<?php

include_once( "common/fnc_park.php" );

$arr_get=$_GET;
    
    $resert_check=todo_select_todo_detail($arr_get["list_no"]);

    if ($resert_check["list_done_flg"]==0) {
        $arr_get["list_done_flg"]=1;
        update_check_flg($arr_get);
    }
    elseif ($resert_check["list_done_flg"]==1) {
        $arr_get["list_done_flg"]=0;
        update_check_flg($arr_get);
    }

    header("Location: todo_detail.php?list_no=".$arr_get["list_no"]);
    exit();



?>