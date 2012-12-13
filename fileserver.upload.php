<!DOCTYPE html>
<html>
<head>
	<title>jQuery File Upload without Flash</title>

	<link rel="stylesheet" type="text/css" media="all"
		href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/ui-darkness/jquery-ui.css"/>

	<script type="text/javascript" 
		src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js">
	</script>

	<script type="text/javascript" 
		src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js">
	</script>
        <script type="text/javascript" >
    // called before upload submit
function startUpload() {
	$('#progress-bar')
	.progressbar()
	.data('uploadCompleted', false);

	window.setTimeout(uploadProgress, 1000);
}

// called every one second. Server side url uploadProgress.do returns
// text: <bytes uploaded so far>/<file size>, e.g.: '1325/33387'
function uploadProgress() {
	if ($('#progress-bar').data('uploadCompleted')) {
		$('#progress-bar').progressbar('destroy');
		return;
	}

	$.ajax({
		url: 'uploadProgress.do',
		cache: false,  // this is important, otherwise there's no progress in IE!
		success: function(response) {
			var s = response.split('/');
			$('#progress-bar').progressbar(
			   'option', 
			   'value',
			   100*s[0]/s[1]
			);
		}
	});

	window.setTimeout(uploadProgress, 1000);
}

// called when file upload is done. Only useful for ajax type uploads
function completeUpload() {
	$('#progress-bar').data('uploadCompleted', true);
}    
    </script>
</head>

<body>
    <form action="fileserver.upload.php" 
		  enctype="multipart/form-data" 
		  method="POST" onSubmit="startUpload();">
		  <input type="file" name="theUploadFile">
		  <input type="submit" name="submit" value="Upload">
	</form>

	<div id="progress-bar"></div>
</body>
</html>