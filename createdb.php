<?php

$mysql_host = "localhost";
$mysql_user = "root";
$mysql_password = "";
$mysql_db = "ASEO";
$_link = mysqli_connect($mysql_host,$mysql_user,$mysql_password);

if(mysqli_connect_errno())
{

	echo " Failed to connect to mysql".mysqli_connect_error();
}


$query = "CREATE DATABASE ".$mysql_db ;
if (mysqli_query($_link,$query)) 
{
  echo "Database my_db created successfully";
} 
else 
{
  echo "Error creating database: " . mysqli_error($_link);
}



?>
