<?php
session_start();

if( $_POST['auth_code'] == $_SESSION['auth_code'] )
{
    include('config.php');
    
    $DB_link = mysql_connect(DB_HOST, DB_USER, DB_PASS);
    mysql_select_db(DB_NAME); 
    mysql_query("SET NAMES UTF8;");
    
    $sql = "UPDATE ".DB_TABLE_NAME." SET `email` = '%s' WHERE `id` = '%s';";
    $sql = sprintf( $sql, mysql_real_escape_string($_POST['email']), 
                          mysql_real_escape_string($_POST['msg_id']) );
    
    $result = mysql_query($sql, $DB_link);
    
    if( $result )
        echo "OK";
    else
        echo "ERROR";
        
    mysql_close($DB_link);
}
?>