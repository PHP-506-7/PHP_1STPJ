<?php
// ---------------------------------------
// 함수명      : db_conn
// 기능        : db DDO 연결
// 파라미터    : &$param_conn
// 리턴값      : 없음
// ---------------------------------------
function db_conn( &$param_conn )
{
    $host        = "localhost";
    $user        = "root";
    $pass        = "root506";
    $charset     = "utf8mb4";
    $db_name     = "todo";
    $dns         = "mysql:host=".$host.";dbname=".$db_name.";charset=".$charset;
    $pdo_option  =
        array(
            PDO::ATTR_EMULATE_PREPARES     => false
            ,PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION
            ,PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC
        );

    try
    {
        $param_conn = new PDO( $dns, $user, $pass, $pdo_option );
    }
    catch( Exception $e )
    {
        $param_conn = null;
        throw new Exception( $e->getMessage() );
    }
}


/*---------------------------------------------
함수명      : todo_select_detail
기능        : 게시글 정보
파라미터    : int           &$param_no
리턴값      : int/array     $result/ERRMSG
작성자      : 박수연
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
함수명      : todo_select_recom_routine
기능        : 삽입 페이지 할일 랜덤 추천
파라미터    : 없음
리턴값      : array     $result/ERRMSG
작성자      : 박수연
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
함수명      : todo_insert_routine_info
기능        : routine_info에 정보 인서트
파라미터    : array      &$param_arr
리턴값      : str        $last_no/ERRMSG
작성자      : 박수연
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


/*---------------------------------------------------------------------
함수명      : todo_insert_routine_list
기능        : routine_info정보를 select해서 routine_list 테이블에 insert 
파라미터    : int      &$param_no
리턴값      :  int/str     $last_no/ERRMSG
작성자      : 박수연
-----------------------------------------------------------------------*/
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


// ---------------------------------------
// 함수명      : insert_routine_list
// 기능        : 루틴임포를 리스트에 생성
// 파라미터    : 없음
// 리턴값      : 없음
// 작성자      : 김재성
// ---------------------------------------

function insert_routine_list()
{
    

    $sql = 
    " INSERT INTO "
    ." routine_list "
    ." ( "
    ."      routine_no "
	."      ,list_title "
	."      ,list_contents "
	."      ,list_due_time "
    ." ) "
	." ( "
    ."  SELECT "
	."      routine_no "
	."      ,routine_title "
	."      ,routine_contents "
	."      ,routine_due_time "
	."  FROM routine_info "
	."  WHERE "
    ."      routine_del_flg='0' "
    ." ) "
    ;

    $conn=null;
    
    try {
        db_conn($conn);
        $conn->beginTransaction();
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $result_count=$stmt->rowCount();
        $conn->commit();
        
    } catch (EXCEPTION $e) {
        echo $e->getMessage();
        $conn->rollback();
    }
    finally{
        $conn = null;
    }
    return $result_count;
}

// ---------------------------------------
// 함수명      : routin_list_info
// 기능        : 오늘 routin_list 모든정보 및 정렬
// 파라미터    : 없음
// 리턴값      : result
// 작성자      : 김재성
// ---------------------------------------

function routin_list_info()
{
    

    $sql = 
    " SELECT "
	." list_title "
	." ,list_contents "
	." ,list_due_time "
    ." ,list_no "
    ." ,list_done_flg "
    ." FROM "
    ." routine_list "
    ." WHERE "
    ." date(list_now_date)=date(NOW()) "
    ." ORDER BY "
    ." list_done_flg "
    ." ASC "
    ." ,list_due_time "
    ." ASC "
    ; 

    $conn=null;
    
    try {
        db_conn($conn);
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

    } catch (EXCEPTION $e) {
        echo $e->getMessage();
    }
    finally{
        $conn = null;
    }
    return $result;
}

// ---------------------------------------
// 함수명      : routin_list_info_count
// 기능        : 오늘 routin_list 계수
// 파라미터    : $param_flg
// 리턴값      : $result[0]['cnt']
// 작성자      : 김재성
// ---------------------------------------

function routin_list_info_count($param_flg)
{
    

    $sql = 
    " SELECT "
    ." count(*) cnt "
    ." FROM "
    ." routine_list "
    ." WHERE "
    ." date(list_now_date)=date(NOW()) "
    ." AND "
    ." list_done_flg=:list_done_flg "
    ; 

    $arr=array(
        ":list_done_flg" =>$param_flg
    );

    $conn=null;
    
    try {
        db_conn($conn);
        $stmt=$conn->prepare($sql);
        $stmt->execute($arr);
        $result = $stmt->fetchAll();
        $conn->commit();

    } catch (EXCEPTION $e) {
        echo $e->getMessage();
        $conn->rollback();
    }
    finally{
        $conn = null;
    }
    return $result[0]['cnt'];
}

// ---------------------------------------
// 함수명      : update_check_flg
// 기능        : 체크리스트 update
// 파라미터    : &$param_arr
// 리턴값      : 없음
// 작성자      : 김재성
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
        
        
        
    } catch (Exception $e) {
        $conn->rollBack();
        return $e->getMessage();
    }
    finally{
        $conn =null;
    }
    
    return $result_count;
}
?>