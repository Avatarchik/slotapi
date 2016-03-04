<?php

define('DIR_UPLOADS', $_SERVER['DOCUMENT_ROOT'] . '/ef_zip/user_uploads');

class Handler
{


	function handleUpload()
	{
		$fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);

		if ($fn) 
		{
			$this->ajaxUpload($fn);
		}
		else 
		{
			$this->formUpload();
		}
	}

	function ajaxUpload($filename)
	{
		// AJAX call
		file_put_contents(
			DIR_UPLOADS . "/" . $filename,
			file_get_contents('php://input')
		);
		echo "$filename uploaded";
	}

	function formUpload()
	{
		// form submit
		$files = isset($_FILES['fileselect']) ?: NULL;

		print_r($_FILES);

		if (isset($files))
		{
			foreach ($files['error'] as $id => $err) 
			{
				if ($err == UPLOAD_ERR_OK) 
				{
					$filename = $files['name'][$id];
					move_uploaded_file(
						$files['tmp_name'][$id],
						DIR_UPLOADS . "/" . $filename
					);
					echo "<p>File $filename uploaded.</p>";
				}
				else
				{
					echo "<p>NO BUENO</p>";
				}
			}
		}
		else
		{
			echo "<p>NO FILES FOUND</p>";
		}
	}


	function clearUploadsDir()
	{
		foreach (new DirectoryIterator(DIR_UPLOADS) as $fileInfo) 
		{
			if (!$fileInfo->isDot())
			{
				unlink($fileInfo->getPathname());
			}
		}
	}
}

?>