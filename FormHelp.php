<?php
switch($_REQUEST[type]){

	case 'password' :
	?>
		<ul>
		<li>1) Password may have <strong>special characters</strong></li>
		<li>2) Minimum length <strong>6 characters</strong></li>
		<li>3) Maximum length <strong>20 characters</strong></li>
		</ul>
	<?php
	break;
	case 'username' :
	?>
		<ul>
		<li>1) Username may have only <strong>_ as special characters</strong></li>
		<li>2) Minimum length <strong>3 characters</strong></li>
		<li>3) Maximum length <strong>20 characters</strong></li>
		</ul>
	<?php
	break;
}
?>
	