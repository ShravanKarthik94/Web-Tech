<?php
error_reporting(E_ERROR | E_PARSE);

$mysql_host = "localhost";
$mysql_user = "root";
$mysql_password = "";
$mysql_db = "ASEO";

$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
$link = mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_db, $link);

if (mysqli_connect_errno()) 
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$directory = '/home/shravan/Desktop/PDFS text';     
$files1 = scandir($directory);
$indexno = 0;
foreach($files1 as $indipdf)
{
if($indexno < 2)
{
$indexno++;
continue;
}
$indexno++;
$filepath = $directory."/".$indipdf;
echo $filepath."<br>";
$content = array();
$handle = fopen($filepath, "r");
if ($handle) {
	$indexno1 =1;
    while (($buffer = fgets($handle, 4096)) !== false) {
        $content[$indexno1] = $buffer;
	$indexno1++;
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}
$title = $content[1];

$author = $content[2];

$abstract = $content[3];

$keywords = $content[4];

echo $title."<br><br>".$author."<br><br>".$abstract."<br><br>".$keywords."<br>";

$citation = $content[5];
$indexpaper = $indexno -2;

$title = trim(preg_replace('/\s+/', ' ', $title));
$author =  trim(preg_replace('/\s+/', ' ', $author));
$abstract = trim(preg_replace('/\s+/', ' ', $abstract));
$keywords = trim(preg_replace('/\s+/', ' ', $keywords));


$textparse = array("Title" => $title,"Author" => $author, "Abstract" => $abstract, "Keyword" => $keywords);
foreach($textparse as $attr=>$value)
	{
		$text = explode(" ",$value);
		foreach($text as $word)
			{
				$word = preg_replace("/[^A-Za-z0-9]/",'',$word);
				$word = strtolower($word);
				#stemming function implement and store stemmed word into $word
				#remove stop words
				$result = mysql_query("SELECT 1 FROM ".$word, $link);
  			$num_rows = mysql_num_rows($result);	
				if($num_rows > 0)
    			{
    				$result = mysql_query("SELECT * FROM ".$word." WHERE PID = ".$indexpaper, $link);
      			$num_rows = mysql_num_rows($result);
     				if($num_rows > 0) 
      				{
          			$attr2 = $attr."_c";
          			$update = "UPDATE  ".$word." SET ".$attr2." = ".$attr2." + 1 WHERE PID = ".$indexpaper;
          			mysqli_query($con,$update);
        			}
      			else
        			{
          			$query = "INSERT INTO ".$word." VALUES(".$indexpaper.",0,0,0,0)";
          			mysqli_query($con,$query);
          			$attr2 = $attr."_c";
         			  $update = "UPDATE ".$word." SET ".$attr2." = ".$attr2." + 1 WHERE PID = ".$indexpaper;
          			mysqli_query($con,$update);    
        			} 	
     			} 
				else
					{
						$query = "CREATE TABLE ".$word." (PID INTEGER, Title_c INTEGER ,Abstract_c INTEGER ,Author_c INTEGER,Keyword_c INTEGER)";
						mysqli_query($con,$query);
						$query = "INSERT INTO ".$word." VALUES(".$indexpaper.",0,0,0,0)";
						mysqli_query($con,$query);
						$attr2 = $attr."_c";
						$update = "UPDATE ".$word." SET ".$attr2." = ".$attr2." + 1 WHERE PID = ".$indexpaper;
						mysqli_query($con,$update);
					}
			}
	}

$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
$link = mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_db, $link);
$table = "SECOND_TABLE";
if (mysqli_connect_errno()) 
echo "Failed to connect to MySQL: " . mysqli_connect_error();

$result = mysql_query("SELECT 1 FROM ".$table, $link);
$num_rows = mysql_num_rows($result);

if($num_rows <= 0)
{
$query = "CREATE TABLE ".$table." (PID INTEGER, PAPER_NAME VARCHAR(100) ,AUTHOR VARCHAR(100) ,CITATION_COUNT INTEGER)";
$result = mysqli_query($con,$query);
}

 $query = "INSERT INTO ".$table." VALUES(".$indexpaper.",'".$title."','".$author."',$citation)";
 echo '<br>'.$query.'<br> End of paper<br><br><br><br>';
 mysqli_query($con,$query);
}
?>

