<?php

/**
 * Example for TM::Apeform (compare with PEAR::HTML_QuickForm's "groups"
 * example).
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

require_once("Apeform.class.php");

$form = new Apeform(0, 30);

$form->templates['header'] =
    "<tr>\n<th colspan=\"2\" class=\"header\">{header}</th>\n</tr>\n";
$form->templates['input'] = "<tr>\n<th align=\"right\" valign=\"top\">{label}" .
    "</th>\n<td valign=\"top\">{error}{input}{help}</td>\n</tr>\n";
$form->templates['error'] = "<div class=\"error\">{error}</div>\n";
echo '<style type="text/css">';
echo 'table{background-color:#CC9;width:450px;}';
echo '.header{background-color:#996;color:#FFC;text-align:left;}';
echo 'th,td{padding:3px;}';
echo 'small{color:#996;}';
echo 'sup{color:#F00;}';
echo '.error{color:#F00;}';
echo '</style>';

$form->header("Tests on grouped elements");

$data['lastname'] = $form->text("<u>I</u>D", "<sup>*</sup>Name", "Mamasam");
if (!$data['lastname'])
{
    $form->error("Name is required");
}
elseif (!preg_match('/^[a-z]*$/i', $data['lastname']))
{
    $form->error("Name is letters only");
}
$data['code'] = $form->text("", "Code", "1234", 4, 5);
if (!is_numeric($data['code']))
{
    $form->error("Code must be numeric");
}

$data['phoneNo'] = $form->text("<sup>*</sup><u>T</u>elephone", "\t-\t-",
    array("513", "123", "3456"), array(3, 3, 4), array(4, 4, 5));
if (empty($data['phoneNo'][0]) || empty($data['phoneNo'][1]) ||
    empty($data['phoneNo'][2]))
{
    $form->error("Please fill the phone field");
}
elseif (!preg_match('/^\d*$/', implode("", $data['phoneNo'])))
{
    $form->error("Value must be numeric");
}

$data['ichkABC'] = $form->checkbox("<sup>*</sup>ABCD", "",
    array("<u>A</u>", "<u>B</u><br>", "<u>C</u>", "<u>D</u>"),
    array(0));
if (count($data['ichkABC']) < 2)
{
    $form->error("Please check at least two boxes");
}

$data['iradYesNo'] = $form->radio("<sup>*</sup>Yes/No", "",
    "<u>Y</u>es|<u>N</u>o");
if (!$data['iradYesNo'])
{
    $form->error("Check Yes or No");
}

$form->submit("Submit", "<sup>*</sup> denotes required field");

$handler = "var msg = '';" .
    "if (!this.elements['element1'].value)" .
      "msg += ' - Name is required\\n';" .
    "else if (!this.elements['element1'].value.match(/^[a-z]*$/i))" .
      "msg += ' - Name is letters only\\n';" .
    "if (!this.elements['element2'].value.match(/^\d*$/))" .
      "msg += ' - Code must be numeric\\n';" .
    "if (!this.elements['element3[]'][0].value ||" .
      "!this.elements['element3[]'][1].value ||" .
      "!this.elements['element3[]'][2].value)" .
      "msg += ' - Please fill the phone field\\n';" .
    "else if (!(this.elements['element3[]'][0].value +" .
      "this.elements['element3[]'][1].value +" .
      "this.elements['element3[]'][2].value).match(/^\d*$/))" .
      "msg += ' - Value must be numeric\\n';" .
    "if ((this.elements['element4[]'][0].checked +" .
      "this.elements['element4[]'][1].checked +" .
      "this.elements['element4[]'][2].checked +" .
      "this.elements['element4[]'][3].checked) < 2)" .
      "msg += ' - Please check at least two boxes\\n';" .
    "if (!this.elements['element5'][0].checked &&" .
      "!this.elements['element5'][1].checked)" .
      "msg += ' - Check Yes or No\\n';" .
    "if (msg) { alert('Invalid information entered.\\n' + msg +" .
      "'Please correct these fields.'); return false; }";
$form->addAttribute("onsubmit", $handler);

if ($form->isValid())
{
    var_dump($data);
    echo "<hr>";
}

$form->display();
