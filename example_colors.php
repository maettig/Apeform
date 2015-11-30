<?php

/**
 * Color form example.
 *
 * @author Thiemo Mättig (http://maettig.com/)
 */

require_once("Apeform.class.php");
$form = new Apeform();

$color1_rgb = $form->checkbox("Choose one or more colors", "",
    "Red|Green|Blue");
$color1_name = $form->text("Or type another color",
    "Leave empty if not required");

$form->header("<hr>");

$color2_select = $form->select("Choose a color", "", "Red|Green|Blue");
$name_select = $form->getName();
$color2_name = $form->text("Or type a new color",
    "Leave empty if not required");

$form->header("<hr>");

$color3_base = array(
    "Black", "Maroon", "Green", "Olive<br>",
    "Navy", "Purple", "Teal", "Silver<br>",
    "Gray", "Red", "Lime", "Yellow<br>",
    "Blue", "Fuchsia", "Aqua", "White");
$color3_bichrome = array(
    '<span style="background:maroon;color:white">dark red</span>',
    '<span style="background:red;color:white">red</span>',
    '<span style="background:green;color:white">dark green</span>',
    '<span style="background:lime">green</span>');
$color3_base_index = $form->checkbox("Choose one or more colors", "",
    $color3_base);
$color3_bichrome_index = $form->checkbox("Or choose from these colors", "",
    $color3_bichrome);

$form->header("<hr>");

$form->header("Type a color name in the text field below");
$color4 = $form->text();

$form->header("<hr>");

$color5 = $form->text("#RRGGBB", "", array("00", "99", "FF"), 2);
$name_rgb = $form->getName();

$form->header("<hr>");

$form->submit();

// Here the construction and validation of the form ends. End of "controller"
// code, start of "view" code.

?>

<style type="text/css">
/* The :first-child and + selectors do not work in Internet Explorer. */
#<?php echo $name_select; ?>i option:first-child { background-color: #F99; }
#<?php echo $name_select; ?>i option+option { background-color: #9F9; }
#<?php echo $name_select; ?>i option+option+option { background-color: #99F; }
#<?php echo $name_rgb;    ?>i0 { background-color: #FCC; }
#<?php echo $name_rgb;    ?>i1 { background-color: #CFC; }
#<?php echo $name_rgb;    ?>i2 { background-color: #CCF; }
</style>

<?php $form->display(); ?>

<? if ($form->isValid()): ?>

  <p style="background-color: <?php echo empty($color1_name) ? "#" .
    (in_array("Red",   $color1_rgb) ? "FF" : "00") .
    (in_array("Green", $color1_rgb) ? "FF" : "00") .
    (in_array("Blue",  $color1_rgb) ? "FF" : "00") : $color1_name; ?>;">
    Color No. 1
  </p>

  <p style="background-color: <?php echo empty($color2_name) ? $color2_select :
    $color2_name; ?>;">
    Color No. 2
  </p>

  <p>
    <? if (!empty($color3_bichrome_index)): ?>
      <? foreach ($color3_bichrome_index as $i): ?>
        <span style="background-color: <?php echo preg_replace('/^[^:]*:(\w+).*$/i',
          '\1', $color3_bichrome[$i]); ?>;">
          Color No. 3</span>
      <? endforeach; ?>
    <? else: ?>
      <? foreach ($color3_base_index as $i): ?>
        <span style="background-color: <?php echo strip_tags($color3_base[$i]); ?>;">
          Color No. 3</span>
      <? endforeach; ?>
    <? endif; ?>
  </p>

  <p style="background-color: <?php echo $color4; ?>;">
    Color No. 4
  </p>

  <p style="background-color: <?vprintf("#%02s%02s%02s", $color5); ?>;">
    Color No. 5
  </p>

<? endif; ?>
