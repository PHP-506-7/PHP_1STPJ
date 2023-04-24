<?php
    define( "URL_DB", "common/db_common.php");
    include_once( URL_DB );


    
    if ((count(routin_list_info()))==0) { //화면 방문시 리스트 목록 체크 //0424 edit 비효율 코드 변경
        if (insert_routine_list()==0) { //
            header("Location: todo_insert.php");
            exit();
        }
        // else { //0424 del 비효율 코드 
        //     // header("Location: todo_routine_list.php");
        // }
    } // 아무것도 없을 시 인포 인설트
    
    
    
    $list_info=routin_list_info(); //리스트 정보들
    $taget_count=routin_list_info_count(0); //체크 안한 개수
    $goal_count=routin_list_info_count(1); //완료한 개수
    $goal_percent_temp=$goal_count/($goal_count+$taget_count)*100; //달성률 퍼센트로 계산
    $goal_percent=round($goal_percent_temp,1); // 소수점 2번째 자리에서 반올림
    $high_hour=(date("H")+3);
    $hour=date("H")

?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="common/img/favi.png">
    <title>list</title>
    <link rel="stylesheet" href="./css/todo_total.css">
</head>
<body>
    <div class="bg">
        <div class="contents_all">
            <head>
                <div class="phrase">
                    <div>
                        <p>안녕하세요.</p>
                    </div>
                    <div class="waving_hand">
                    </div>
                    <div class="date">
                        <p>오늘은 <?echo date("m")?>월 <?echo date("d")?>일입니다.</p>
                    </div>
                </div>
                <div class="goal_status">
                    <div class="goal_title">
                        <h2>Today's Routine</h2>
                    </div>
                    <div class="goal_text">
                        <p><?echo $goal_count?>/<?echo $goal_count+$taget_count?> 완료</p>
                    </div>
                        <div class="gauge">
                            <?
                            for ($i=0; $i < $goal_count; $i++) { 
                                ?>
                            <div class="goal_gauge"></div>
                            <?
                            }
                            ?>
                            <?
                            for ($i=0; $i < $taget_count; $i++) { 
                                ?>
                            <div class="no_gauge"></div>
                            <?
                            }
                            ?>
                        </div>
                        <div class="goal_pcent">
                            <p><?echo $goal_percent."%"?></p>
                        </div>
                </div>
            </head>
            <main>
                <ul>
                    <?
                    foreach ($list_info as $value) {
                        ?>
                    <li>
                        <div class="list">
                        <?
                        if ($value["list_done_flg"]==1) {
                        ?>
                            <div class="due_time">
                                <?echo mb_substr($value["list_due_time"],0,5)?> 
                            </div>
                            <div class="list_title">
                                <a href="todo_detail.php?list_no=<?echo $value["list_no"]?>" class="limit_str" ><?echo $value["list_title"]?></a>
                            </div>
                            <a href="todo_check_update.php?list_no=<?echo $value["list_no"]?>" class="checked_status"></a>
                            <?   
                        }
                        elseif ($value["list_done_flg"]==0) { 
                            if (mb_substr($value["list_due_time"],0,2)<=$high_hour&&mb_substr($value["list_due_time"],0,2)>$hour) { //0424 update 오류 수정
                                ?>
                                <div class="due_time_high">
                                    <?echo mb_substr($value["list_due_time"],0,5)?> 
                                </div>
                                <div class="list_title_high">
                                    <a href="todo_detail.php?list_no=<?echo $value["list_no"]?>" class="limit_str"><?echo $value["list_title"]?></a>
                                </div>
                            <?
                            }
                            elseif(mb_substr($value["list_due_time"],0,2)>$high_hour){
                            ?>
                                <div class="due_time">
                                    <?echo mb_substr($value["list_due_time"],0,5)?> 
                                </div>
                                <div class="list_title">
                                    <a href="todo_detail.php?list_no=<?echo $value["list_no"]?>" class="limit_str"><?echo $value["list_title"]?></a>
                                </div>
                            <?
                            }
                            elseif (mb_substr($value["list_due_time"],0,2)<=$hour) {
                            ?>
                                <div class="due_time_over">
                                    <?echo mb_substr($value["list_due_time"],0,5)?> 
                                </div>
                                <div class="list_title_high">
                                    <a href="todo_detail.php?list_no=<?echo $value["list_no"]?>" class="limit_str"><?echo $value["list_title"]?></a>
                                </div>
                            <?
                            }
                            ?>
                            <a href="todo_check_update.php?list_no=<?echo $value["list_no"]?>" class="check_status"></a>
                        <?
                        }
                        ?>
                        </div>
                    </li>
                    <?php
                    }
                    ?>
                </ul>
            </main>
            <a href="todo_insert.php"><div class="check_butten">
            </div></a>
        </div>
    </div>
</body>
</html>