<html>
<?php
include '/opt/lampp/htdocs/seo/vendor/autoload.php';
include '/opt/lampp/htdocs/seo/test.php';
$allowedExts = array("pdf");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
$path = "/opt/lampp/htdocs/seo/PDFS";
$len = sizeof(scandir($path)) - 1 ;
$filename = $len.".pdf";
$writepath = "/home/shravan/Desktop/PDFS text";
if ($_FILES["file"]["type"] == 'application/pdf')
{
  if ($_FILES["file"]["error"] > 0) {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
  } 
else {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      $path."/". $filename);
    }
  
} else {
  echo "Invalid file";
}

$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($path."/".$filename);  
$details = $pdf->getDetails();
$pages = $pdf->getPages();
$title = " ";
$author = " ";
foreach($pages as $page)
{
$data = $page->getText();
break;
}
foreach( $details as $property => $propval)
{
if(strtolower($property)=="title")
{
	$title = $propval;
}
if(strtolower($property)=='author')
{
$author = $propval;
}
}

$arr = getAbsKey($len);
$abstract = $arr["Abstract"];
$keywords = $arr["Keywords"];
?>
<html>
<body>
<form action = "insertdb.php" method = "post">
Title :<textarea rows = "2" cols = "50" name="Title" > <?php echo $title ?> </textarea><br>
Author : <textarea rows = "1" cols = "50" name="Author"><?php echo $author ?></textarea><br>
Abstract : <textarea  rows = "8" cols = "50" name="Abstract"><?php echo $abstract;?></textarea><br>
Keywords : <textarea rows = "2" cols = "50" name="Keywords"><?php echo $keywords;?></textarea><br>
Citation : <input type = "text" name = "Citation"/><br>
<input type = "hidden" name = "Filename" value = "<?php echo $writepath."/".$len.".txt"?>">
<input type = "hidden" name = "PID" value = "<?php echo $len ?>">
<input type = "submit" value = "submit pdf to database">
</form>
</body>
</html>

