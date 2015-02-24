<?php
$handle = fopen("/home/shravan/inputfile.txt", "r");
$arr = array();
if ($handle) {
	$index =1;
    while (($buffer = fgets($handle, 4096)) !== false) {
        $arr[$index] = $buffer;
	$index = $index + 1; 
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
print_r($arr);
}
?>
