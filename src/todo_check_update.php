<?php
    include_once( "common/fnc_kim.php" );

    $arr_get=$_GET;
    // var_dump($arr_get);
    // var_dump($arr_get["list_no"]);

    $resert_check=list_no_info($arr_get);
    // $arr_get["list_done_flg"]=1;
    // var_dump($arr_get);
    // var_dump($resert_check);

    if ($resert_check["list_done_flg"]==0) {
        $arr_get["list_done_flg"]=1;
        update_check_flg($arr_get);
    }
    elseif ($resert_check["list_done_flg"]==1) {
        $arr_get["list_done_flg"]=0;
        update_check_flg($arr_get);
    }

    // var_dump($arr_get);

    header("Location: todo_routine_list.php");
    exit();
?>
