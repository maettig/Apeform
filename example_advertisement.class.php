<?php

// Include the parent class to be extended.
require_once("Apeform.class.php");

// All properties and methods of the Apeform class are copied.
class ExtendedApeform extends Apeform
{
    var $magicQuotes = true;

    var $size = 50;

    var $tmpDir = "temporary";

    // Overwrite the "templates" property.
    var $templates = array(
        'form' => "<p><table align=\"center\" border=\"0\" cellpadding=\"8\" cellspacing=\"0\" summary=\"\">\n{input}</table>",
        'input' => "<tr>\n<th align=\"right\">{label}</th>\n<td>{input}{help}</td>\n</tr>\n",
        'label' => "{label}",
        'error' => "<p class=\"error\">{error}</p>",
        'help' => "<br><small>({help})</small>"
    );

    function ExtendedApeform()
    {
        // Call the parent constructor.
        parent::Apeform();

        // Create the temporary directory (to make the example work).
        if (!file_exists($this->tmpDir)) mkdir($this->tmpDir, 0777);
    }
}
