<?php
include_once( "db_common.php" );

/*---------------------------------------------
함수명 : todo_insert_recom_routine
기능   : 게시글 작성
파라미터 : Arr      &$param_arr
리턴값  :  int/array     $result_cnt/ERRMSG
사용여부 : o
-----------------------------------------------*/

function todo_insert_recom_routine( &$param_arr )
{
    $sql =
        " INSERT INTO "
        ." recom_routine "
        ." ( "
        ." recom_title "
        ." ,recom_contents "
        ." ) "
        ." VALUES "
        ." ( "
        ." :recom_title "
        ." ,:recom_contents"
        ." ) "
        ;

    $arr_prepare =
        array(
            ":recom_title" => $param_arr["recom_title"]
            ,":recom_contents" => $param_arr["recom_contents"]
        );

    $conn = null;
    
    try 
    {
        db_conn( $conn );
        $conn->beginTransaction();
        $stmt = $conn -> prepare( $sql ); 
        $stmt -> execute( $arr_prepare ); 
        $result_cnt = $stmt->rowCount();
        $conn->commit();
    } 
    catch ( Exception $e) 
    {
        $conn->rollBack();
        return $e->getMessage(); 
    }
    finally 
    {
        $conn = null;
    }

    return $result_cnt;

}


/*---------------------------------------------
함수명 : todo_select_detail
기능   : 게시글 정보
파라미터 : int      &$param_no
리턴값  :  int/array     $result/ERRMSG
사용여부 : o
-----------------------------------------------*/

function todo_select_detail( &$param_no )
{
    $sql =
        " SELECT "
        ."  list_no "
        ."  ,routine_no "
        ."  ,list_title "
        ."  ,list_contents "
        ."  ,list_due_time "
        ."  ,list_done_flg "
        ."  ,list_now_date "
        ." FROM "
        ."  routine_list "
        ." WHERE "
        ."  list_no = :list_no "
        ;

    $arr_prepare =
        array
        (
            ":list_no"  =>  $param_no
        );

    $conn = null;

    try 
    {
        db_conn( $conn );
        $stmt = $conn -> prepare( $sql );
        $stmt -> execute( $arr_prepare );
        $result = $stmt->fetchAll();
    } 
    catch ( Exception $e) 
    {
        return $e->getMessage();
    }
    finally 
    {
        $conn = null;
    }

    return $result[0];

}

/*---------------------------------------------
함수명      : select_page_routine_info
기능        : 해당 페이지 루틴 정보를 받아오는 함수
파라미터    : INT          &$param_no
리턴값      : Array        $result
작성자      : 최아란
-----------------------------------------------*/
function select_page_routine_info( &$param_no )
{
    $sql =
        " SELECT "
        ."  routine_no "
        ."  ,routine_title "
        ."  ,routine_contents "
        ."  ,routine_due_time "
        ." FROM routine_info "
        ." WHERE routine_no = :routine_no "
        ;
    
    $arr = todo_select_detail( $param_no );
    
    $arr_prepare =
        array(
            ":routine_no" => $arr["routine_no"]
        );

    $conn = null;
    try 
    {
        db_conn( $conn );
        $stmt = $conn->prepare( $sql );
        $stmt->execute( $arr_prepare );
        $result = $stmt->fetchAll();
    } 
    catch ( Exception $e ) 
    {
        return $e->getMessage();
    }
    finally
    {
        $conn = null;
    }
    
    return $result[0];
}

/*---------------------------------------------
함수명      : update_routine_info
기능        : routine_no에 따라 입력한 정보 업데이트
파라미터    : Array        &$param_arr
리턴값      : INT/STRING   $result_cnt/ERRMSG
작성자      : 최아란
-----------------------------------------------*/
function update_routine_info(&$param_arr)
{
    $sql =
    " UPDATE routine_info "
    ." SET "
    ."  routine_title = :routine_title "
    ."  ,routine_contents = :routine_contents "
    ."  ,routine_due_time = :routine_due_time "
    ." WHERE routine_no = :routine_no "
    ;

    $arr_prepare =
        array(
            ":routine_title"     => $param_arr["routine_title"]
            ,":routine_contents" => $param_arr["routine_contents"]
            ,":routine_due_time" => $param_arr["routine_due_hour"].$param_arr["routine_due_min"]."00"
            ,":routine_no"       => $param_arr["routine_no"]
        );

    $conn = null;
    try 
    {
        db_conn( $conn );
        $conn->beginTransaction();
        $stmt = $conn->prepare( $sql );
        $stmt->execute( $arr_prepare );
        $result_cnt = $stmt->rowCount();
        $conn->commit();
    } 
    catch ( Exception $e ) 
    {
        $conn->rollBack();
        return $e->getMessage();
    }
    finally
    {
        $conn = null;
    }
    
    return $result_cnt;
}

