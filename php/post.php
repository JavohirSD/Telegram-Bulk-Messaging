<?php
// CORS POLICY HEADER
header('Access-Control-Allow-Origin: *');

// CONFIGURATIONS
$servername  = "localhost";
$username    = "root";
$password    = "";
$database    = "mtm_db";
$user_table  = "users1";
$chat_id_row = "telegram_id";
$bot_token   = "2010521012:AAGoq8e2KnSXzHxthUfdXlLujL47ZiTkWH4";



// READING MESSAGE DETAILS FROM $_POST
$message  = $_POST['message']; 
$method   = $_POST['method'];
$file     = str_replace(' ','',$_POST['url']);
$keyboard = strlen($_POST['keyboard']) < 1 ? '{}' : $_POST['keyboard'];



 // DATABASE CONNECTION
try {
  $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
 echo "Connection failed: " . $e->getMessage();
}


 // RETURN COUNT OF TELEGRAM USERS IN TABLE
if(isset($_POST['getCount'])) {
    $query  = "SELECT COUNT(id) as total_users FROM $user_table";
    $result = $conn->query($query)->fetchAll();
    echo json_encode($result); 
    exit;
}



 // SELECT LIMITED USERS FROM TABLE
$query  = "SELECT $chat_id_row FROM $user_table WHERE status=1 LIMIT 30 OFFSET ".$_GET['offset'];
$result = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
 


// DETECTING APPROPRIATE ARGUMENT FOR METHOD
$argument = "";
switch ($method) {
  case "sendMessage":
    $argument = 'text';
    break;
  case "sendPhoto":
    $argument = 'photo';
    break;
  case "sendVideo":
    $argument = 'video';
    break;
  case "sendAudio":
    $argument = 'audio';
    break;
  case "sendDocument":
    $argument = 'document';
    break;
  default:
    exit('Wrong argument or method');
}



 // GENERATING POST FIELDS
$messages = [];
for($i=0; $i<count($result); $i++){
    if($method == 'sendMessage') {
     $messages[] = [
        "chat_id"      => $result[$i][$chat_id_row],
        "text"         => $message,
        "parse_mode"   => 'HTML',
        "reply_markup" => $keyboard
    ];
  } else {
     $messages[] = [
        "chat_id"      => $result[$i][$chat_id_row],
        'caption'      => $message,
         $argument     => $file,
        "parse_mode"   => 'HTML',
        "reply_markup" => $keyboard
    ];
  }
}
  


//set the url
$url = 'https://api.telegram.org/bot'.$bot_token.'/'.$method;
//$url = 'http://bulk.loc/php/telegram.php';
 


//create the array of cURL handles and add to a multi_curl
$mh = curl_multi_init();
foreach ($messages as $key => $message) {
    $chs[$key] = curl_init($url);
    curl_setopt($chs[$key], CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chs[$key], CURLOPT_POST, true);
    curl_setopt($chs[$key], CURLOPT_POSTFIELDS, $messages[$key]);
    curl_multi_add_handle($mh, $chs[$key]);
}



//running the requests
$running = null;
do {
  curl_multi_exec($mh, $running);
} while ($running);




//getting the responses
foreach(array_keys($chs) as $key){
    $error = curl_error($chs[$key]);
    $last_effective_URL = curl_getinfo($chs[$key], CURLINFO_EFFECTIVE_URL);
    $time = curl_getinfo($chs[$key], CURLINFO_TOTAL_TIME);
    $response = json_decode(curl_multi_getcontent($chs[$key]),true);  // get results
    $response = $response['ok'] ? '200' :$response['error_code'];
    if (!empty($error)) {
      //echo "The request $key return a error: $error";
     file_put_contents("errors.txt", $messages[$key]['chat_id'].'----'.print_r($error,true).PHP_EOL, FILE_APPEND);
    }
    else {
     file_put_contents("file.txt", $messages[$key]['chat_id'].' '.$response.PHP_EOL, FILE_APPEND);
     // echo "The request to '$last_effective_URL' returned '$response' in $time seconds.";
    }

    curl_multi_remove_handle($mh, $chs[$key]);
}

// close current handler
curl_multi_close($mh);

// close the database connection
$conn = null;
?>