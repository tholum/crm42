<?php
require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once('app_code/class.Event_Contacts.php');
$page = new basic_page();
$em = new Event_Contacts();
$em->SetUserID($_SESSION['user_id']);
$contact_id = $vars['contact_id'];

?>

<table style="width: 100%"><tr><td style="width: 80%;">
<div id="content_column_header">
	Edit Contact
</div>
<div class="contact_form cf_editor">
	<div><?php 
        echo $em->edit_contact_ui($contact_id);
?></div>
</div>
  <hr class="cf_break">
  <a style="font-weight: bold;"  onclick="<?php
  echo $page->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $em->get_company_contact_id($contact_id) )); ?>"  >&laquo; Back To Contact</a>
            







        </td><td>
<?php
// **********************Closes the Content Column and begins Info Column ****************************//

// **********************Start code for Info Column ****************************//
?>
<div class="form_main">
</div>
        </td></tr></table>


