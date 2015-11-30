<?php

/**
 * Email form example. Shows compatible use of select and radio. Shows basic
 * use of returning by reference. Shows use of acceskey characters.
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

require_once("Apeform.class.php");
$form = new Apeform();

$form->header("Support email");

// It's very easy to switch between input type "radio" and "select".
$problem = $form->select("Your <u>p</u>roblem", "", "I found 20 &euro;|I need help|It doesn't work");
$problem =  $form->radio("Your <u>p</u>roblem", "", "I found 20 &euro;|I need help|It doesn't work");

// Return value as a reference, note the ampersand.
$description = &$form->textarea("<u>D</u>escribe your problem", "", "", 10);
// Due to the reference the following changes are displayed imediatelly.
$description = strtoupper($description);
if (!$description)
{
    $form->error("Description missing");
}

$form->header("Additional information about you");

$name = $form->text("Your <u>n</u>ame");
if (!$name)
{
    $form->error("Name missing");
}

// Return value as a reference, note the ampersand.
$email = &$form->text("Your <u>e</u>mail", "Please specify if you want an answer.");
// Add an individual class label to the form element above.
$form->addClass("important");
// Due to the reference the following changes are displayed imediatelly.
$email = strtolower(trim($email));
if ($email && !preg_match(
    '/^[^\s"\'<>()@,;:]+@[^\s"\'<>()@,;:]+\.[a-z]{2,6}$/is', $email))
{
    $form->error("Email invalid");
}

$form->submit("Send email");

echo '<style type="text/css">';
echo 'table { margin: auto; }';
echo 'th { background-color: #D2ECD2; }';
echo 'td { background-color: #F4FAF4; }';
echo '.important label, .important strong { background-color: #FE0; }';
echo '</style>';

if ($form->isValid())
{
    // mail("example@example.com", $problem, $description, "From: $email");
    echo "I'm trying to solve your problem. Please wait ...";
}

$form->display();

echo '<p class="important"><strong>Important:</strong> Individual stylesheet class on single form elements.</p>';
