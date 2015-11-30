<?php

/**
 * Multiple page form example. Shows use of a big multiple page form to
 * acquire a large ammount of data.
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

require_once("Apeform.class.php");

// Create a single form.
$form = new Apeform();

$page = 1;

// Add the first form elements.
$element0 = $form->text("First form, element 0");
if (empty($element0)) $form->error();
$form->addClass("page1");
$element1 = $form->text("First form, element 1");
if (empty($element1)) $form->error();
$form->addClass("page1");
$submit1 = $form->submit("Go to page 2");
$form->addClass("page1");

if ($form->isValid()) $page++;

// Add some more form elements.
$element3 = $form->text("Second form, element 3");
if (empty($element3)) $form->error();
$form->addClass("page2");
$submit2 = $form->submit("Go to page 1|Submit both forms");
$form->addClass("page2");

if ($form->isValid()) $page++;

if ($submit1 == "Go to page 2")
{
    $page = 2;
    $form->_isSubmitted = false;
}
elseif ($submit2 == "Go to page 1")
{
    $page = 1;
    $form->_isSubmitted = false;
}

echo '<style type="text/css">';
if ($page != 1) echo '.page1{display:none;}';
if ($page != 2) echo '.page2{display:none;}';
echo '</style>';

if (!$form->isValid())
{
    // Display the form.
    $form->display();
}
else
{
    // Process the collected form data.
    echo "INSERT INTO `table` SET `element0` = '$element0'";
    echo ", `element1` = '$element1', `element3` = '$element3'";
}
