<?php

/**
 * Example for TM::Apeform (compare with PEAR::HTML_QuickForm's "elements"
 * example).
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

require_once("Apeform.class.php");
$form = new Apeform(0, 20);

$form->templates['header'] =
    "<tr>\n<th colspan=\"2\" class=\"header\">{header}</th>\n</tr>\n";
$form->templates['input'] = "<tr>\n<th align=\"right\" valign=\"top\">{label}" .
    "</th>\n<td valign=\"top\">{error}{input}{help}</td>\n</tr>\n";
$form->templates['error'] = "<div class=\"error\">{error}</div>\n";
echo '<style type="text/css">';
echo '.header{background-color:#CCC;text-align:left;}';
echo 'sup{color:#F00;}';
echo '.error{color:#F00;}';
echo '</style>';

$form->header("Normal Elements");
$data['ihidTest'] = $form->hidden("hiddenField");
$data['itxtTest'] = $form->text("<sup>*</sup>Test <u>T</u>ext", "",
    "Test Text Box");
if (!$data['itxtTest'])
{
    $form->error("Test Text is a required field");
}
$data['itxaTest'] = $form->textarea("<sup>*</sup>Test T<u>e</u>xtArea", "",
    "Hello World");
if (!$data['itxaTest'])
{
    $form->error("Test TextArea is a required field");
}
elseif (strlen($data['itxaTest']) < 5)
{
    $form->error("Test TextArea must be at least 5 characters");
}
$data['ipwdTest'] = $form->password("Test <u>P</u>assword");
if ($data['ipwdTest'] &&
    (strlen($data['ipwdTest']) < 8 || strlen($data['ipwdTest']) > 10))
{
    $form->error("Password must be between 8 to 10 characters");
}

// $data['ifilTest'] = $form->file("File");
$data['ichkTest'] = $form->checkbox("Test CheckBox", "",
    "C<u>h</u>eck the box",
    "C<u>h</u>eck the box");
$data['iradTest'] = $form->radio("Test Radio Buttons", "",
    array(
        "Check the radio button #<u>1</u><br>",
        "Check the radio button #<u>2</u>"),
    0);
$data['ibtnTest'] = $form->submit("Test Button");
$data['isubTest'] = $form->submit("Test Submit");
$data['iimgTest'] = $form->image("http://pear.php.net/gifs/pear-icon.gif");
$data['iselTest'] = $form->select("Test Se<u>l</u>ect", "", "A|B|C|D", "B", 5);

$form->header("Custom Elements");
$data['dateTest1'] = $form->text("<u>D</u>ate1", "", "11.01.2003", 0, 11);
$data['dateTest2'] = $form->text("<u>D</u>ate2", "", "01. Januar 2001 00:00",
    22);
$data['dateTest3'] = $form->staticText("Today is", "", date("l, d M Y"));

$options = array(
    "Pop / Belle & Sebastian",
    "Pop / Elliot Smith",
    "Pop / Beck",
    "Rock / Noir Desir",
    "Rock / Violent Femmes",
    "Classical / Wagner",
    "Classical / Mozart",
    "Classical / Beethoven");
$data['ihsTest'] = $form->select("Hierarchical select", "", $options);

$data['iadvChk'] = $form->checkbox("Advanced checkbox",
    "This is a standard checkbox.", array('on' => "Check the box"));

$data['iautoComp'] = $form->text("Your favourite fruit",
    "This is a standard text element.", "", 0, 30);

$form->header("Grouped Elements");

$data['name'] = $form->text("Na<u>m</u>e (last, first)", "\t, ", array("Daniel", "Adam"), 0, array(30, 20));
$data['phoneNo'] = $form->text("Teleph<u>o</u>ne", "\t-\t-",
    array("513", "123", "3456"), array(3, 3, 4), array(4, 4, 5));
$data['iradYesNo'] = $form->radio("Yes/No", "",
    "<u>Y</u>es|<u>N</u>o",
    "<u>Y</u>es");
$data['ichkABC'] = $form->checkbox("ABC", "",
    "<u>A</u><br>|<u>B</u><br>|<u>C</u>",
    "<u>A</u><br>|<u>B</u><br>");

$form->submit("Submit|Test Button", "<sup>*</sup> denotes required field");

if ($form->isValid())
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    echo "<hr>";
}

$form->display();
