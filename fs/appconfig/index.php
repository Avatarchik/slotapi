
<?php

include 'pdo_client.php';
include_once 'params_helper.php';
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

    // get the provided app id
    $app_id = requestValueFromKey('app_id');

    // check first if the app id exists in the list of applications.
    if( !isset($applications[$app_id]) ) {
        throw new Exception('Application does not exist!');
    }
    // get app key.
    $app_key = requestValueFromKey('app_key');
    
    // get hash of app_key.
    $local_app_key = hash('sha512', $applications[$app_id]);

    // check if app keys match.
    if( $local_app_key != $app_key)
    {
    	throw new Exception('App key not valid.');
    }
     
    // get the action and format it correctly.
    $action = requestValueFromKey('action');
    $action = strtolower($action);

    // create new PDO client.
    $DB = new pdo_client();
    
    if($action == 'fetch')
    {
        // get user_id and device_id from request.
        $user_id = requestValueFromKey('user_id');
        $device_id = requestValueFromKey('device_id');

        // retrieve row for user_id from DB.
        $existing_user_id = $DB->getRowForUserId($user_id);

        // if user_id is not found.
        if(empty($existing_user_id) || $user_id == '')
        {
            // check if device_id is in DB.
            $existing_device_id = $DB->getUserIdForDeviceId($device_id);
            echo('previous user not found: '.$user_id.' checking device_id: '.$device_id);

            // if device_id is not found.
            if(empty($existing_device_id))
            {

                echo('previous device_id not found: '.$user_id.' creating new user_id from device_id: '.$device_id);

                // insert the new device_id into the db and get the new associated user_id.
                $user_id  = $DB->insertNewDeviceId($device_id);
                echo('new user id: '.$user_id);
            }
            else
            {
                // Good device id, no good user id
                echo('previous device_id found: '.$device_id);
                $user_id = $existing_device_id[0]['user_id'];
            }
        }
        else
        {
            // existing user id was found.

            // check if device_id is in DB.
            $existing_device_id = $DB->getUserIdForDeviceId($device_id);            
            if($existing_device_id != $device_id)
            {
                throw new Exception("device_id did not match user_id", 1);
            }
        }

        $result = array();
        $result['success'] = true;
        $result['user_id'] = $user_id;

    }
    else
    {
        $result = array();
        $result['success'] = false;
        $result['errormsg'] = 'No valid action';
    }

}
catch(Exception $e)
{
    //catch any exceptions and report the problem
    $result = array();
    $result['success'] = false;
    $result['errormsg'] = $e->getMessage();
}
 echo json_encode($result);
exit();