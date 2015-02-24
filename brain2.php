<html>
<style>
a {
    margin-left: 100px;
}
 img {
  float: left;
  clear: none;
}
</style>

<body>
<br>
<div>
<img src = "wt logo.jpg" width = "80" height = "80"/>

<form action = "brain2.php" method = "post">
<input type = "text" name = "query" size = "50">
<input type = "submit" name ="search" value = "Search!">
</div>
<br>
<br><br>
</form>
</body>
</html>

<?php
function value($count, $const)
{
	if($count == 1)
		{
				return ($const);
		}
	else if($count == 2)
		{
			return ($const * 1.5);
		} 
	else if($count > 2)
		{
			return ($const * 1.5 + sqrt($count)); 
		}
}

function PageRank( $attribute, $count, $const)
{
	if($attribute == 'Title_c')
		{
			return (value($count,$const));
		}
	else if($attribute == 'Author_c')
		{
			return (value($count,$const));
		}
	else if($attribute == 'Abstract_c')
		{	
			return (value($count,$const));
		}																																								else if($attribute == 'Keyword_c')
		{
			return (value($count,$const));
		}
		else if($attribute == 'Citation')
		{
			return sqrt($count);
		}
}
$weight = 20;
error_reporting(E_ERROR | E_PARSE);
$anyresult = 0;
$mysql_host = "localhost";
$mysql_user = "root";
$mysql_password = "";
$mysql_db = "ASEO";
$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
$link = mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_db, $link);

$query = $_POST["query"];
$pageranks = array();
$titles = array();
$listofwords = explode(" ",$query);
foreach ($listofwords as $token)
{
  $token = preg_replace("/[^A-Za-z0-9]/",'',$token);
  $token = strtolower($token);
  $query = mysql_query("SELECT 1 FROM ".$token, $link);
	$num_rows = mysql_num_rows($query);
	if($num_rows > 0)
	{
		$anyresult = 1;
		$result = mysqli_query($con,"SELECT * from ".$token);
		while($row = mysqli_fetch_array($result))
		{
		$result2 = mysqli_query($con,"SELECT * from SECOND_TABLE WHERE PID = ".$row['PID']);
		$row2 = mysqli_fetch_array($result2);
		if(!array_key_exists($row['PID'],$pageranks))
		{
			$titles[$row2['PID']] = $row2['PAPER_NAME'];
			$pageranks[$row['PID']] = 0;
		}
			$pageranks[$row['PID']] = $pageranks[$row['PID']] + PageRank('Title_c',$row['Title_c'],$weight);
			$pageranks[$row['PID']] = $pageranks[$row['PID']] + PageRank('Author_c',$row['Author_c'],$weight);
			$pageranks[$row['PID']] = $pageranks[$row['PID']] + PageRank('Abstract_c',$row['Abstract_c'],$weight);
			$pageranks[$row['PID']] = $pageranks[$row['PID']] + PageRank('Keyword_c',$row['Keyword_c'],$weight);
			$pageranks[$row['PID']] = $pageranks[$row['PID']] + PageRank('Citation',$row2['CITATION_COUNT'],$weight);
		}
	}
}

#print_r($pageranks);
#echo "<br><br>";
#print_r($titles);
arsort($pageranks);

#echo "<br><br>";
echo "<html>";
foreach($pageranks as $res => $val)
{
$pdfpath = "PDFS/".$res.".pdf";
echo '<a href="'.$pdfpath.'">'.$titles[$res].'</a><br><br>';
}
echo "</html>";
?>
