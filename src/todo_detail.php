<?php

include_once( "common/fnc_park.php" );

$arr_get = $_GET;

$result_info = todo_select_todo_detail( $arr_get["list_no"] );

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
    <link rel="stylesheet" href="./css/todo_detail.css">
    <link rel="icon" href="common/img/favi.png">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img id="logo" src="./common/img/logo.png" alt="logo">
        </div>
        <!-- <form action="" method=""></form> -->
        <div class = "phase">
            <p id="text">안녕하세요.</p>
            <p id="text_1">오늘은 <?echo date("m")?>월 <?echo date("d")?>일입니다.</p>
        </div>

        <div class="line">
            <img id="line" src="./common/img/line.png" alt="line">
            <p id="line_text" ><? echo $result_info["list_title"]?></p>
        </div>

        <div class="clock">
            <img id="clock" src="./common/img/clock.png" alt="clock">
            <p id="clock_text" ><? echo $result_info["list_due_time"]?></p>
        </div>

        <div class="clip">
            <img id="clip" src="./common/img/clip.png" alt="clip">
            <p id="clip_text"><? echo $result_info["list_contents"]?></p>
        </div>

        <div class="done_but">
            <?
            if ($result_info["list_done_flg"] == 1) 
            {
            ?>
            <a href="todo_detail_check.php?list_no=<? echo $result_info["list_no"]?>"> 
                <img id="done" src="./common/img/done_button.png" alt="완료버튼">
            </a>
            <?
            }
            else
            {
            ?>
            <a href="todo_detail_check.php?list_no=<? echo $result_info["list_no"]?>"> 
                <img id="not_done"src="./common/img/circle.png" alt="미완료">
            </a>
            <?
            }
            ?>
        </div>
        
        <div class="but">
            <a id="list_but" href="todo_routine_list.php">
                <button type="button">
                    목록
                </button>
            </a>
            <a id="list_but_1" href="todo_update.php?list_no=<? echo $result_info["list_no"]?>">
                <button type="button">
                    수정
                </button>
            </a>
        </div>
    </div>
</body>
</html>