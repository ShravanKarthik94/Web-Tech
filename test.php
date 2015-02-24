<?php
function getAbsKey($filename)
{
$filename = $filename.".pdf";
include '/opt/lampp/htdocs/seo/vendor/autoload.php';
$path = "/opt/lampp/htdocs/seo/PDFS";
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($path."/".$filename);
$pages = $pdf->getPages();
foreach($pages as $page)
{
$data = $page->getText();
break;
}
$path = "/home/shravan/Desktop/PDFS text/112.txt";
$myfile = fopen($path, "w") or die("Unable to open file!");
fwrite($myfile,$data);
fclose($myfile);
$myfile = fopen($path, "r");
$abs = " ";
$keywords = " ";
$abs = " ";
$keywords = " ";
$startabs =0;
$endabs =0;
$startkeywords =0;
$endkeywords =0;
$newlinecount =0;
$checknext =1;
$prev = " ";
$index =0;
while(! feof($myfile))
{
$linedat =  fgets($myfile);

if($endabs == 1 && $endkeywords ==1)
{
break ;
}
if(strlen(trim($linedat)) <= 0)
{
  if($startabs == 1)
  {
		$endabs = 1;
  }
  if($startkeywords ==1)
	{
		$endkeywords = 1;
	}
 continue;
}
$linedatarray = explode(" ",$linedat);
foreach($linedatarray as $word)
{
$word = preg_replace("/[^A-Za-z0-9]/",'',$word);
if(strtolower($word) == 'keywords' || strtolower($word) == 'keyword' )
{
  $startkeywords =1;
  if($startabs == 1)
   {
    $endabs =1;
   }
continue;
}

else if(strtolower($word) == 'abstract')
{
 $startabs = 1;
 if($startkeywords ==1)
  {
    $endkeywords =1;
  }
continue;
}
if(strtolower($word) == 'introduction')
{
 $endkeywords =1;
 $endabs =1;
}

if($startabs == 1 && $endabs ==0)
{
  $abs = $abs." ".$word;
}

else if($startkeywords == 1 && $endkeywords == 0)
{
  $keywords = $keywords. " ". $word;
}
}
}
fclose($myfile);
$arraytoret = array("Abstract"=>$abs,"Keywords"=>$keywords);
unlink($path);
return($arraytoret);
}
?>

