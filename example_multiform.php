<?php

/**
 * Single page, multi form example. Shows one possible solution how to use
 * multiple forms on a single page.
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

require_once("Apeform.class.php");

// Define form A. This gots no id (defaults to "form").
$formA = new Apeform();
$textA = $formA->text();
$submitA = $formA->submit("Button A");

// Define form B. Important: This gots a different id.
$formB = new Apeform(0, 0, "formB");
$textB = $formB->text();
$submitB = $formB->submit("Button B");

// Display form A.
$formA->display();

// Display form B. The auto-focus is disabled here cause the form gots an id.
$formB->display();

// Process form A.
if ($formA->isValid())
{
    echo "Form A submitted: " . $textA;
}

// Process form B.
if ($formB->isValid())
{
    echo "Form B submitted: " . $textB;
}
