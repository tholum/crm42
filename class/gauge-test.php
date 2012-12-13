<?php
$size = 175;
$risks  = 'gauge.php?'.http_build_query(array('value'=>15,
                                              'text'=>'RISKS',
                                              'size'=>$size,
                                              'green'=>33,
                                              'yellow'=>67,
                                              'min'=>0,
                                              'max'=>100,
                                              'span'=>260));

$issues = 'gauge.php?'.http_build_query(array('value'=>46,
                                              'text'=>'ISSUES',
                                              'size'=>$size,
                                              'green'=>33,
                                              'yellow'=>67,
                                              'min'=>0,
                                              'max'=>100,
                                              'span'=>180));

$escalations = 'gauge.php?'.http_build_query(array('value'=>8,
                                                  'text'=>'ESCALATIONS',
                                                  'size'=>$size,
                                                  'green'=>33,
                                                  'yellow'=>67,
                                                  'min'=>0,
                                                  'max'=>100,
                                                  'span'=>260));

$actions = 'gauge.php?'.http_build_query(array('value'=>41,
                                              'text'=>'ACTIONS',
                                              'size'=>$size,
                                              'green'=>50,
                                              'yellow'=>80,
                                              'min'=>0,
                                              'max'=>100,
                                              'span'=>260));
                                              
$budget  = 'gauge.php?'.http_build_query(array('value'=>23,
                                              'text'=>'BUDGET',
                                              'size'=>$size,
                                              'green'=>80,
                                              'yellow'=>90,
                                              'min'=>0,
                                              'max'=>100,
                                              'span'=>260));

$hours = 'gauge.php?'.http_build_query(array('value'=>31,
                                              'text'=>'BUDGET HOURS',
                                              'size'=>$size,
                                              'green'=>80,
                                              'yellow'=>90,
                                              'min'=>0,
                                              'max'=>100,
                                              'span'=>260));

$sr_ready = 'gauge.php?'.http_build_query(array('value'=>4,
                                                  'text'=>'SR READINESS',
                                                  'size'=>$size,
                                                  'green'=>10,
                                                  'yellow'=>50,
                                                  'min'=>0,
                                                  'max'=>100,
                                                  'span'=>260));

$pr_ready = 'gauge.php?'.http_build_query(array('value'=>41,
                                              'text'=>'PR READINESS',
                                              'size'=>$size,
                                              'green'=>10,
                                              'yellow'=>50,
                                              'min'=>0,
                                              'max'=>100,
                                              'span'=>260));

?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
  <title>Gauge Testing</title>
</head>

<body>
<table border='0' cellspacing='0' cellpadding='2' align='center'>
  <tr>
    <td><a href="#"><img src='<?php echo $risks; ?>' border='0' /></a></td>
    <td><a href="#"><img src='<?php echo $issues; ?>' border='0' /></a></td>
    <td><a href="#"><img src='<?php echo $escalations; ?>' border='0' /></a></td>
    <td><a href="#"><img src='<?php echo $actions; ?>' border='0' /></a></td>
  <tr>
  <tr>
    <td><a href="#"><img src='<?php echo $budget; ?>' border='0' /></a></td>
    <td><a href="#"><img src='<?php echo $hours; ?>' border='0' /></a></td>
    <td><a href="#"><img src='<?php echo $sr_ready; ?>' border='0' /></a></td>
    <td><a href="#"><img src='<?php echo 'gauge.php?value=41&text=SEW&size='.$size; ?>' border='0' /></a></td>
  <tr>
</table>

</body>
</html>
