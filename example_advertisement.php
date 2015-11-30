<?php

/**
 * Small advertisement form example. Shows basic use of an extended class.
 * Shows use of a preview function including a file upload element.
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

// Display some HTML headers.
echo '<style>';
echo 'body{background-color:#eeeeee;font-family:Georgia,serif;}';
echo 'input,select,textarea{font-family:monospace;}';
echo '.error{color:red;font-weight:bold;}';
echo '</style>';
echo '<h2 align="center">Enter new small Advertisement</h2>';

//----------------------------------------------------------------------------

// Include the extended class and build a form out of it.
require_once("example_advertisement.class.php");
$form = new ExtendedApeform();

$message = &$form->textarea("Advertising <u>M</u>essage", "Bidding, Application, ...", "", 7);
$message = trim(preg_replace('/\s+/', ' ', $message));
$message = preg_replace('/(.)(\1{3})\1*/', '\2', $message);
if (!$message) $form->error("Message missing");
if (strlen($message) < 10) $form->error("Message to short");
if (strlen($message) > 500) $form->error("Message to long");

$name = $form->text("Your <u>N</u>ame");
if (!$name) $form->error("Name missing");

$email = $form->text("Your <u>E</u>mail", "Optional; if somebody wants to contact you");
if ($email && !preg_match(
    '/^[^\s"\'<>()@,;:]+@[^\s"\'<>()@,;:]+\.[a-z]{2,6}$/is', $email))
{
    $form->error("Email invalid");
}

$telefon = $form->text("Your <u>T</u>elephone", "Optional");
if (!$email && !$telefon)
{
    $form->error("Email (or Telephone) missing", -1);
    $form->error("Telephone (or Email) missing");
}

$options = array(7 => "1 Week",
                14 => "2 Weeks",
                21 => "3 Weeks",
                28 => "4 Weeks");
$tage = $form->select("Duration", "How long should the Advertisement be visible?", $options);

$image = $form->file("Image", "Optional; JPG/JPEG/PNG/GIF only");
if ($image && !getimagesize($image['tmp_name']))
{
    $form->error("Invalid Image File");
}

$submit = $form->submit("Preview|Submit");

//----------------------------------------------------------------------------

// Generate preview if requested.
if ($form->isValid())
{
    echo '<table width="100%" border="1" cellpadding="4" cellspacing="0">';
    echo '<tr><th bgcolor="#CCCCCC" colspan="3">Preview</th></tr>';
    echo '<tr><td valign="top" width="200">';
    if ($image)
    {
        $size = getimagesize($image['tmp_name']);
        $width = 200;
        $height = $size[1] * $width / $size[0];
        echo '<img src="' . $image['tmp_name'] . '" width="' . $width . '" height="' . $height . '">';
    }
    else echo "No Image submitted";
    echo '</td><td valign="top">';
    $preview = htmlspecialchars(stripslashes($message));
    $preview = preg_replace('/^.{10,}\b/U', '<big>\0</big>', $preview);
    echo $preview;
    echo '</td><td valign="top">';
    if ($email) echo '<a href="mailto:' . $email . '">';
    echo '<em>' . htmlspecialchars(stripslashes($name)) . '</em>';
    if ($email) echo '</a>';
    if ($telefon) echo '<br>Telephone: ' . htmlspecialchars(stripslashes($telefon));
    echo '</td></tr>';
    echo '</table>';
}

//----------------------------------------------------------------------------

if (!$form->isValid() || $submit == "Preview")
{
    // Display the whole form.
    $form->display();
}
else
{
    // Make sure there is no equal entry. Reloads will be ignored this way.
    $sql = "SELECT id FROM table WHERE message = '$message'";
    // $result = mysql_query($sql);
    $num_rows = 0;
    // $num_rows = mysql_num_rows($result);
    if ($num_rows < 1)
    {
        // Insert the new entry into the database table.
        $sql = "INSERT INTO table SET message = '$message'";
        // mysql_query($sql);
        echo '<p class="error">Submission succesfull.</p>';
    }
    else
    {
        // Error. This entry is already in the database. Don't submit twice.
        echo '<p class="error">This Advertisement was already submitted before.</p>';
    }
}
