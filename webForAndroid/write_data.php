<?php
    require 'connec.php';

    // Prepare the SQL statement

    $sql = "INSERT INTO dcw.data (temp,humid,smoke) VALUES ('".$_GET["temp1"]."','".$_GET["humid1"]."','".$_GET["smoke1"]."')";    

    // Execute SQL statement

    mysqli_query($my_conn,$sql);
    echo "DATA SAVED";
?>