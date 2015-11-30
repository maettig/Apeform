<?php

// Include the original TM::Apeform class to be extended.
require_once("Apeform.class.php");

/**
 * Extended TM::Apeform class.
 */
class ExtendedApeform extends Apeform
{
    function regex($patterns, $offset = -1)
    {
        if ($offset < 0) $offset += count($this->_rows);
        $value = &$this->_rows[$offset]['value'];

        foreach ((array)$patterns as $pattern => $message)
        {
            // Required to handle multiple text elements.
            foreach ((array)$value as $singleValue)
            {
                if (!preg_match($pattern, $singleValue))
                {
                    $this->error($message, $offset);
                    return false;
                }
            }
        }
    }

    function callback_price($offset = -1)
    {
        if ($offset < 0) $offset += count($this->_rows);
        $value = &$this->_rows[$offset]['value'];

        $price = $value[0] + $value[1] / 100;
        if ($price <= 0) $this->error("Price can not be empty");
    }
}

function callback_price(&$form, $offset = -1)
{
    if ($offset < 0) $offset += count($form->_rows);
    $value = &$form->_rows[$offset]['value'];

    $price = $value[0] + $value[1] / 100;
    if ($price <= 0) $form->error("Price can not be empty");
}

$form = new ExtendedApeform();

$price = $form->text("Price", "This one uses regular expressions\t.\t Euro",
    "", array(10, 2));
// Define a bunch of regular expressions and error messages.
$form->regex(array(
    '/^\d*$/s' => "Invalid characters in price",
    '/^.+$/s' => "Empty fields not allowed in price"));

$price = $form->text("Price", "This one uses an inner callback method\t.\t Euro",
    "", array(10, 2));
$form->callback_price();

$price = $form->text("Price", "This one uses an outer callback function\t.\t Euro",
    "", array(10, 2));
callback_price($form);

$form->submit("Try");
$form->display();
