
<?php

include 'pdo_client.php';

// Define path to data folder
define('CONFIG_DATA_PATH', realpath(dirname(__FILE__).'/data'));
 
//Define our id-key pairs
$applications = array(
    'APP001' => '28e336ac6c9423d946ba02d19c6a2632', //randomly generated app key 
);

//include our models
include_once 'models/TodoItem.php';
 
//wrap the whole thing in a try-catch block to catch any wayward exceptions!
try {
    // get request params.
    $params = $_REQUEST;

    // check if the params exists.
    if( $params == false ){
        throw new Exception('Params is not valid');
    }

    // get the provided app id
    $app_id = $params['app_id'];

    // check first if the app id exists in the list of applications.
    if( !isset($applications[$app_id]) ) {
        throw new Exception('Application does not exist!');
    }

    // get app key.
    $app_key = $params['app_key'];

    // get hash of app_key.
    $local_app_key = hash('sha512', $applications[$app_id]);

    // check if app keys match.
    if( $local_app_key != $app_key)
    {
    	throw new Exception('App key not valid.');
    }
     
    //check if the action exists in the controller. if not, throw an exception.
    if(isset($params['action']) == false){
        throw new Exception('Action is not defined');
    }    

    // get the action and format it correctly so all the
    // letters are not capitalized, and append 'Action'
    $action = strtolower($params['action']).'Action';

    $result = array();

    if($action == 'fetch')
    {
        // grab udid or user id
        // check if user id exists
        // if not, create a user id from udid

        // grab config
        // return config and user id.
    }

//     //cast it into an array
//     $params = (array) $params;
//     // $params = $_REQUEST;
     
//     //get the controller and format it correctly so the first
//     //letter is always capitalized
//     $controller = ucfirst(strtolower($params['controller']));
     
//     //get the action and format it correctly so all the
//     //letters are not capitalized, and append 'Action'
//     $action = strtolower($params['action']).'Action';
 
//     //check if the controller exists. if not, throw an exception
//     if( file_exists("controllers/{$controller}.php") ) {
//         include_once "controllers/{$controller}.php";
//     } else {
//         throw new Exception('Controller is not found.');
//     }
     
//     //create a new instance of the controller, and pass
//     //it the parameters from the request
//     $controller = new $controller($params);
     
//     //check if the action exists in the controller. if not, throw an exception.
//     if( method_exists($controller, $action) === false ) {
//         throw new Exception('Action is not found.');
//     }
     
//     //execute the action
//     $result['data'] = $controller->$action();
//     $result['success'] = true;
     
// } catch( Exception $e ) {
//     //catch any exceptions and report the problem
//     $result = array();
//     $result['success'] = false;
//     $result['errormsg'] = $e->getMessage();
// }
 
// //echo the result of the API call
// echo json_encode($result);
// exit();
}
catch(Exception $e)
{

}
exit();