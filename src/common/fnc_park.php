<?php
include_once( URL_DB );

/*---------------------------------------------
함수명 : todo_insert_recom_routine
기능   : 게시글 작성
파라미터 : Arr      &$param_arr
리턴값  :  int/array     $result_cnt/ERRMSG
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
        $conn->befigTransaction();
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
함수명 : todo_detail_routine_list
기능   : 게시글 정보
파라미터 : int      &$param_no
리턴값  :  int/array     $result/ERRMSG
-----------------------------------------------*/

function todo_detail_routine_list( &$param_no )
{
    $sql =
        " SELECT "
        ." list_no "
        ." ,list_title "
        ." ,list_contents "
        ." ,list_due_time "
        ." ,list_done_flg "
        ." ,list_now_date "
        ." FROM "
        ." routine_list "
        ." WHERE "
        ." list_no = :list_no "
        ;

    $arr_prepare =
        array
        (
            ":list_no"=>$param_no
        );

    $conn = null;

    try 
    {
        
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
함수명 : todo_update_flg
기능   : 게시글 정보
파라미터 : int      &$param_arr
리턴값  :  int/array     $result/ERRMSG
-----------------------------------------------*/
function todo_update_flg( &$param_arr )
{
    $sql =
        " UPDATE "
        ." routine_info "
        ." SET "
        ." routine_del_flg = '1'"
        ." WHERE "
        ." routine_no = :routine_no "
        ;
    
    $arr_prepare =
        array (
            " routine_no " => $param_arr
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







?>