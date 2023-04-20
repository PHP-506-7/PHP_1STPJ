<?php
function insert_routine_list()
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
	." ( "
    ." SELECT "
	." routine_no "
	." ,routine_title "
	." ,routine_contents "
	." ,routine_due_date "
	." FROM routine_info "
	." WHERE "
    ." routine_del_flg='0' "
    ." ) "
    ;

    $conn=null;
    
    try {
        db_conn($conn);
        $stmt=$conn->prepare($sql);
        $conn->beginTransaction();
        $stmt->execute();
        $conn->commit();
        
    } catch (EXCEPTION $e) {
        echo $e->getMessage();
        $conn->rollback();
    }
    finally{
        $conn = null;
    }
}

// ---------------------------------------
// 함수명      : routin_list_info
// 기능        : 오늘 routin_list 모든정보
// 파라미터    : 없음
// 리턴값      : 없음
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
        $conn->commit();

    } catch (EXCEPTION $e) {
        echo $e->getMessage();
        $conn->rollback();
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
