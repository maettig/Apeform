<?php

// Include the original TM::Apeform class to be extended.
require_once("Apeform.class.php");

/**
 * Extended TM::Apeform class providing some special form elements with build in
 * error checking.
 */
class ExtendedApeform extends Apeform
{
    /**
     * Creates an ISO-date input field, e.g. "2003-12-31".
     *
     * @param label string
     * @param help string
     * @param defaultValue string
     * @return string
     */
    function &date($label = "<u>D</u>ate", $help = "Year-Month-Day",
        $defaultValue = "")
    {
        // Use the actual date as defaultValue.
        if (empty($defaultValue)) $defaultValue = date("Y-m-d");
        // Calculate default element maxLength (and size too).
        $maxLength = strlen($defaultValue);
        // Call the parent method.
        $date = &$this->text($label, $help, $defaultValue, $maxLength);
        // Try to fetch three numbers from the string, the year first.
        if (!preg_match('/(\d+)\D+(\d+)\D+(\d+)/s', $date, $m))
        {
            $this->error("Please enter a date");
        }
        else
        {
            // Extend years with missing century.
            if ($m[1] <= 38) $m[1] += 2000;
            elseif ($m[1] <= 99) $m[1] += 1900;
            // Check validity, e.g. 2003-02-29 is forbidden.
            if (!checkdate($m[2], $m[3], $m[1]))
            {
                $this->error("Please enter a valid <u>d</u>ate");
            }
            else
            {
                // Well-form the value.
                $date = sprintf("%04s-%02s-%02s", $m[1], $m[2], $m[3]);
            }
        }
        return $date;
    }

    /**
     * Creates an ISO-date input field, e.g. "2003-12-31".
     *
     * @param label string
     * @param help string
     * @param defaultValue string
     * @return string
     */
    function &dateSelector($label = "<u>D</u>ate", $help = "Year-Month-Day",
        $defaultValue = "")
    {
        static $count = 0;
        $count++;
        $name = "element" . count($this->_rows);
        $help .= <<<CALENDAR
\t <a href="#" onclick="return calendar$count()">Calendar...</a>
<script type="text/javascript">
function calendar$count()
{
  var c = '<html><head><title>Calendar</title></head><body>This is just an example<br><code>';
  for (i = 1; i <= 31; i++)
  {
    v = i <= 9 ? '0' + i : i;
    c += '<a href="#" onclick="e=opener.document.forms[\'form\'].elements[\'$name\'];';
    c += 'e.value=e.value.substr(0,8)+\'' + v + '\';self.close();return false">' + v + '</a> ';
    if (i % 7 == 0) c += '<br>';
  }
  c += '</code></body></html>';
  var w = 250;
  var h = 130;
  var p = "";
  if (typeof event == "object" && typeof event.screenX == "number")
  {
    p = ",left=" + event.screenX + ",top=" + event.screenY;
  }
  var f = window.open("", "Calendar", "width=" + w + ",height=" + h + p + ",location=no,menubar=no,status=no,toolbar=no");
  f.document.open("text/html", "replace");
  f.document.write(c);
  f.document.close();
  f.focus();
  return false;
}
</script>
CALENDAR;
        // Call the parent method.
        return $this->date($label, $help, $defaultValue);
    }

    /**
     * Creates an email input field, e.g. "name@domain.tld".
     *
     * @param label string
     * @param help string
     * @param defaultValue string
     * @param isRequired bool
     * @return string
     */
    function &email($label = "<u>E</u>mail", $help = "", $defaultValue = "",
        $isRequired = true)
    {
        // Call the parent method.
        $email = &$this->text($label, $help, $defaultValue);
        if ($isRequired && !$email)
        {
            $this->error("Please enter your <u>e</u>mail adress");
        }
        // Check for a single At sign, no spaces, top level domain and so on.
        elseif ($email && !preg_match(
            '/^[^\s"\'<>()@,;:]+@[^\s"\'<>()@,;:]+\.[a-z]{2,6}$/is', $email))
        {
            $this->error("Please enter a valid <u>e</u>mail adress");
        }
        return $email;
    }

    /**
     * Creates a price input field, e.g. "99.95".
     *
     * @param label string
     * @param help string
     * @param defaultValue float
     * @param min float
     * @param max float
     * @return string
     */
    function &price($label = "<u>P</u>rice", $help = "\t Euro",
        $defaultValue = "0.00", $min = 0.01, $max = 999999.99)
    {
        // Calculate maximum string length.
        $maxLength = max(strlen(sprintf("%.2f", $min)),
            strlen(sprintf("%.2f", $max)));
        // Call the parent method.
        $price = &$this->text($label, $help, $defaultValue, $maxLength,
            $maxLength);
        // Remove any special character.
        $price = preg_replace('/[^\d.-]+/s', '', $price);
        // Check boundaries.
        if ($price < $min)
        {
            $this->error("Please enter a larger <u>p</u>rice");
        }
        elseif ($price > $max)
        {
            $this->error("Please enter a lower <u>p</u>rice");
        }
        else
        {
            // Well-form the value.
            $price = sprintf("%.2f", $price);
        }
        return $price;
    }

    /**
     * Creates an extended file upload field.
     *
     * @param label string
     * @param help string
     * @param allowedType string
     * @param maxKB int
     * @param isRequired bool
     * @return array
     */
    function &filetype($label = "<u>F</u>ile", $help = "", $allowedType = "*/*",
        $maxKB = 2048, $isRequired = false)
    {
        // Call the parent method.
        $file = &$this->file($label, $help);
        $pattern = '{^' . str_replace("*", ".*", $allowedType) . '$}is';
        if ($isRequired && !$file)
        {
            $this->error("Please select a file");
        }
        elseif ($file && $allowedType && !preg_match($pattern, $file['type']))
        {
            $this->error("Please select a file of another type");
        }
        elseif ($file && $maxKB && $file['size'] > $maxKB * 1024)
        {
            $this->error("Please select a smaller file (max. " .
                number_format($maxKB) . "KB)");
        }
        return $file;
    }
}
