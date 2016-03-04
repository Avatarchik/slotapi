<?php

define('DIR_UPLOADS', $_SERVER['DOCUMENT_ROOT'] . '/ef_zip/user_uploads');

echo "Clearing uploads directory... <br />";

foreach (new DirectoryIterator(DIR_UPLOADS) as $fileInfo) 
{
	if (!$fileInfo->isDot())
	{
		$filename = $fileInfo->getFilename();

		if ($filename != ".keep")
		{
			echo "<br>Deleting existing file: $filename";

			unlink($fileInfo->getPathname());
		}
	}
}

?>