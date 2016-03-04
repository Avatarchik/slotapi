<?

/*
	The landing page for uploading files. We include upload.php which handles the actual upload.

*/

include_once 'handler.php';

$uploadHandler = new Handler();
$uploadHandler->handleUpload();







// include_once 'upload.php';

// $return = array();

// $return['files'] = $_FILES;
// $return['post'] = $_POST;
// $return['get'] = $_GET;
// $return['server'] = $_SERVER;
// $return['request'] = $_REQUEST;


// $upload_handler = new upload();
// $result = $upload_handler->uploadFile();

// $return['result'] = $result;

// $json = json_encode($return);

// file_put_contents('log.txt', $json.PHP_EOL, FILE_APPEND);

?>