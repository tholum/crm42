<?php
require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once('class/class.project.php');
require_once('class/class.note.php');
require_once('class/class.tasks.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.WorkOrder.php');
require_once('app_code/class.Event_Contacts.php');
require_once('app_code/zipcode.class.php');
$page = new basic_page();
$page->auth->Checklogin();
$workorder = new WorkOrder();
$order_id = $_REQUEST["order_id"];
$label = $workorder->get_shipping_label($order_id);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
    <HEAD>
        <TITLE>View/Print Label</TITLE>
        <META content="text/html; charset=utf-8" http-equiv=Content-Type>
        <STYLE>.small_text { FONT-SIZE: 80%}.large_text {           FONT-SIZE: 115%}</STYLE>
        <META name=GENERATOR content="MSHTML 8.00.7600.16588">
    </HEAD>
    <BODY bgColor=#ffffff>
        <TABLE border=0 cellSpacing=0 cellPadding=0 width=600></TABLE>
            <TBODY>  <TR>    <TD height=410 vAlign=top align=left><B class=large_text>View/Print       Label</B> &nbsp;<BR>&nbsp;<BR>      <OL class=small_text>        <LI><B>Print the label:</B> &nbsp; Select Print from the File menu in         this browser window to print the label below.<BR><BR>        <LI><B>Fold the printed label at the dotted line.</B> &nbsp; Place the         label in a UPS Shipping Pouch. If you do not have a pouch, affix the         folded label using clear plastic shipping tape over the entire         label.<BR><BR>        <LI><B>GETTING YOUR SHIPMENT TO UPS<BR>Customers without a Daily         Pickup</B>        <UL>          <LI>Take this package to any location of The UPS Store&reg;, UPS Drop Box,           UPS Customer Center, UPS Alliances (Office Depot&reg; or Staples&reg;) or           Authorized Shipping Outlet near you or visit <A           href="http://www.ups.com/content/us/en/index.jsx">www.ups.com/content/us/en/index.jsx</A>           and select Drop Off.          <LI>Air shipments (including Worldwide Express and Expedited) can be           picked up or dropped off. To schedule a pickup, or to find a drop-off           location, select the Pickup or Drop-off icon from the UPS tool bar.           </LI></UL><BR><B>Customers with a Daily Pickup</B>        <UL>          <LI>Your driver will pickup your shipment(s) as usual.     </LI></UL></LI></OL></TD></TR></TBODY></TABLE><TABLE border=0 cellSpacing=0 cellPadding=0 width=600>  <TBODY>  <TR>    <TD class=small_text vAlign=top align=left>&nbsp;&nbsp;&nbsp; <A       name=foldHere>FOLD HERE</A></TD></TR>  <TR>    <TD vAlign=top align=left>      <HR>    </TD></TR></TBODY></TABLE><TABLE>  <TBODY>  <TR>    <TD height=10>&nbsp; </TD></TR></TBODY></TABLE><TABLE border=0 cellSpacing=0 cellPadding=0 width=650>  <TBODY>  <TR>    <TD vAlign=top align=left>

                        <img src="data:image/gif;base64,<?php echo $label; ?>" width="651px" height="392px"/>
 </TD></TR></TBODY></TABLE>

<?php 
$hvr = $workorder->get_hvr_label($order_id);
if( $hvr != '' ){
    echo '<p style="page-break-before: always"></p>';
    echo base64_decode($hvr);
}
?>

</BODY>
</HTML>