<?php
define( "URL_DB", "common/fnc_aran.php" );
include_once( URL_DB );

$get_arr = $_GET;

// routine_info 테이블 routine_del_flg 업데이트
update_routine_del_flg( $get_arr );

// routine_list 테이블 정보 삭제
delete_list_info( $get_arr );

header( "Location: todo_routine_list.php" );
exit();

?>