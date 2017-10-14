<?php
	$my_host = 'localhost';
	$my_userName = 'root';
	$my_pass = '';
	$my_db = 'dcw';
	$conn_error = 'Connection Error';
   $my_conn=mysqli_connect($my_host, $my_userName, $my_pass, $my_db);

	if(!(@mysqli_connect($my_host, $my_userName, $my_pass, $my_db)))
		die(mysqli_error($my_conn));		
?>