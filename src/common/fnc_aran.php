<?php
include_once("db_common.php");

// ----------------------------------------------
// 함수명      : select_list_info
// 기능        : 리스트 정보를 받아오는 함수
// 파라미터    : 없음          
// 리턴값      : Array        $result
// ----------------------------------------------
function select_list_info()
{
    $sql =
        " SELECT "
        ."  list_no "
        ."  ,list_contents "
        ."  ,list_title "
        ."  ,list_due_time "
        ." FROM routine_list "
    ;

    $arr_prepare = array();

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
    
    return $result;
}


// ----------------------------------------------
// 함수명      : select_page_list_info
// 기능        : 해당 페이지 리스트 정보를 받아오는 함수
// 파라미터    : Array        &$param_arr
// 리턴값      : Array        $result
// ----------------------------------------------
function select_page_list_info(&$param_arr)
{
    $sql =
        " SELECT "
        ."  list_no "
        ."  ,routine_no "
        ."  ,list_contents "
        ."  ,list_title "
        ."  ,list_due_time "
        ." FROM routine_list "
        ." WHERE list_no = :list_no "
    ;

    $arr_prepare =
        array(
            ":list_no" => $param_arr["list_no"]
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
    
    return $result;
}


// ----------------------------------------------
// 함수명      : select_page_routine_info
// 기능        : 해당 페이지 루틴 정보를 받아오는 함수
// 파라미터    : INT          &$param_no
// 리턴값      : Array        $result
// ----------------------------------------------
function select_page_routine_info(&$param_no)
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
    
    $arr_no = 
        array(
            "list_no" => $param_no
        );

    $arr = select_page_list_info($arr_no);
    
    $arr_prepare =
        array(
            ":routine_no" => $arr[0]["routine_no"]
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

// ----------------------------------------------
// 함수명      : update_routine_info
// 기능        : routine_no 정보 업데이트
// 파라미터    : Array        &$param_arr
// 리턴값      : INT/STRING   $result_cnt/ERRMSG
// ----------------------------------------------
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

// ----------------------------------------------
// 함수명      : update_routine_list
// 기능        : routine_list 정보를 당일
//               routine_info 정보로 최신화
// 파라미터    : 없음
// 리턴값      : INT/STRING   $result_cnt/ERRMSG
// ----------------------------------------------
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

// ----------------------------------------------
// 함수명      : update_routine_del_flg
// 기능        : 루틴 삭제 플래그 설정
// 파라미터    : Array        &$param_arr    
// 리턴값      : INT/STRING   $result_cnt/ERRMSG
// ----------------------------------------------
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

// ----------------------------------------------
// 함수명      : delete_list_info
// 기능        : routine_no가 일치하는 레코드를
//               list에서 모두 삭제
// 파라미터    : Array        &$param_arr    
// 리턴값      : INT/STRING   $result_cnt/ERRMSG
// ----------------------------------------------
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
?>