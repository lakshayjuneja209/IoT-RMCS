<?php
    require 'config.php';

    // Prepare the SQL statement
    // $idparam = $_GET["id"];

    $sql = "SELECT STATUS FROM dcw.machine_status";

    // Execute SQL statement

    $my_queryOutput = mysqli_query($my_conn,$sql);
    
    while($query_row = mysqli_fetch_assoc($my_queryOutput)){
    	$status = $query_row['STATUS'];
		echo $status.',';
        // print_r($query_row);
    }
?>