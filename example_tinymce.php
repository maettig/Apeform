<?php

/**
 * A slightly complex TM::Apeform and TinyMCE example. You need at least the
 * TinyMCE main package somewhere on your web server. Change the define below
 * and set the directory to where you have copied the package.
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

define("TINYMCE_SRC", "tiny_mce/tiny_mce.js");

require_once("Apeform.class.php");

// Create a new form with predefined maxlength and size for all form elements.
$form = new Apeform(255, 80);

// We don't want quotes in the preview. Use mysql_real_escape_string() instead!
$form->magicQuotes = false;

$caption = $form->text("Caption", "No HTML allowed");
if (empty($caption))
{
    $form->error("Please enter a caption");
}

$categories = array(
    "Blue",
    "Red",
    "Yellow",
);
$category_id = $form->select("Category", "", $categories);

// Create a textarea with no help text, no default value and 10 lines.
$text = $form->textarea("Text", "", "", 10);
if (empty($text))
{
    $form->error("Please enter some text");
}
// We need the internal name of this form element for TinyMCE.
$text_name = $form->getName();

// Add a second textarea. Only the first one should be modified by TinyMCE.
$abstract = $form->textarea("Abstract", "No HTML allowed");

// Add two buttons in a single row.
$submit = $form->submit("Preview|Save");

// Everything is prepared, now we are ready to start the output.

?><html>
<head>
<title>How to use the TM::Apeform PHP Class with TinyMCE</title>
<style type="text/css">
/* A little bit of CSS for a nice preview. */
.article{
	background: #EEE;
	border: 1px solid #CCC;
	color: #111;
	font-family: Verdana, sans-serif;
	padding: 1em;
}
.article h2{
	margin: 0;
}
.category{
	color: #666;
	font-size: smaller;
	margin: 0;
}
</style>
<script type="text/javascript" src="<?php echo TINYMCE_SRC; ?>"></script>
<script type="text/javascript">
	tinyMCE.init({
		/* The mode "textareas" modifies all textareas. */
		mode     : "exact",
		/* Modify the textarea with this name only. */
		elements : "<?php echo $text_name; ?>",
		theme    : "simple",
	});
</script>
</head>
<body>

<h1>How to use the TM::Apeform PHP Class with TinyMCE</h1>

<?php

// If the save button was pressed, do not display the form and the preview.
if ($form->isValid() && $submit == "Save")
{
    echo "The text is not saved in this example script.";
}
else
{
    $form->display();

    if ($form->isValid())
    {
        echo "<p>Preview:</p>";
        echo "<div class=\"article\">";
        echo "<h2>" . htmlspecialchars($caption) . "</h2>";
        echo "<p class=\"category\">Posted in " . htmlspecialchars($categories[$category_id]) . "</p>";
        echo $text;
        echo "</div>";
    }
}

?>

</body>
</html>