<?php
ob_start();
$order = $_REQUEST['order'];
$case_id = $_REQUEST['case_id'];

    
    require_once('class/class.eapi_api.php');

    require_once('class/global.config.php');
    require_once('class/class.dynamicpage.php');
    require_once('class/config.inc.php');
    require_once('class/class.email_client.php');
    require_once('class/class.flags.php');
    require_once('class/class.GlobalTask.php');
    require_once('class/class.smtp.php');
    require_once('class/class.casecreation.php');
    require_once('class/class.FctSearchScreen.php');
    require_once('class/class.eapi_order.php');
    require_once('class/class.eapi_account.php');
    require_once('class/class.cases.php');
    require_once('class/class.note.php');
    require_once('class/class.imap.php');
    require_once('class/class.user.php');
    require_once('class/class.email_client.php');
    require_once('class/class.display.php');
if( $order == '' && $case_id != '' ){
    $db=new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
    $ca = $db->fetch_assoc($db->query("SELECT OrderNumber FROM cases WHERE case_id = '$case_id'"));
    $order = $ca['OrderNumber'];
}
    $eapi_api = new eapi_api();
    $json = $eapi_api->order_images($order);
    $data = json_decode($json);
$path = str_replace('\\', '/', $data->Path);
ob_end_clean();
?>
<a href="file:<?php echo $path; ?>" >Click Here if the folder does not open</a><br/>
Please note that if The link does not work, your system might not have this site as a
trusted site, Please contact your network administrator to configure this site in the 
trusted zone
<?php echo $path; ?><br/>

<script>
    window.open('file:<?php echo $path; ?>');
    window.close();
</script>

