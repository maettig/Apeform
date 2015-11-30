<?php

/**
 * Example for TM::Apeform. Shows use of different templates.
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

require_once("Apeform.class.php");

srand(time());

echo "<h1>TM::Apeform Templates Example</h1>";

$form = new Apeform();
$form->header("A header");
$form->text("<u>L</u>abel", "A help text", "Default value");
if (!rand(0, 2))
{
    $form->error("Random error message No. 1");
}
$form->text("The <u>e</u>lement's label", "This element may get an error");
if (!rand(0, 2))
{
    $form->error("Random error message #2");
}
$form->checkbox("Another label", "Another help text", "<u>C</u>heck");
if (!rand(0, 2))
{
    $form->error("Random error message three");
}
$radio = $form->radio("Choose the form's templates", "", array(
    'default' => "<u>D</u>efault (error message replaces label)<br />\n",
    'defaultcss' => "Default with style sheets<br />\n",
    'above' => "Error message above element, no help (but tooltips)<br />\n",
    'kalsey' => "Style sheets, decent<br />\n",
    'css' => "Style sheets, crazy"), "default");
if (!rand(0, 2))
{
    $form->error("Fourth random error message");
}
$form->submit("Redraw the form");

switch ($radio)
{
    case "default":
        /* no templates, no style sheets */
        break;

    case "defaultcss":
        /* no templates */

        echo '<style type="text/css">';
        echo 'th{background-color:#009;color:#FFF}';
        echo 'td{background-color:#CCF}';
        echo '.error{color:#F00}';
        echo '</style>';
        break;

    case "above":
        $form->templates['input'] =
            "<tr>\n<td align=\"right\" valign=\"top\">{label}</td>\n" .
            "<td valign=\"top\">{error}{input}</td>\n</tr>\n";
        $form->templates['error'] =
            "<strong class=\"error\">{error}</strong><br />\n";

        echo '<style type="text/css">';
        echo 'th{background-color:#009;color:#FFF}';
        echo 'td{background-color:#CCF}';
        echo '.error{color:#F00}';
        echo '</style>';
        break;

    case "kalsey":
        $form->templates = array(
            'form' => "{input}",
            'header' => "<h3>{header}</h3>\n",
            'input' =>
                "<div class=\"row\"><div{error}><div class=\"label\">{label}</div>\n" .
                "<div class=\"input\">{input}</div></div></div>",
            'label' => "{label}:",
            'error' => " class=\"error\"><strong>{error}</strong");

        echo '<style type="text/css">';
        echo 'body,input{font-family:sans-serif;}';
        echo 'label{cursor:pointer;cursor:hand;}';
        echo '.error{background-color:#FCC;border: 1px solid red;padding:1ex 0;}';
        echo '.error strong{padding-left:1ex;}';
        echo '.row{margin:2ex 0;}';
        echo '.label{float:left;text-align:right;width:30ex;}';
        echo '.input{padding-left:31ex;}';
        echo '</style>';
        break;

    case "css":
        $form->templates = array(
            'form' => "{input}",
            'header' => "<h3>{header}</h3>\n",
            'input' =>
                "{label}<div class=\"element\">{input}{error}</div>\n",
            'label' => "<h4>{label}{help}</h4>",
            'error' => "<br /><strong class=\"error\">{error}</strong>\n",
            'help' => "<br /><small>(?) {help}</small>");

        echo '<style type="text/css">';
        echo 'h3{background:#FD0}';
        echo 'h4{background:#EEA;margin:1ex 0 0 0}';
        echo '.element{background:#DDC;border:2px inset white;' .
            'margin:0 0 1ex 6ex;padding:.5ex}';
        echo 'small{color:#666;font-weight:normal}';
        echo '.error{color:#F00}';
        echo '</style>';
        break;
}

$form->display();
