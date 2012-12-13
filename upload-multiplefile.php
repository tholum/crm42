<script type="text/javascript" src="upload_attachment/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="upload_attachment/swfobject.js"></script>
<script type="text/javascript" src="upload_attachment/jquery.uploadify.v2.1.4.min.js"></script>
<script type="text/javascript">
	$(function() {
		$('#custom_file_upload').uploadify({
					'uploader'       : 'upload_attachment/uploadify.swf',
					'script'         : 'upload_attachment/uploadify.php',
					'cancelImg'      : 'upload_attachment/cancel.png',
					'folder'         : 'uploads',
					'multi'          : true,
					'auto'           : true,
					'queueID'        : 'custom-queue',
					'queueSizeLimit' : 3,
					'simUploadLimit' : 3,
					'sizeLimit'   : 102400,
					'removeCompleted': false,
					'onSelectOnce'   : function(event,data) {
					$('#status-message').text(data.filesSelected + ' files have been added to the queue.');
					},
					'onAllComplete'  : function(event,data) {
					$('#status-message').text(data.filesUploaded + ' files uploaded, ' + data.errors + ' errors.');
					}
		});				
	});
</script>
<div id="status-message">Select some files to upload:</div>
<div id="custom-queue"></div>
<input id="custom_file_upload" type="file" name="Filedata" />