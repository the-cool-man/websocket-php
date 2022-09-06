<?php

global $con;
$con = mysqli_connect('localhost','trywhm_share','Lmki)nhG56G_','trywhm_websocket_db');
if(!$con)
{
 echo 'unable to connect with db';
 die();
}