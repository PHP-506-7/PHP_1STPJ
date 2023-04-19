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


?>