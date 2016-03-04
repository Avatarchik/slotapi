<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>HTML5 File Drag &amp; Drop API</title>
<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />
</head>
<body>

<h2>Drop zip files below</h2>

<p>This uploads zip files and creates json for ios DLC api return.</p>


<form id="upload" action="upload/" method="POST" enctype="multipart/form-data">

<fieldset>
<legend>HTML File Upload</legend>

<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="50000000" />

<div>
  <label for="fileselect">Files to upload:</label>
  <input type="file" id="fileselect" name="fileselect[]" multiple="multiple" />
  <div id="filedrag">or drop files here</div>
</div>

<div id="submitbutton">
  <button type="submit">Upload Files</button>
</div>

</fieldset>

</form>

<div id="progress"></div>

<div id="messages">
<p>Status Messages</p>
</div>

<div id="prettyjson">
<p>Prettyfied json - (note: use generated json below to give to the api, this is just for review..)</p>
</div>

<div id="genjson">
<p>Generated Json</p>
</div>

<script src="js/html5_help.js"></script>
</body>
</html>