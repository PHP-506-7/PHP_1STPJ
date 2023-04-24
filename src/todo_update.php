<?php
    define( "URL_DB", "common/db_common.php" );
    include_once( URL_DB );

    $http_method = $_SERVER["REQUEST_METHOD"];

    if ( $http_method === "GET" ) 
    {
        $list_no = 1;
        if ( array_key_exists( "list_no", $_GET ) )
        {
            $list_no = $_GET["list_no"];
            $result_info = select_page_routine_info( $list_no );
        }
        else
        {
            echo "잘못된 페이지입니다.";
            exit();
        }
    }
    else
    {
        $arr_post = $_POST;

        // routine_info 테이블 업데이트
        update_routine_info($arr_post);

        // routine_list 테이블 업데이트
        update_routine_list();

        header( "Location: todo_detail.php?list_no=".$arr_post["list_no"] );
        exit();
    }

    // 시간과 분 option값으로 넣는 배열
    $hour = array(); // 시간
    for ( $i=0; $i < 24; $i++ ) 
    {
        if ( $i < 10 ) {
            array_push( $hour, "0".$i );
        }
        else
        {
            array_push( $hour, $i );
        }
    }
    $min = array(  //분 
        "00", "10", "20", "30", "40", "50" 
    );

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/todo_total.css">
    <link rel="icon" href="common/img/favi.png">
    <title>수정</title>
</head>
<body>
    <!-- 취소 버튼 -->
    <a href="todo_detail.php?list_no=<? echo $list_no ?>">
        <button class="back_button">
            <img id="back_button" src="common/img/back_button.png" alt="취소">
        </button>
    </a>
    <div class="container">     <!-- 로고부터 완료/삭제 버튼 -->
        <div class="logo">
            <img id="logo" src="common/img/logo.png" alt="logo">
        </div>
        <div class="p_none">
            <p>Make it easy!</p>
        </div>
        <form action="todo_update.php" method="post">
            <div class="contents">
                <div class="line">
                    <img id="line" src="common/img/line.png" alt="line">
                    <input type="text" name="routine_title" value="<? echo $result_info["routine_title"] ?>" required></input>
                </div>
                <div class="clock">
                    <img id="clock" src="common/img/clock.png" alt="clock">
                    <select name="routine_due_hour" required>
                        <? foreach ( $hour as $val ) { 
                        if ($val == mb_substr($result_info["routine_due_time"],0,2)) { ?>
                            <option selected><? echo $val ?></option>
                            <? }
                        else { ?>
                            <option><? echo $val ?></option>
                        <? }
                        } ?>
                    </select>
                    <p>:</p>
                    <select name="routine_due_min" required>
                        <? foreach ( $min as $val ) { 
                        if ($val == mb_substr($result_info["routine_due_time"],3,2)) { ?>
                            <option selected><? echo $val ?></option>
                            <? }
                        else { ?>
                            <option><? echo $val ?></option>
                        <? }
                        } ?>
                    </select>
                </div>
                <div class="clip">
                    <img id="clip" src="common/img/clip.png" alt="clip">
                    <input type="text" name="routine_contents" value="<? echo $result_info["routine_contents"] ?>" required></input>
                </div>
                <input type="hidden" name="routine_no" value="<? echo $result_info["routine_no"] ?>" readonly></input>
                <input type="hidden" name="list_no" value="<? echo $list_no ?>" readonly></input>
                <div class="none_but"></div>
                <div class="but">     <!-- 버튼 부분 -->
                    <button type="submit">완료</button>
                    <a href="todo_delete.php?routine_no=<? echo $result_info['routine_no'] ?>"><button type="button">삭제</button></a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>