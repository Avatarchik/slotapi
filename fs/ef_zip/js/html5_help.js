(function() {

	var uploadCount = 0;
	var totalCount = 0;

	// getElementById
	function $id(id) {
		return document.getElementById(id);
	}


	// output information
	function Output(msg) {
		var m = $id("messages");
		m.innerHTML = msg + m.innerHTML;
	}


	// file drag hover
	function FileDragHover(e) {
		e.stopPropagation();
		e.preventDefault();
		e.target.className = (e.type == "dragover" ? "hover" : "");
	}


	// file selection
	function FileSelectHandler(e) {

		// cancel event and hover styling
		FileDragHover(e);

		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;

		totalCount = files.length;

		// clear children
		clearLastUploadData($id('prettyjson'));
		clearLastUploadData($id('genjson'));
		clearElement($id('progress'));
		clearElement($id('messages'));

		// clear uploads directory
		clearUploadsDirectory();

		// process all File objects
		for (var i = 0, f; f = files[i]; i++) 
		{
			ParseFile(f);
			UploadFile(f);
		}
	}

	function clearElement(element)
	{
		while (element.firstChild)
		{
			element.removeChild(element.firstChild);
		}
	}

	function clearLastUploadData(element)
	{
		while (element.children[1]){
			element.removeChild(element.children[1]);
		}

	}

	// output file information
	function ParseFile(file) {

		Output(
			"<p>File information: <strong>" + file.name +
			"</strong> type: <strong>" + file.type +
			"</strong> size: <strong>" + file.size +
			"</strong> bytes</p>"
		);

		// display an image
		if (file.type.indexOf("image") == 0) 
		{
			var reader = new FileReader();
			reader.onload = function(e) {
				Output(
					"<p><strong>" + file.name + ":</strong><br />" +
					'<img src="' + e.target.result + '" /></p>'
				);
			}
			reader.readAsDataURL(file);
		}

		// display text
		if (file.type.indexOf("text") == 0) 
		{
			var reader = new FileReader();
			reader.onload = function(e) {
				Output(
					"<p><strong>" + file.name + ":</strong></p><pre>" +
					e.target.result.replace(/</g, "&lt;").replace(/>/g, "&gt;") +
					"</pre>"
				);
			}
			reader.readAsText(file);
		}

	}

	// upload files
	function UploadFile(file) 
	{
		var xhr = new XMLHttpRequest();
		if (xhr.upload) 
		{
			// create progress bar
			var o = $id("progress");
			var progress = o.appendChild(document.createElement("p"));
			progress.appendChild(document.createTextNode("upload " + file.name));


			// progress bar
			xhr.upload.addEventListener("progress", function(e) {
				var pc = parseInt(100 - (e.loaded / e.total * 100));
				progress.style.backgroundPosition = pc + "% 0";
			}, false);

			// file received/failed
			xhr.onreadystatechange = function(e) {
				if (xhr.readyState == 4) {
					progress.className = (xhr.status == 200 ? "success" : "failure");

					uploadCount++;

					var msg = $id("messages");
					var myupdate = msg.appendChild(document.createElement("p"));
					myupdate.appendChild(document.createTextNode(xhr.response));

					if (uploadCount == totalCount)
					{
						// reset our trackers
						uploadCount = 0;
						totalCount  = 0;

						// call our gen json script now
						genJson();
					}
				}
			};

			// start upload
			xhr.open("POST", $id("upload").action, true);
			xhr.setRequestHeader("X-FILENAME", file.name);
			xhr.send(file);

		}

	}

	// deletes any existing zips already on the server so generated json is correct
	function clearUploadsDirectory()
	{
		var xhr = new XMLHttpRequest();

		// file received/failed
		xhr.onreadystatechange = function(e) {
			if (xhr.readyState == 4) {
				progress.className = (xhr.status == 200 ? "success" : "failure");

				var responseString = xhr.response;

				// update genjson with un-prettified json
				var msg = $id("messages");
				var myupdate = msg.appendChild(document.createElement("p")).innerHTML = responseString;
			}
		};

		// start upload
		xhr.open("POST", "upload/clearUploads.php", true);
		xhr.setRequestHeader("Content-type", "application/json");
		xhr.send("[\"fetch\"]");
	}

	// gen json call
	function genJson() 
	{
		var xhr = new XMLHttpRequest();

		// file received/failed
		xhr.onreadystatechange = function(e) {
			if (xhr.readyState == 4) {
				progress.className = (xhr.status == 200 ? "success" : "failure");

				var jsonResponseString = xhr.response;

				// update genjson with un-prettified json
				var msg = $id("genjson");
				var myupdate = msg.appendChild(document.createElement("p"));
				myupdate.appendChild(document.createTextNode(jsonResponseString));

				// update prettified json
				var prettyDiv = $id("prettyjson");
				var prettyUpdate = prettyDiv.appendChild(document.createElement("p"));

				// generate pretty string
				var prettyString = syntaxHighlight(JSON.parse(jsonResponseString));
				prettyUpdate.appendChild(document.createElement('pre')).innerHTML = prettyString;
			}
		};

		// start upload
		xhr.open("POST", "gen-json/", true);
		xhr.setRequestHeader("Content-type", "application/json");
		xhr.send("[\"fetch\"]");

	}

	function syntaxHighlight(json) 
	{
	    if (typeof json != 'string') {
	         json = JSON.stringify(json, undefined, 4);
	    }
	    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
	        var cls = 'number';
	        if (/^"/.test(match)) {
	            if (/:$/.test(match)) {
	                cls = 'key';
	            } else {
	                cls = 'string';
	            }
	        } else if (/true|false/.test(match)) {
	            cls = 'boolean';
	        } else if (/null/.test(match)) {
	            cls = 'null';
	        }
	        return '<span class="' + cls + '">' + match + '</span>';
	    });
	}

	// initialize
	function Init() {

		var fileselect = $id("fileselect"),
			filedrag = $id("filedrag"),
			submitbutton = $id("submitbutton");

		// file select
		fileselect.addEventListener("change", FileSelectHandler, false);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {

			// file drop
			filedrag.addEventListener("dragover", FileDragHover, false);
			filedrag.addEventListener("dragleave", FileDragHover, false);
			filedrag.addEventListener("drop", FileSelectHandler, false);
			filedrag.style.display = "block";

			// remove submit button
			submitbutton.style.display = "none";
		}

	}

	// call initialization file
	if (window.File && window.FileList && window.FileReader) {
		Init();
	}


})();