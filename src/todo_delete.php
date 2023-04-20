<?php
define( "URL_DB", "common/fnc_aran.php" );
include_once( URL_DB );

var_dump($get_arr = $_GET);

// routine_info 테이블 routine_del_flg 업데이트
update_routine_del_flg($get_arr);

// 위에서 업데이트한 레코드에 영향받는 routine_list 테이블 레코드 물리적 삭제
delete_list_info($get_arr);

header( "Location: todo_list.php" );
exit();

?>