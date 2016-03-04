<?php

// the location of the zips we're going to generate json for
$zip_directory = "../user_uploads/";

// get the contents of the 'zips' directory
$filenames = scandir($zip_directory);

// if we have files
if (isset($filenames))
{
	// remove any non .zip files from our array
	foreach ($filenames as $index => $name) 
	{
		// if the name does not contain 'zip' then remove it
		if (strpos($name, 'zip') === false)
		{
			unset($filenames[$index]);
		}
	}

	include_once 'zip_data.php';

	$zip_handler = new zip_prep();

	foreach ($filenames as $zip_name) 
	{
		$zip_handler->getDataWithFile($zip_directory . $zip_name, "1");
	}

	echo json_encode($zip_handler->zip_data_array);
}
else
{
	echo "<br />No files found in zip directory... bailing.</br>";
}

?>