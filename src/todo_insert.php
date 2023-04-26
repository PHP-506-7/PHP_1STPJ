<?php
define( "URL_DB", "common/db_common.php" );
include_once( URL_DB );

$http_method = $_SERVER["REQUEST_METHOD"];

if ( $http_method === "POST" )              
{
    $arr_post = $_POST;                             //if를 사용하지 않으면 HTTP 요청 방법이 "POST"인지 확인되지 않기 때문에 if 사용
    $result = todo_insert_routine_info($arr_post);
    $result_list = todo_insert_routine_list($result);
    $result_select = todo_select_detail($result_list);

    header( "Location: todo_detail.php?list_no=".$result_select["list_no"]);
    exit();
}

// $hour = array();                     //0425 del 내용 간소화
//     for ($i=0; $i < 24; $i++) 
//     {
//         if ($i<10) 
//         {
//             array_push($hour, "0".$i);
//         }
//         else
//         {
//             array_push($hour, $i);
//         }
//     }

$hour = [];
for ($i = 0; $i < 24; $i++) 
{
    $hour[] = str_pad($i, 2, "0", STR_PAD_LEFT);    //왼쪽 부터(앞에서 부터) $i에 0이 붙음(최대자리수 : 2 빈칸이면 채우는 수: 0)
}
$min = array("00", "10", "20", "30", "40", "50");


$arr_1 = todo_select_recom_routine();
// $rand_no = rand(0,count($arr_1)-1);      0424 del 내용 간소화


?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert page</title>
    <link rel="stylesheet" href="./css/todo_total.css">
    <link rel="icon" href="common/img/favi.png">
</head>
<body>
    <div class="none"></div>
        <div class="container">
            <div class="logo">
                <img id="logo" src="./common/img/logo.png" alt="logo">
            </div>

            <div class = "phase">
                <!-- <p>What do </p> 
                <p>you want to do?</p>  0425 del--> 
                <p>
                    What do 
                    <br>
                    you want to do?
                </p>
        </div>
        <div class="p_none"></div>
        <form action="todo_insert.php" method="post">
            <div class="contents">
                <div class="line">
                    <img id="line" src="./common/img/line.png" alt="line">
                    <input type="text" name="routine_title" placeholder="<? echo $arr_1["recom_title"]?>" maxlength="10" required></input>
                </div>
                
                <div class="clock">
                    <img id="clock" src="./common/img/clock.png" alt="clock">
                    <select id="hour" name="routine_due_hour" required>     <!-- 목록 상자-->
                        <? 
                            foreach ( $hour as $val ) 
                            { 
                        ?>
                                <option><? echo $val ?></option>
                        <? 
                            }
                        ?>
                    </select>
                        <span>:</span>  
                    <select id="min" name="routine_due_min" required>
                        <? 
                            foreach ( $min as $val ) 
                            { 
                        ?>
                                <option><? echo $val ?></option>
                        <? 
                            }
                        ?>
                    </select>
                </div>
                <div class="clip">
                    <img id="clip" src="./common/img/clip.png" alt="clip">
                    <input type="text" name ="routine_contents" placeholder="<? echo $arr_1["recom_contents"]?>" maxlength="33" required></input>
                </div>
                <div class="none_but">
                </div>
                <div class="but">
                    <button type="submit">
                            완료
                    </button>
                    <a id="but2" href= "todo_routine_list.php?list_no"> 
                        <button type="button">
                            취소
                        </button>
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
