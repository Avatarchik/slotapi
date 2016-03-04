<?php


class zip_prep
{
	public $zip_data_array;

	public function getDataWithFile($zip_name, $priority)
	{
		// create our hashmap for all the data about this zip
		$return = array();

		// remove any .DS_Store directories from the zip
		$this->removeDSStoreDirectories($zip_name);

		// set the name of our zip file
		$return['name'] = basename($zip_name);
		// calculate size as MiB instead of MB, since it's a little larger just to be safe
		$return['size'] = (filesize($zip_name) / 1000000);
		$return['priority'] = $priority;
		$return['hash'] = md5_file($zip_name);

		// create an array to hold our manifest
		$files = array();

		// open our zip for reading
		$zip = zip_open($zip_name);

		if ($zip)
		{
		  	while ($zip_entry = zip_read($zip))
		    {
		    	// get the filename from the zip
		    	$filename = zip_entry_name($zip_entry);

		    	// add the filename to our manifest
		    	$files[] = $filename;
		    }
		}
		zip_close($zip);

		$return['manifest'] = $files;

		$this->zip_data_array['files'][] = $return;
	}


	public function removeDSStoreDirectories($zip_name)
	{
		$dirPath = getcwd();
		$scriptPath = $dirPath . "/ef_zip/bash/strip_dir.sh";

		// strip any hidden mac files out of our zips
		shell_exec($scriptPath . " " . $zip_name);

		// NOTE: results of this process will be logged to logs/logfile 
	}
}

?>