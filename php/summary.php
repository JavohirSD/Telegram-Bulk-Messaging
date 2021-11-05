<?php 
 // CORS POLICY HEADER
header('Access-Control-Allow-Origin: *');

 // READ FILE AND LOAD TO ARRAY
$content = file_get_contents("file.txt");
$array   = explode(PHP_EOL, $content);

$result_200 = 0;
$result_400 = 0;
$result_403 = 0;
$empty      = 0;
$count = count($array);

// CALCULATING RESPONSE RESULTS
for($i=0; $i<$count; $i++) {
	$result_200 = explode(" ", $array[$i])[1] == '200' ? ($result_200 + 1) : $result_200;
	$result_400 = explode(" ", $array[$i])[1] == '400' ? ($result_400 + 1) : $result_400;
	$result_403 = explode(" ", $array[$i])[1] == '403' ? ($result_403 + 1) : $result_403;
	//$empty      = explode(" ", $array[$i])[0] == null ? ($empty + 1) : $empty;
}

 // RETURNING RESULT
echo $result_200.",".$result_403.",".$result_400.",".($count-1);

// DELETE OLD SUMMARY FILE
if(file_exists('file.txt')){
	rename('file.txt', 'file_'.date('d-m-y_h-i-s').'.txt');	
} 

