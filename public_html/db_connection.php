<?php
function OpenCon()
 {
 $dbhost = "dbcourse.cs.smu.ca";
 $dbuser = "u49";
 $dbpass = "fenceWERE13";
 $db = "u49";
 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 
 return $conn;
 }
 
function CloseCon($conn)
 {
 $conn -> close();
 }
   
?>