/*---------------------------------------------
함수명      : update_routine_list
기능        : routine_list 정보를 당일
                routine_info 정보로 최신화
파라미터    : 없음
리턴값      : INT/STRING   $result_cnt/ERRMSG
작성자      : 최아란
-----------------------------------------------*/
function update_routine_list()
{
    $sql =
    " UPDATE routine_list li "
    ."  INNER JOIN routine_info info "
    ."  ON li.routine_no = info.routine_no "
    ." SET "
    ."  li.list_title = info.routine_title "
    ."  ,li.list_contents = info.routine_contents "
    ."  ,li.list_due_time = info.routine_due_time "
    ." WHERE DATE(li.list_now_date) = DATE(NOW()) "
    ;

    $arr_prepare = array();

    $conn = null;
    try 
    {
        db_conn( $conn );
        $conn->beginTransaction();
        $stmt = $conn->prepare( $sql );
        $stmt->execute( $arr_prepare );
        $result_cnt = $stmt->rowCount();
        $conn->commit();
    } 
    catch ( Exception $e ) 
    {
        $conn->rollBack();
        return $e->getMessage();
    }
    finally
    {
        $conn = null;
    }
    
    return $result_cnt;
}

/*---------------------------------------------
함수명      : update_routine_del_flg
기능        : 루틴 삭제 플래그 설정
파라미터    : Array        &$param_arr    
리턴값      : INT/STRING   $result_cnt/ERRMSG
작성자      : 최아란
-----------------------------------------------*/
function update_routine_del_flg( &$param_arr )
{
    $sql = 
        " UPDATE routine_info "
        ." SET "
        ."  routine_del_flg = '1' "
        ." WHERE routine_no = :routine_no "
        ;
    
    $arr_prepare = 
        array(
            ":routine_no" => $param_arr["routine_no"]
        );
    
    $conn = null;
    try 
    {
        db_conn( $conn );
        $conn->beginTransaction();
        $stmt = $conn->prepare( $sql );
        $stmt->execute( $arr_prepare );
        $result_cnt = $stmt->rowCount();
        $conn->commit();
    } 
    catch ( Exception $e ) 
    {
        $conn->rollBack();
        return $e->getMessage();
    }
    finally
    {
        $conn = null;
    }
    
    return $result_cnt;
}

/*---------------------------------------------
함수명      : delete_list_info
기능        : routine_no가 일치하는 레코드를
                list에서 모두 삭제
파라미터    : Array        &$param_arr    
리턴값      : INT/STRING   $result_cnt/ERRMSG
작성자      : 최아란
-----------------------------------------------*/
function delete_list_info(&$param_arr)
{
    $sql = 
        " DELETE FROM routine_list "
        ." WHERE routine_no = :routine_no "
        ;
    
    $arr_prepare = 
        array(
            ":routine_no" => $param_arr["routine_no"]
        );
    
    $conn = null;
    try 
    {
        db_conn( $conn );
        $conn->beginTransaction();
        $stmt = $conn->prepare( $sql );
        $stmt->execute( $arr_prepare );
        $result_cnt = $stmt->rowCount();
        $conn->commit();
    } 
    catch ( Exception $e ) 
    {
        $conn->rollBack();
        return $e->getMessage();
    }
    finally
    {
        $conn = null;
    }
    
    return $result_cnt;
}


