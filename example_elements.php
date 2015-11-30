<?php

/**
 * Extended form elements example. Shows use of an extended class to provide
 * some special form elements with build in error checking.
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

// Include the extended class.
require_once("example_elements.class.php");

// Instantiate an object from this class.
$form = new ExtendedApeform();

// Add some of the extended input fields to the form.
$date = $form->date();
$date = $form->dateSelector();
$email = $form->email();
$date = $form->price();
$image = $form->filetype("<u>I</u>mage", "JPG, PNG etc. only", "image/*", 10,
    true);
$form->submit();

// Process the form if it was submitted error-free.
if ($form->isValid()) echo "Thank you!";
else $form->display();
