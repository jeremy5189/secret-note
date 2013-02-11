<?php
session_start();

if( $_POST['auth_code'] == $_SESSION['auth_code'] )
{
    $subject = "CMS: Your message has been read";
    $content = "This is an automatic notification to let you know that Your message https://ssinrc.org/secret/?q=" . $_POST['msg_id'] . " has been read. \n\n Want to send another one? \n https://ssinrc.org/secret";
    mail($_SESSION['to_email'], $subject, $content ,'From: noreply@ssinrc.org');
}

?>