/*---------------------------------------------
함수명 : todo_update_flg
기능   : 게시글 정보
파라미터 : int      &$param_arr
리턴값  :  int/array     $result_cnt/ERRMSG
-----------------------------------------------*/
function todo_update_flg( &$param_arr )
{
    $sql =
        " UPDATE "
        ." routine_info "
        ." SET "
        ." routine_del_flg = :routine_del_flg"
        ." WHERE "
        ." routine_no = :routine_no "
        ;
    
    $arr_prepare =
        array (
            " routine_no " => $param_arr["routine_no"]
            ," routine_del_flg " => $param_arr["routine_del_flg"]
        );

    $conn = null;

    try 
    {
        db_conn( $conn );
        $conn->beginTransaction();
        $stmt = $conn->prepare( $sql );
        $stmt->execute( $arr_prepare );
        $result_cnt = $stmt->rowCount(); 
        $conn->commit();
    } 
    catch ( Exception $e) 
    {
        $conn->rollBack();
        return $e->getMessage();
    }
    finally 
    {
        $conn = null;
    }

    return $result_cnt;

}

/*---------------------------------------------
함수명 : select_routine_info_cnt
기능   : 
파라미터 : int      &$param_arr
리턴값  :  int/array     $result/ERRMSG
-----------------------------------------------*/
function select_routine_info_cnt()
{
    $sql = 
        " SELECT "
        ."      COUNT(*) cnt"
        ." FROM "
        ."      routine_info "
        ." WHERE "
        ."      routine_del_flg = '0' "
        ;

    $arr_prepare = array ();

    $conn = null; 
    try 
    {
        db_conn( $conn );
        $stmt = $conn -> prepare( $sql );
        $stmt -> execute( $arr_prepare );
        $result = $stmt->fetchAll();
    } 
    catch ( Exception $e ) 
    {
        return $e->getMessage(); 
    }
    finally
    {
        $conn = null; 
    }

    return $result;
}

//todo 실행
// $a=1;
// var_dump(todo_select_todo_detail($a));
//todo 종료


// ---------------------------------------
// 함수명      : update_check_flg
// 기능        : 체크리스트 update
// 파라미터    : &$param_arr
// 리턴값      : $result_count
// ---------------------------------------

function update_check_flg(&$param_arr)
{
    $sql=
    " UPDATE "
    ." routine_list "
    ." SET "
    ." list_done_flg = :list_done_flg "
    ." WHERE "
    ." list_no = :list_no "
    ;

    $arr_prepare =
            array(
                ":list_no" => $param_arr["list_no"]
                ,":list_done_flg" => $param_arr["list_done_flg"]
            );
            
    $conn = null;
    
    try {
        db_conn($conn);
        $conn->beginTransaction();
        $stmt = $conn ->prepare($sql);
        $stmt->execute($arr_prepare);
        $result_count = $stmt->rowCount();
        $conn->commit();
    } 
    catch (Exception $e) 
    {
        $conn->rollBack();
        return $e->getMessage();
    }
    finally
    {
        $conn =null;
    }
    
    return $result_count;
}

/*---------------------------------------------
함수명 : todo_select_recom_routine
기능   : 삽입 페이지 할일 랜덤 추천
파라미터 : 
리턴값  :  array     $result/ERRMSG
사용여부 : o
-----------------------------------------------*/
function todo_select_recom_routine()
{
    $sql =
        " SELECT "
        ." recom_no "
        ." ,recom_title "
        ." ,recom_contents "
        ." FROM "
        ." recom_routine "
        ." ORDER BY "
        ." RAND() "
        ." LIMIT 1 "
        ;

    $arr_prepare = array();

    $conn = null;

    try 
    {
        db_conn( $conn );
        $stmt = $conn -> prepare( $sql );
        $stmt -> execute( $arr_prepare );
        $result = $stmt->fetchAll();
    } 
    catch ( Exception $e) 
    {
        return $e->getMessage();
    }
    finally 
    {
        $conn = null;
    }

    return $result[0];  //이중배열
}

