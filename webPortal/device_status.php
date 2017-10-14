<?php
    require 'config.php';

    // Prepare the SQL statement

    $query = "SELECT * from  dcw.machine_status";    

    // Execute SQL statement

    $my_queryOutput = mysqli_query($my_conn,$query);
    while($query_row = mysqli_fetch_assoc($my_queryOutput)){
    	$id = $query_row['device_id'];
		$status = $query_row['status'];
		if ($status == 1){
			echo "Device ".$id." is on."."<br>";
		}
		else if ($status == 0) {
			echo "Device ".$id." is off."."<br>";
		}
    }
?>