<?php 
include "./connect_to_mysql_server.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>PHP Retrieve Data from MySQL using Drop Down Menu</title>
  <script  type="application/javascript" src=./jquery.min.js></script>
  <link rel="shortcut icon" href="#">
  <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
  <meta content="utf-8" http-equiv="encoding">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>

  
</head>
<body>

<div>Choose Database: </div>
  <select id="database">
    <option value=0 disabled selected>-- Select Database --</option>
    <?php
      

        $db_info = mysqli_query($mysql_srvr, "SHOW DATABASES"); 


        while($data = mysqli_fetch_array($db_info)){
            echo "<option value='". $data[0] ."'>" .$data[0] ."</option>"."\n";  
        }
        mysqli_close($mysql_srvr);	
    ?>  
  </select>
  <fieldset id="servers"></fieldset>
  <input type="checkbox"  id='select_all' value='select_all'>Select All<br>
  <input type="submit" id="submitButton" value="SUBMIT"/>


<table id="info" class="display nowrap" style="width:100%"></table>

<script type="application/javascript" src=./ajax_code.js></script>



</body>
</html>