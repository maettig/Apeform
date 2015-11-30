<?php

/**
 * Multiple page form example. Shows use of a big multiple page form to
 * acquire a large ammount of data.
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

require_once("Apeform.class.php");

// Create the first form.
$form = new Apeform();
// Add the first three form elements.
$element0 = $form->text("First form, element 0");
$element1 = $form->text("First form, element 1");
$form->submit("Go to second form");

if (!$form->isValid())
{
    // Display the first form.
    $form->display();
}
else
{
    // Create the second form.
    $form = new Apeform();
    // Create hidden elements for any element that comes from the first form.
    // Although they look like dummy elements, they are required components.
    $form->hidden(); // element0
    $form->hidden(); // element1
    $form->hidden(); // element2
    // Reset the submission state of the form (private object property).
    $form->_isSubmitted = false;
    // Add some more form elements.
    $element3 = $form->text("Second form, element 3");
    $form->submit("Submit both forms");

    if (!$form->isValid())
    {
        // Display the second form.
        $form->display();
    }
    else
    {
        // Process the collected form data.
        echo "INSERT INTO `table` SET `element0` = '$element0'";
        echo ", `element1` = '$element1', `element3` = '$element3'";
    }
}
