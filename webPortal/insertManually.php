<?php
	require 'config.php';
	if (isset($_POST['temp']) && isset($_POST['humid']) && isset($_POST['smoke'])) {
		$temp = mysqli_real_escape_string($my_conn,$_POST['temp']);
		$humid = mysqli_real_escape_string($my_conn,$_POST['humid']);
		$smoke = mysqli_real_escape_string($my_conn,$_POST['smoke']);
		if(!empty($temp) && !empty($humid) && !empty($smoke)){
			$manual_query = "insert into data (temp,humid,smoke) values ('$temp', '$humid', '$smoke')";
			if (!mysqli_query($my_conn,$manual_query)) {
  				die('Error: ' . mysqli_error($my_conn));
			}
			echo "1 record added";
		}
		else
			echo "Enter all the fields";
		mysqli_close($my_conn);
	}
?>
<form action="" method="POST">
Temperature: <input type="text" name="temp" size="20" maxlength="30">
Humidity: <input type="text" name="humid" size="20" maxlength="30">
Smoke: <input type="text" name="smoke" size="20" maxlength="30">
<input type="submit" name="accion" value="Submit">
</FORM>