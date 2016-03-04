<?php



class upload
{

	protected $file;
	protected $uploadFilename;
	protected $uploadDirectory;
	protected $fileSize;
	protected $fileTmpName;
	protected $fileType;
	protected $fileExtension;

	protected $saveFilePath;

	protected $allowedExtensions;

	function __construct()
	{
		// setup initial properties
		$this->file 	= $_FILES['file'];

		// setup file properties
		if (isset($this->file) && !empty($this->file))
		{	
			$this->uploadFilename 	= $this->file['name'];
			$this->fileSize 	  	= $this->file['size'];
			$this->fileTmpName    	= $this->file['tmp_name'];
			$this->fileType       	= $this->file['type'];
		}
		else
		{
			throw new Exception('Received empty data. No file found to upload.');
		}
		
		// pull the file extension
		$tmp = explode('.', $this->uploadFilename);
		$this->fileExtension  		= strtolower(end($tmp));
		
	}



	public function uploadFile()
	{
		$retVal['status'] = false;

		// set our type limits
		$this->allowedExtensions 	= array("png", "zip", "mov", "mp4", "mpv", "3gp");

		// setup destination path
		$this->uploadDirectory      = DIR_UPLOADS;

		// clear the upload directory
		$this->clearFolder($this->uploadDirectory);

		// try to upload the file
		$success = $this->startUpload();

		if ($success === TRUE)
		{
			// either file uploaded and was not a profile image or it was a profile image and uploaded and was set successfully
			// so return the image name so the app can have it locally. (NOTE: this wipes the error alert set above)
			$retVal['status'] = true;
		}
		
		return $retVal;
	}




	//-------------------------------------------------------------------------------
	//							Upload Process Methods
	//-------------------------------------------------------------------------------

	private function startUpload()
	{
		// see if there are any errors
		$this->checkForUploadErrors();

		// validate the type received is correct
		$this->checkFileExtension();

		// check the filesize
		$this->checkFileSize();

		// create the directory for the user if it does not exist
		$this->createUserDirectoryIfNotExists();

		// generate a local file name
		$this->createLocalFileName();

		// verify that the file is an uploaded file
		$this->verifyIsUploadedFile();

		// save the image to the appropriate folder
		$success = $this->saveFileToDisk();

		// return TRUE/FALSE
		return $success;
	}

	private function checkForUploadErrors()
	{
		if ($this->file['error'] != 0)
		{
			throw new Exception($this->file['error']);
		}
	}

	private function checkFileExtension()
	{
		if (!(in_array($this->fileExtension, $this->allowedExtensions)))
		{
			throw new Exception('Unsupported file type.');
		}
	}

	private function checkFileSize()
	{
		$max_mb_size = 30 * 1024 * 1024;

		if($this->fileSize > $max_mb_size)
		{
			throw new Exception('The image filesize must be under 30mb.');
		}
	}

	private function createUserDirectoryIfNotExists()
	{
		if (!file_exists($this->uploadDirectory)) 
		{
    		mkdir($this->uploadDirectory, 0755, true);
		}
	}

	private function createLocalFileName()
	{
		// generate new filename
		// $now = time();
		// while(file_exists($this->uploadFilename = $now.'-'.$this->uid.'.'.$this->fileExtension))
		// {
		// 	$now++;
		// }

		$this->saveFilePath = $this->uploadDirectory. "/" .$this->uploadFilename;
	}

    private function clearFolder($path)
    {
        if(is_file($path)){
            return @unlink($path);
        }
        elseif(is_dir($path))
        {
            $scan = glob(rtrim($path,'/').'/*');
            foreach($scan as $index=>$npath)
            {
                $this->clearFolder($npath);
                @rmdir($npath);
            }
        }
    }

	private function verifyIsUploadedFile()
	{
		if (! is_uploaded_file($this->file['tmp_name']))
		{
			throw new Exception('The file failed to upload.');
		}
	}


	private function saveFileToDisk()
	{
		if (move_uploaded_file($this->file['tmp_name'], $this->saveFilePath))
		{
			return TRUE;	 
		}

		throw new Exception('File failed to upload. Please retry.');
	}

}






?>