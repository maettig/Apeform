<?php

/**
 * Accesskeys example. Shows mixed use of user defined and automatically created
 * accesskey attributes.
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

require_once("Apeform.class.php");
$form = new Apeform();

$form->autoAccesskeys = true;
$form->templates['accesskey'] = '<em class="accesskey">{accesskey}</em>';

$form->header("Some example");
$form->staticText("Some example", "", "Some example");
$form->text("Some example");
$form->password("User defined <u>k</u>ey");
$form->error("Error message inherits accesskey");
$form->textarea("Other example");
$form->checkbox("Some example");
$form->checkbox("", "", "Some example");
$form->checkbox("Some example", "", "Some example");
$form->checkbox("Some example", "", "Aaa|Bbb|Ccc");
$form->radio("Some example", "", "Aaa|Bbb|Ccc");
$form->select("Some example", "", "Aaa|Bbb|Ccc");
$form->submit("Submit accesskey example");

?>

<style type="text/css">
label{
	background-color:#FEC;
	cursor:hand;
}
label:hover{
	background-color:#FD0;
}
.accesskey{
	background-color:#FD0;
	color:#900;
	font-style:normal;
	font-weight:bold;
	text-decoration:underline;
}
</style>

<?php $form->display(); ?>

<ul>
<li>In Internet Explorer and Firefox press Alt+character.</li>
<li>In Opera press Shift+Esc and then the character.</li>
</ul>