/*---------------------------------------------
함수명 : todo_insert_info
기능   : db의 list, info에 둘다 정보가 적용
파라미터 : int      &$param_arr
리턴값  :  int/array     $result_cnt/ERRMSG
-----------------------------------------------*/
function todo_insert_info( &$param_arr )
{
    $sql =
        " INSERT INTO "
        ." routine_info "
        ." ( "
        ." routine_title "
        ." ,routine_contents "
        ." ,routine_due_time "
        ." ) "
        ." VALUES "
        ." ( "
        ." :routine_title "
        ." ,:routine_contents "
        ." ,:routine_due_time "
        ." ) "
        ;

    $arr_prepare =
    array(
        ":routine_title" => $param_arr["routine_title"]
        ,":routine_contents" => $param_arr["routine_contents"]
        ,":routine_due_time" => $param_arr["routine_due_time"]
    );

    $conn = null;
    
    try 
    {
        db_conn( $conn );
        $conn->beginTransaction();
        $stmt = $conn -> prepare( $sql ); 
        $stmt -> execute( $arr_prepare ); 
        $result_cnt = $stmt->rowCount();
        $conn->commit();
    } 
    catch ( Exception $e) 
    {
        $conn->rollBack();
        return $e->getMessage(); 
    }
    finally 
    {
        $conn = null;
    }

    return $result_cnt;
}

//to do 
// $a = array("routine_title"=>"str"
//             ,"routine_contents"=>"sttttt"
//             ,"routine_due_time"=>1212
//             );
// // var_dump($a);
// var_dump(todo_insert_info($a));
// // to do


/*---------------------------------------------
함수명 : todo_insert_routine_info
기능   : routine_info에 정보 인서트
파라미터 : array      &$param_arr
리턴값  :  str     $last_no/ERRMSG
사용여부 : o
-----------------------------------------------*/
function todo_insert_routine_info( &$param_arr )
{
    $sql =
        " INSERT INTO "
        ." routine_info "
        ." ( "
        ." routine_title "
        ." ,routine_contents "
        ." ,routine_due_time "
        ." ) "
        ." VALUES "
        ." ( "
        ." :routine_title "
        ." ,:routine_contents "
        ." ,:routine_due_time "
        ." ) "
        ;

    $arr_prepare =
    array(
        ":routine_title" => $param_arr["routine_title"]
        ,":routine_contents" => $param_arr["routine_contents"]
        ,":routine_due_time" => $param_arr["routine_due_hour"].$param_arr["routine_due_min"].'00'
    );

    $conn = null;
    
    try 
    {
        db_conn( $conn );
        $conn->beginTransaction();
        $stmt = $conn -> prepare( $sql ); 
        $stmt -> execute( $arr_prepare ); 
        $last_no = $conn->lastInsertId();
        $conn->commit();
    } 
    catch ( Exception $e) 
    {
        $conn->rollBack();
        return $e->getMessage(); 
    }
    finally 
    {
        $conn = null;
    }

    return $last_no;
}


/*---------------------------------------------
함수명 : todo_insert_routine_list
기능   : routine_info정보를 select해서 routine_list 테이블에 insert 
파라미터 : int      &$param_no
리턴값  :  int/str     $last_no/ERRMSG
사용여부 : o
-----------------------------------------------*/
function todo_insert_routine_list( &$param_no )
{
    $sql =
        " INSERT INTO "
        ." routine_list "
        ." ( "
        ." routine_no "
        ." ,list_title "
        ." ,list_contents "
        ." ,list_due_time "
        ." ) "
        ." SELECT "
        ." routine_no "
        ." ,routine_title "
        ." ,routine_contents "
        ." ,routine_due_time "
        ." FROM "
        ." routine_info "
        ." WHERE "
        ." routine_no = :routine_no "
        ;

    $arr_prepare =
    array(
        ":routine_no" => $param_no
    );

    $conn = null;
    
    try 
    {
        db_conn( $conn );
        $conn->beginTransaction();
        $stmt = $conn -> prepare( $sql ); 
        $stmt -> execute( $arr_prepare ); 
        $last_no = $conn->lastInsertId();
        $conn->commit();
    } 
    catch ( Exception $e) 
    {
        $conn->rollBack();
        return $e->getMessage(); 
    }
    finally 
    {
        $conn = null;
    }

    return $last_no;
}
//to do 
// $a = array( 
//             "routine_title"=>"str"
//             ,"routine_contents"=>"sttttt"
//             ,"routine_due_hour"=>12
//             ,"routine_due_min"=>12
//             );
// // var_dump($a);
// var_dump(todo_select_list($a));
// to do


?>