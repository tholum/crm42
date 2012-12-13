<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Read File</title>
</head>

<body>
<?php
  echo file_get_contents("/tmp/timsdoc.txt");
  print mime_content_type("/tmp/timsdoc.txt");
?>
</body>
</html>
