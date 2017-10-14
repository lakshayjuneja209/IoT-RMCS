<?php
   include("config.php");
   session_start();
   if(isset($_SESSION['login_user'])){
    // die("test debug");
    header("location: index.php");
   }
   

   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($my_conn,$_POST['username']);
      $mypassword = mysqli_real_escape_string($my_conn,$_POST['password']); 
      
      $sql = "SELECT id FROM admin WHERE username = '$myusername' and passcode = '$mypassword'";
      $result = mysqli_query($my_conn,$sql)
         or die("Error: ".mysqli_error($my_conn));
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      // $active = $row['active'];
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
      
      if($count == 1) {
         $_SESSION['myusername']="myusername";
         //session_register("myusername");
         $_SESSION['login_user'] = $myusername;
         
         header("location: index.php");
      }else {
         $error = "Your Login Name or Password is invalid";
         header("location: login.php?error=".$error);
      }
   }
?>
<!-- <html>
   
   <head>
      <title>Login Page</title>
      
      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         
         .box {
            border:#666666 solid 1px;
         }
      </style>
      
   </head>
   
   <body bgcolor = "#FFFFFF">
   
      <div align = "center">
         <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
            
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
                  <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"></div>
               
            </div>
            
         </div>
         
      </div>

   </body>
</html> -->
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  
  
  
      <style type="text/css">
         @import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300);
         * {
           box-sizing: border-box;
           margin: 0;
           padding: 0;
           font-weight: 300;
         }
         body {
           font-family: 'Source Sans Pro', sans-serif;
           color: white;
           font-weight: 300;
         }
         body ::-webkit-input-placeholder {
           /* WebKit browsers */
           font-family: 'Source Sans Pro', sans-serif;
           color: white;
           font-weight: 300;
         }
         body :-moz-placeholder {
           /* Mozilla Firefox 4 to 18 */
           font-family: 'Source Sans Pro', sans-serif;
           color: white;
           opacity: 1;
           font-weight: 300;
         }
         body ::-moz-placeholder {
           /* Mozilla Firefox 19+ */
           font-family: 'Source Sans Pro', sans-serif;
           color: white;
           opacity: 1;
           font-weight: 300;
         }
         body :-ms-input-placeholder {
           /* Internet Explorer 10+ */
           font-family: 'Source Sans Pro', sans-serif;
           color: white;
           font-weight: 300;
         }
         .wrapper {
           background: #50a3a2;
           background: -webkit-linear-gradient(top left, #50a3a2 0%, #53e3a6 100%);
           background: linear-gradient(to bottom right, #50a3a2 0%, #53e3a6 100%);
           position: absolute;
           /*top: 50%;*/
           left: 0;
           width: 100%;
           /*height: 400px;*/
           height: 100%;
           /*margin-top: -200px;*/
           overflow: hidden;
         }
         .wrapper.form-success .container h1 {
           -webkit-transform: translateY(85px);
                   transform: translateY(85px);
         }
         .container {
           max-width: 600px;
           margin: 0 auto;
           padding: 80px 0;
           height: 400px;
           text-align: center;
           margin-top: 65px;
         }
         .container h1 {
           font-size: 40px;
           -webkit-transition-duration: 1s;
                   transition-duration: 1s;
           -webkit-transition-timing-function: ease-in-put;
                   transition-timing-function: ease-in-put;
           font-weight: 200;
         }
         form {
           padding: 20px 0;
           position: relative;
           z-index: 2;
         }
         form input {
           -webkit-appearance: none;
              -moz-appearance: none;
                   appearance: none;
           outline: 0;
           border: 1px solid rgba(255, 255, 255, 0.4);
           background-color: rgba(255, 255, 255, 0.2);
           width: 250px;
           border-radius: 3px;
           padding: 10px 15px;
           margin: 0 auto 10px auto;
           display: block;
           text-align: center;
           font-size: 18px;
           color: white;
           -webkit-transition-duration: 0.25s;
                   transition-duration: 0.25s;
           font-weight: 300;
         }
         form input:hover {
           background-color: rgba(255, 255, 255, 0.4);
         }
         form input:focus {
           background-color: white;
           width: 300px;
           color: #53e3a6;
         }
         form button {
           -webkit-appearance: none;
              -moz-appearance: none;
                   appearance: none;
           outline: 0;
           background-color: white;
           border: 0;
           padding: 10px 15px;
           color: #53e3a6;
           border-radius: 3px;
           width: 250px;
           cursor: pointer;
           font-size: 18px;
           -webkit-transition-duration: 0.25s;
                   transition-duration: 0.25s;
         }
         form button:hover {
           background-color: #f5f7f9;
         }
         .bg-bubbles {
           position: absolute;
           top: 0;
           left: 0;
           width: 100%;
           height: 100%;
           z-index: 1;
         }
         .bg-bubbles li {
           position: absolute;
           list-style: none;
           display: block;
           width: 40px;
           height: 40px;
           background-color: rgba(255, 255, 255, 0.15);
           bottom: -160px;
           -webkit-animation: square 25s infinite;
           animation: square 25s infinite;
           -webkit-transition-timing-function: linear;
           transition-timing-function: linear;
         }
         .bg-bubbles li:nth-child(1) {
           left: 10%;
         }
         .bg-bubbles li:nth-child(2) {
           left: 20%;
           width: 80px;
           height: 80px;
           -webkit-animation-delay: 2s;
                   animation-delay: 2s;
           -webkit-animation-duration: 17s;
                   animation-duration: 17s;
         }
         .bg-bubbles li:nth-child(3) {
           left: 25%;
           -webkit-animation-delay: 4s;
                   animation-delay: 4s;
         }
         .bg-bubbles li:nth-child(4) {
           left: 40%;
           width: 60px;
           height: 60px;
           -webkit-animation-duration: 22s;
                   animation-duration: 22s;
           background-color: rgba(255, 255, 255, 0.25);
         }
         .bg-bubbles li:nth-child(5) {
           left: 70%;
         }
         .bg-bubbles li:nth-child(6) {
           left: 80%;
           width: 120px;
           height: 120px;
           -webkit-animation-delay: 3s;
                   animation-delay: 3s;
           background-color: rgba(255, 255, 255, 0.2);
         }
         .bg-bubbles li:nth-child(7) {
           left: 32%;
           width: 160px;
           height: 160px;
           -webkit-animation-delay: 7s;
                   animation-delay: 7s;
         }
         .bg-bubbles li:nth-child(8) {
           left: 55%;
           width: 20px;
           height: 20px;
           -webkit-animation-delay: 15s;
                   animation-delay: 15s;
           -webkit-animation-duration: 40s;
                   animation-duration: 40s;
         }
         .bg-bubbles li:nth-child(9) {
           left: 25%;
           width: 10px;
           height: 10px;
           -webkit-animation-delay: 2s;
                   animation-delay: 2s;
           -webkit-animation-duration: 40s;
                   animation-duration: 40s;
           background-color: rgba(255, 255, 255, 0.3);
         }
         .bg-bubbles li:nth-child(10) {
           left: 90%;
           width: 160px;
           height: 160px;
           -webkit-animation-delay: 11s;
                   animation-delay: 11s;
         }
         @-webkit-keyframes square {
           0% {
             -webkit-transform: translateY(0);
                     transform: translateY(0);
           }
           100% {
             -webkit-transform: translateY(-700px) rotate(600deg);
                     transform: translateY(-700px) rotate(600deg);
           }
         }
         @keyframes square {
           0% {
             -webkit-transform: translateY(0);
                     transform: translateY(0);
           }
           100% {
             -webkit-transform: translateY(-700px) rotate(600deg);
                     transform: translateY(-700px) rotate(600deg);
           }
         }

      </style>

  
</head>

<body>
  <div class="wrapper">
   <div class="container">
      <h1>Welcome</h1>
      
      <form class = "form" action = "" method = "post">
         <input type="text" name="username" placeholder="Username" required>
         <input type="password" name="password" placeholder="Password" required>
         <label style="color: red; font-weight: bold;"> <?php  
         
         if(isset($_GET['error'])){
          $errormsg = $_GET['error'];
          echo $errormsg; echo "<br>";
         }

          
         ?> </label>
         <button type="submit" value="submit" id="login-button">Login</button>

      </form>
   </div>
   
   <ul class="bg-bubbles">
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
   </ul>
</div>
  <!-- <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script  src="js/index.js"></script> -->

</body>
</html>