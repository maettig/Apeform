<?php

/**
 * Single page, multiple use form example. Shows use of a single form to
 * acquire multiple database records.
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

// Include the class.
require_once("Apeform.class.php");
// Create an instance of the class.
$form = new Apeform();

// Add some form elements. Note the ampersands (get the values as references).
$name = &$form->text("Name");
// Do some basic error handling.
if (!$name) $form->error("Name missing");
$age = &$form->text("Age", "\tYears", 0, 2);
if ($age < 1) $form->error("Age missing");
$form->submit("Insert Record");

if ($form->isValid())
{
    // Process the database query (dummy code in this example, of course).
    echo "INSERT INTO table SET name = '$name', age = '$age'";
    // Reset the form elements (that's the trick to empty the form).
    $name = "";
    $age = 0;
}

// Display the form.
$form->display();
