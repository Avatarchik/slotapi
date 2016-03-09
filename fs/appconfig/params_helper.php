<?php



function requestValueFromKey($key)
{
	try
	{
		$params = $_REQUEST;

	    if( $params == false ){
	        throw new Exception('$_REQUEST is not valid');
	    }

	    if(isset($params[$key]) == false){
	        throw new Exception($key. ' is not defined');
	    }    

	    return $params[$key];
	} 
	catch (PDOException $ex) 
	{
		$errInfo = $this->handleException($ex);

		// return our custom error message
		return $errInfo;
	}
}


?>