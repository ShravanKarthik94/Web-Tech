<?php
error_reporting(E_ERROR | E_PARSE);

$title = $_POST["Title"];
$author = $_POST["Author"];
$abstract = $_POST["Abstract"];
$keywords = $_POST["Keywords"];
$citation = $_POST["Citation"];
$path = $_POST["Filename"];
$pid = $_POST["PID"];

$mysql_host = "localhost";
$mysql_user = "root";
$mysql_password = "";
$mysql_db = "ASEO";

$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
$link = mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_db, $link);

$title = trim(preg_replace('/\s\s+/', ' ', $title));
$author =  trim(preg_replace('/\s\s+/', ' ', $author));
$abstract = trim(preg_replace('/\s\s+/', ' ', $abstract));
$keywords = trim(preg_replace('/\s\s+/', ' ', $keywords));

echo $title."<br>".$author."<br>".$abstract."<br>".$keywords."<br>".$citation."<br>".$path."<br>".$pid."<br>";

$textparse = array("Title" => $title,"Author" => $author, "Abstract" => $abstract, "Keyword" => $keywords);

foreach($textparse as $attr=>$value)
  {
    $text = explode(" ",$value);
    foreach($text as $word)
      {
        $word = preg_replace("/[^A-Za-z0-9]/",'',$word);
        $word = strtolower($word);
        $result = mysql_query("SELECT 1 FROM ".$word, $link);
        $num_rows = mysql_num_rows($result);
        if($num_rows > 0)
          {
            $result = mysql_query("SELECT * FROM ".$word." WHERE PID = ".$pid, $link);
            $num_rows = mysql_num_rows($result);
            if($num_rows > 0)
              {
                $attr2 = $attr."_c";
                $update = "UPDATE  ".$word." SET ".$attr2." = ".$attr2." + 1 WHERE PID = ".$pid;
                mysqli_query($con,$update);
              }
            else
              {
                $query = "INSERT INTO ".$word." VALUES(".$pid.",0,0,0,0)";
                mysqli_query($con,$query);
                $attr2 = $attr."_c";
                $update = "UPDATE ".$word." SET ".$attr2." = ".$attr2." + 1 WHERE PID = ".$pid;
                mysqli_query($con,$update);
              }
          }
        else
          {
            $query = "CREATE TABLE ".$word." (PID INTEGER, Title_c INTEGER ,Abstract_c INTEGER ,Author_c INTEGER,Keyword_c INTEGER)";
            mysqli_query($con,$query);
            $query = "INSERT INTO ".$word." VALUES(".$pid.",0,0,0,0)";
 mysqli_query($con,$query);
            $attr2 = $attr."_c";
            $update = "UPDATE ".$word." SET ".$attr2." = ".$attr2." + 1 WHERE PID = ".$pid;
            mysqli_query($con,$update);
          }
      }
  }
$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
$link = mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_db, $link);
$table = "SECOND_TABLE";

$result = mysql_query("SELECT 1 FROM ".$table, $link);
$num_rows = mysql_num_rows($result);

if($num_rows <= 0)
{
$query = "CREATE TABLE ".$table." (PID INTEGER, PAPER_NAME VARCHAR(100) ,AUTHOR VARCHAR(100) ,CITATION_COUNT INTEGER)";
$result = mysqli_query($con,$query);
}
 
$query = "INSERT INTO ".$table." VALUES(".$pid.",'".$title."','".$author."',$citation)";
 echo '<br>'.$query.'<br> End of paper<br><br><br><br>';
 mysqli_query($con,$query);
$myfile = fopen($path, "w") or die("Unable to open file!");
$texttowrite = $title."\n".$author."\n".$abstract."\n".$keywords."\n".$citation;
fwrite($myfile, $texttowrite);
fclose($myfile);
if(chown($myfile,"shravan"))
{
echo "user changed";
}
if(chgrp($myfile,"shravan"))
{
echo "<br>group changed";
}

if(chmod($myfile,0777))
{
echo "<br> perm changed";
}
?>
<html>
<body>
Form Loaded<br>
<form action = "form.html">
<input type ="submit" value = "Submit another file">
<form>
<body>
<html>

