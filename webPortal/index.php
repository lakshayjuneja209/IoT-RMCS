<?php
   include('session.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Weather Monitoring and Device Management</title>
  <!-- <script src="https://s.codepen.io/assets/libs/modernizr.js" type="text/javascript"></script> -->


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
   
       function updateFeed(){
        // console.log('entered updatefeed function');
      $.ajax({
      url: 'device_status2.php',
      type: 'GET',
      success: function(response){
      var status = response.split(',');
      // console.log(status[0]);
      if(status[0] == 0){
        document.getElementById('stat1').innerHTML = '(Status = off)';
        document.getElementById('led1').checked = false;
      }
      else if(status[0] == 1){
        document.getElementById('stat1').innerHTML = '(Status = on) ';
        document.getElementById('led1').checked = true;
      }

      if(status[1] == 0){
        document.getElementById('stat2').innerHTML = '(Status = off)';
        document.getElementById('led2').checked = false;
      }
      else if(status[1] == 1){
        document.getElementById('stat2').innerHTML = '(Status = on) ';
        document.getElementById('led2').checked = true;
      }

      if(status[2] == 0){
        document.getElementById('stat3').innerHTML = '(Status = off)';
        document.getElementById('led3').checked = false;
      }
      else if(status[2] == 1){
        document.getElementById('stat3').innerHTML = '(Status = on) ';
        document.getElementById('led3').checked = true;
      }

      if(status[3] == 0){
        document.getElementById('stat4').innerHTML = '(Status = off)';
        document.getElementById('led4').checked = false;
      }
      else if(status[3] == 1){
        document.getElementById('stat4').innerHTML = '(Status = on) ';
        document.getElementById('led4').checked = true;
      }  


       setTimeout(updateFeed,5000);
      },
      error: function(){
        setTimeout(updateFeed,5000);
      }
      
      });
      //setInterval(updateFeed,30000);
      }
      
      updateFeed();



		$(document).ready(function(){
    		$("#button1on").click(function(){
        		$.ajax({url: "change_device_status.php?status=1&device_id=1", success: function(result){document.getElementById('stat1').innerHTML = '(Status = on) ';
              document.getElementById('led1').checked = true;}});
    		});
		});
		$(document).ready(function(){
    		$("#button1off").click(function(){
        		$.ajax({url: "change_device_status.php?status=0&device_id=1", success: function(result){document.getElementById('stat1').innerHTML = '(Status = off)';
              document.getElementById('led1').checked = false;}});
    		});
		});
		$(document).ready(function(){
    		$("#button2on").click(function(){
        		$.ajax({url: "change_device_status.php?status=1&device_id=2", success: function(result){document.getElementById('stat2').innerHTML = '(Status = on) ';
          document.getElementById('led2').checked = true;}});
    		});
		});
		$(document).ready(function(){
    		$("#button2off").click(function(){
        		$.ajax({url: "change_device_status.php?status=0&device_id=2", success: function(result){document.getElementById('stat2').innerHTML = '(Status = off)';
              document.getElementById('led2').checked = false;}});
    		});
		});
		$(document).ready(function(){
    		$("#button3on").click(function(){
        		$.ajax({url: "change_device_status.php?status=1&device_id=3", success: function(result){document.getElementById('stat3').innerHTML = '(Status = on) ';
              document.getElementById('led3').checked = true;}});
    		});
		});$(document).ready(function(){
    		$("#button3off").click(function(){
        		$.ajax({url: "change_device_status.php?status=0&device_id=3", success: function(result){document.getElementById('stat3').innerHTML = '(Status = off)';
              document.getElementById('led3').checked = false;}});
    		});
		});$(document).ready(function(){
    		$("#button4on").click(function(){
        		$.ajax({url: "change_device_status.php?status=1&device_id=4", success: function(result){document.getElementById('stat4').innerHTML = '(Status = on) ';
              document.getElementById('led4').checked = true;}});
    		});
		});$(document).ready(function(){
    		$("#button4off").click(function(){
        		$.ajax({url: "change_device_status.php?status=0&device_id=4", success: function(result){document.getElementById('stat4').innerHTML = '(Status = off)';
              document.getElementById('led4').checked = false;}});
    		});
		});

</script>  
</head>
<body>
<center>
<br><br><h1>Weather Monitor</h1><a href="javascript: window.location.reload()"><img src="img/61225.png" width="20px" style="margin: 0 15px"></a>
<table class="rwd-table">
  <tr>
    <th>S. No.</th>
    <th>Temperature</th>
    <th>Humidity</th>
    <th>Smoke Level</th>
    <th>Date & Time</th>
  </tr>
  <?php 
 	require 'config.php';
	$query = 'select * from data';
	if($my_queryOutput = mysqli_query($my_conn,$query)) {
		$value = 1;
		if(mysqli_num_rows($my_queryOutput) == NULL){
			echo'<tr>
    				<td data-th="S. No."> </td>
    				<td data-th="Temperature"> </td>
    				<td data-th="Humidity" align="center">Nothing to Display</td>
    				<td data-th="Smoke Level"> </td>
    				<td data-th="Date & Time"> </td>
  					</tr>';
		}
		else{
 			while($query_row = mysqli_fetch_assoc($my_queryOutput)) {
				$temp = $query_row['temp'];
				$humid = $query_row['humid'];
				$smoke = $query_row['smoke'];
				$time = $query_row['time'];
				echo '<tr>
    				<td data-th="S. No.">'.$value.'</td>
    				<td data-th="Temperature">'.$temp.'</td>
    				<td data-th="Humidity">'.$humid.'</td>
    				<td data-th="Smoke Level">'.$smoke.'</td>
    				<td data-th="Date & Time">'.$time.'</td>
  					</tr>';
  				$value++;
			}
		}	
	}	
  ?>
</table>
</center>
<br><br>
<center>
<h1>Device Manager</h1><br><br>
  Device 1 &nbsp;&nbsp;&nbsp;
  <button class = "bfulbtn" id = 'button1on'>Turn On </button> &nbsp;
  <button class = "bfulbtn" id = 'button1off'>Turn Off </button>
  <span id = 'stat1' class="statspan">
  (Status = on) </span> <input disabled='true' id = "led1" type="radio" name="radical" />
  </center>   
  <br><br>
  <center>
  Device 2 &nbsp;&nbsp;&nbsp;
  <button class = "bfulbtn" id = 'button2on'>Turn On </button> &nbsp;
  <button class = "bfulbtn" id = 'button2off'>Turn Off </button>
  <span id = 'stat2' class="statspan">
  (Status = on) </span>  <input disabled='true' id = "led2" type="radio" name="radical2" />
  </center> 
  <br><br>
  <center>
  Device 3 &nbsp;&nbsp;&nbsp;
  <button class = "bfulbtn" id = 'button3on'>Turn On </button> &nbsp;
  <button class = "bfulbtn" id = 'button3off'>Turn Off </button>
  <span id = 'stat3' class="statspan">
  (Status = on) </span>  <input disabled='true' id = "led3" type="radio" name="radical3" />
  </center> 
  <br><br>
  <center>
  Device 4 &nbsp;&nbsp;&nbsp;
  <button class = "bfulbtn" id = 'button4on'>Turn On </button> &nbsp;
  <button class = "bfulbtn" id = 'button4off'>Turn Off </button>
  <span id = 'stat4' class="statspan">
  (Status = on) </span>  <input disabled='true' id = "led4" type="radio" name="radical4" />
  </center> 
  <br><br>

<!-- <p>&larr; Drag window (in editor or full page view) to see the effect. &rarr;</p> -->
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>
</html>