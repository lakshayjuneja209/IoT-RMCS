<?php
    require 'config.php';

    // Prepare the SQL statement

    $sql = "update ".$my_db.".machine_status set status = '".$_GET["status"]."' where device_id='".$_GET["device_id"]."'";

    // Execute SQL statement

    mysqli_query($my_conn,$sql);
?>