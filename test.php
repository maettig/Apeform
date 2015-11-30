<?php

/*
 * Collection of test cases for the TM::Apeform class. Requires PEAR::PHPUnit 1,
 * see http://pear.php.net/package/PHPUnit/
 */

require_once("Apeform.class.php");
require_once("PHPUnit.php");
require_once("PHPUnit/GUI/HTML.php");

class ApeformTest extends PHPUnit_TestCase
{
    var $f = null;

    function setUp()
    {
        $this->f = new Apeform();
        $this->f->templates = array(
            'form' =>
                "[FORM]{input}[/FORM]",
            'header' =>
                "[HEADER]{header}[/HEADER]",
            'input' =>
                "[INPUT]{label}{input}{help}[/INPUT]",
            'label' =>
                "[LABEL]{label}[/LABEL]",
            'error' =>
                "[ERROR]{error}[/ERROR]",
            'help' =>
                "[HELP]{help}[/HELP]");
        $this->PHP_SELF = $_SERVER['PHP_SELF'];
        $_SERVER['PHP_SELF'] = "[PHP_SELF]";
        $GLOBALS['HTTP_SERVER_VARS']['PHP_SELF'] = "[PHP_SELF]";
    }

    function tearDown()
    {
        $_POST = null;
        $GLOBALS['HTTP_SERVER_VARS']['PHP_SELF'] = $this->PHP_SELF;
        $_SERVER['PHP_SELF'] = $this->PHP_SELF;
        $this->f = null;
    }

    function testApeform()
    {
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml());
        $this->assertEquals($expected, $this->f->toHtml());
        $this->assertEquals($expected, $this->f->toHtml());
    }

    function testApeformWithId()
    {
        $this->f->id = "X";
        $expected = "<form action=\"[PHP_SELF]#X\" id=\"X\" method=\"post\">[FORM]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml());
        $this->assertEquals($expected, $this->f->toHtml());
        $this->assertEquals($expected, $this->f->toHtml());
        $this->f->text();
        $expected = "<form action=\"[PHP_SELF]#X\" id=\"X\" method=\"post\">[FORM]" .
            "[INPUT]<input type=\"text\" name=\"Xelement0\" value=\"\" maxlength=\"255\" size=\"40\" id=\"Xelement0i\" />[/INPUT]" .
            "[/FORM]</form>" .
            "<script type=\"text/javascript\">\n" .
            "self.onload=function(){var f=document.forms['X'];if(f){var e=f.elements['Xelement0'];if(e&&e.focus)e.focus();}}\n" .
            "</script>";
        $this->assertEquals($expected, $this->f->toHtml(true));
        $this->assertEquals($expected, $this->f->toHtml(true));
        $this->f->text("LABEL", "HELP", "VALUE", 71, 29);
        $expected = "<form action=\"[PHP_SELF]#X\" id=\"X\" method=\"post\">[FORM]" .
            "[INPUT]<input type=\"text\" name=\"Xelement0\" value=\"\" maxlength=\"255\" size=\"40\" id=\"Xelement0i\" />[/INPUT]" .
            "[INPUT]<label for=\"Xelement1i\">[LABEL]LABEL[/LABEL]</label><input type=\"text\" name=\"Xelement1\" value=\"VALUE\" maxlength=\"71\" size=\"29\" id=\"Xelement1i\" title=\"HELP\" />[HELP]HELP[/HELP][/INPUT]" .
            "[/FORM]</form>" .
            "<script type=\"text/javascript\">\n" .
            "self.onload=function(){var f=document.forms['X'];if(f){var e=f.elements['Xelement0'];if(e&&e.focus)e.focus();}}\n" .
            "</script>";
        $this->assertEquals($expected, $this->f->toHtml(true));
    }

    function testAnchor()
    {
        $this->f->id = "X";
        $this->f->anchor = "A";
        $expected = "<form action=\"[PHP_SELF]#A\" id=\"X\" method=\"post\">[FORM]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml());
    }

    function testAnchorDisabled()
    {
        $this->f->id = "X";
        $this->f->anchor = false;
        $expected = "<form action=\"[PHP_SELF]\" id=\"X\" method=\"post\">[FORM]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml());
    }

    function testTemplates()
    {
        $this->f->templates['input'] = "[INPUT]{help}{label}{input}[/INPUT]";
        $this->f->_isSubmitted = true;
        $this->f->text("<u>a</u>x", "h", "v");
        $this->f->error("xa");
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]" .
            "[HELP]h[/HELP]" .
            "<label accesskey=\"a\" for=\"element0i\">[ERROR]x<u>a</u>[/ERROR]</label>" .
            "<input type=\"text\" name=\"element0\" value=\"v\" maxlength=\"255\" size=\"40\" id=\"element0i\" title=\"h\" />" .
            "[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml(false));
        $this->f->_rows[0]['error'] = "";
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]" .
            "[HELP]h[/HELP]" .
            "<label accesskey=\"a\" for=\"element0i\">[ERROR]<u>a</u>x[/ERROR]</label>" .
            "<input type=\"text\" name=\"element0\" value=\"v\" maxlength=\"255\" size=\"40\" id=\"element0i\" title=\"h\" />" .
            "[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml(false));
        $this->f->_rows[0]['label'] = "";
        $this->f->_rows[0]['error'] = "x<u>a</u>";
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]" .
            "[HELP]h[/HELP]" .
            "<label accesskey=\"a\" for=\"element0i\">[ERROR]x<u>a</u>[/ERROR]</label>" .
            "<input type=\"text\" name=\"element0\" value=\"v\" maxlength=\"255\" size=\"40\" id=\"element0i\" title=\"h\" />" .
            "[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml(false));
    }

    function testTemplatesWithErrorTag()
    {
        $this->f->templates['input'] = "[INPUT]{help}{label}{input}{error}[/INPUT]";
        $this->f->_isSubmitted = true;
        $this->f->text("<u>a</u>x", "h", "v");
        $this->f->error("xa");
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]" .
            "[HELP]h[/HELP]" .
            "<label accesskey=\"a\" for=\"element0i\">[LABEL]<u>a</u>x[/LABEL]</label>" .
            "<input type=\"text\" name=\"element0\" value=\"v\" maxlength=\"255\" size=\"40\" id=\"element0i\" title=\"h\" />" .
            "[ERROR]xa[/ERROR]" .
            "[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml(false));
        $this->f->_rows[0]['error'] = "";
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]" .
            "[HELP]h[/HELP]" .
            "<label accesskey=\"a\" for=\"element0i\">[LABEL]<u>a</u>x[/LABEL]</label>" .
            "<input type=\"text\" name=\"element0\" value=\"v\" maxlength=\"255\" size=\"40\" id=\"element0i\" title=\"h\" />" .
            "[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml(false));
        $this->f->_rows[0]['label'] = "";
        $this->f->_rows[0]['error'] = "x<u>a</u>";
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]" .
            "[HELP]h[/HELP]" .
            "<label accesskey=\"a\" for=\"element0i\"></label>" .
            "<input type=\"text\" name=\"element0\" value=\"v\" maxlength=\"255\" size=\"40\" id=\"element0i\" title=\"h\" />" .
            "[ERROR]x<u>a</u>[/ERROR]" .
            "[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml(false));
    }

    function testTemplatesWithoutSubtemplates()
    {
        unset($this->f->templates['label']);
        unset($this->f->templates['error']);
        unset($this->f->templates['help']);
        $this->f->_isSubmitted = true;
        $this->f->text("<u>a</u>x");
        $this->f->error("xa");
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]" .
            "<label accesskey=\"a\" for=\"element0i\">x<u>a</u></label>" .
            "<input type=\"text\" name=\"element0\" value=\"\" maxlength=\"255\" size=\"40\" id=\"element0i\" />" .
            "[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml(false));

        $this->f->templates['input'] = "[INPUT]{label}{error}{input}{help}[/INPUT]";
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]" .
            "<label accesskey=\"a\" for=\"element0i\"><u>a</u>x</label>" .
            "xa" .
            "<input type=\"text\" name=\"element0\" value=\"\" maxlength=\"255\" size=\"40\" id=\"element0i\" />" .
            "[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml(false));
    }

    function testAccesskey()
    {
        $this->f->templates['accesskey'] = "<ACCESSKEY>{accesskey}</ACCESSKEY>";
        $this->f->text("l<u>a</u>l");
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]" .
            "<label accesskey=\"a\" for=\"element0i\">[LABEL]l<ACCESSKEY>a</ACCESSKEY>l[/LABEL]</label>" .
            "<input type=\"text\" name=\"element0\" value=\"\" maxlength=\"255\" size=\"40\" id=\"element0i\" />" .
            "[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml(false));
        unset($this->f->_rows[0]);
        $this->f->radio("<u>a</u>", "", array("h<u>i</u>j", "k<u>l</u>m"));
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]" .
            "[LABEL]<ACCESSKEY>a</ACCESSKEY>[/LABEL]" .
            "<input type=\"radio\" name=\"element0\" value=\"0\" checked=\"checked\" id=\"element0i0\" />" .
            "<label for=\"element0i0\" accesskey=\"i\">h<ACCESSKEY>i</ACCESSKEY>j</label>\n" .
            "<input type=\"radio\" name=\"element0\" value=\"1\" id=\"element0i1\" />" .
            "<label for=\"element0i1\" accesskey=\"l\">k<ACCESSKEY>l</ACCESSKEY>m</label>\n" .
            "[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml(false));
    }

    function testAutoAccesskeys()
    {
        $this->f->autoAccesskeys = true;
        $this->f->templates['accesskey'] = "<U>{accesskey}</U>";
        $this->f->text("SmE exämp_l");
        $this->f->text("SmE exämp_l");
        $this->f->text("SmE exämp_l");
        $this->f->text("SmE exämp_l");
        $this->f->text("SmE eXämp_l");
        $this->f->text("SmE exämp_l");
        $this->f->text("SmE exämp_l");
        $this->assertRegexp('
            {
                accesskey="s".*\[LABEL\]<U>S</U>mE\ exämp_l\[/LABEL\].*
                accesskey="m".*\[LABEL\]S<U>m</U>E\ exämp_l\[/LABEL\].*
                accesskey="e".*\[LABEL\]Sm<U>E</U>\ exämp_l\[/LABEL\].*
                accesskey="x".*\[LABEL\]SmE\ e<U>x</U>ämp_l\[/LABEL\].*
                accesskey="p".*\[LABEL\]SmE\ eXäm<U>p</U>_l\[/LABEL\].*
                accesskey="l".*\[LABEL\]SmE\ exämp_<U>l</U>\[/LABEL\].*
                \[LABEL\]SmE\ exämp_l\[/LABEL\]
            }isUx', $this->f->toHtml());
    }

    function testHeader()
    {
        $this->f->header("bla");
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[HEADER]bla[/HEADER]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml());
        $this->f->header("2nd");
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[HEADER]bla[/HEADER]" .
            "[HEADER]2nd[/HEADER]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml());
    }

    function testStaticText()
    {
        $this->f->staticText("Blabel", "Bhelp", "Bval");
        $this->f->staticText("Clabel", "Chelp");
        $this->f->staticText("Dlabel");
        $this->f->staticText();
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT][LABEL]Blabel[/LABEL]Bval<input type=\"hidden\" name=\"element0\" value=\"Bval\" />[HELP]Bhelp[/HELP][/INPUT]" .
            "[INPUT][LABEL]Clabel[/LABEL]<input type=\"hidden\" name=\"element1\" value=\"\" />[HELP]Chelp[/HELP][/INPUT]" .
            "[INPUT][LABEL]Dlabel[/LABEL]<input type=\"hidden\" name=\"element2\" value=\"\" />[/INPUT]" .
            "[INPUT]<input type=\"hidden\" name=\"element3\" value=\"\" />[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml());
    }

    function testText()
    {
        $this->assertEquals("", $this->f->text());
        $this->assertEquals("text", $this->f->_rows[0]['type']);
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]<input type=\"text\" name=\"element0\" value=\"\" maxlength=\"255\" size=\"40\" id=\"element0i\" />[/INPUT]" .
            "[/FORM]</form>" .
            "<script type=\"text/javascript\">\n" .
            "self.onload=function(){var f=document.forms['form'];if(f){var e=f.elements['element0'];if(e&&e.focus)e.focus();}}\n" .
            "</script>";
        $this->assertEquals($expected, $this->f->toHtml());
        $this->assertEquals($expected, $this->f->toHtml());
        $var = &$this->f->text("LABEL", "HELP", "DEFAULTVALUE", 71, 29);
        $this->assertEquals("DEFAULTVALUE", $var);
        $var = "VALUE";
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]<input type=\"text\" name=\"element0\" value=\"\" maxlength=\"255\" size=\"40\" id=\"element0i\" />[/INPUT]" .
            "[INPUT]<label for=\"element1i\">[LABEL]LABEL[/LABEL]</label><input type=\"text\" name=\"element1\" value=\"VALUE\" maxlength=\"71\" size=\"29\" id=\"element1i\" title=\"HELP\" />[HELP]HELP[/HELP][/INPUT]" .
            "[/FORM]</form>" .
            "<script type=\"text/javascript\">\n" .
            "self.onload=function(){var f=document.forms['form'];if(f){var e=f.elements['element0'];if(e&&e.focus)e.focus();}}\n" .
            "</script>";
        $this->assertEquals($expected, $this->f->toHtml());
    }

    function testTextUnit()
    {
        $this->f->text("L", "H\tU");
        $this->f->staticText("", "\tU", "V");
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]" .
            "<label for=\"element0i\">[LABEL]L[/LABEL]</label>" .
            "<input type=\"text\" name=\"element0\" value=\"\" maxlength=\"255\" size=\"40\" id=\"element0i\" title=\"H\" />" .
            "U" .
            "[HELP]H[/HELP]" .
            "[/INPUT]" .
            "[INPUT]" .
            "V<input type=\"hidden\" name=\"element1\" value=\"V\" />" .
            "U" .
            "[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml(false));
        $this->f->text("L3", "H3\tU3");
        $this->assertRegexp('
            {
                <label[^>]*>\[LABEL\]L3\[/LABEL\]</label>
                <input\s+type="text"[^>]*>U3
                \[HELP\]H3\[/HELP\]
            }isx', $this->f->toHtml(false));
        $this->f->text("L4", "H4\tU4\tU4b");
        $this->assertRegexp('
            {
                <label[^>]*>\[LABEL\]L4\[/LABEL\]</label>
                <input\s+type="text"[^>]*>U4\tU4b
                \[HELP\]H4\[/HELP\]
            }isx', $this->f->toHtml(false));
        $this->f->text("L5", array("H5", "U5"));
        $this->assertRegexp('
            {
                <label[^>]*>\[LABEL\]L5\[/LABEL\]</label>
                <input\s+type="text"[^>]*>U5
                \[HELP\]H5\[/HELP\]
            }isx', $this->f->toHtml(false));
    }

    function testTextMultiple()
    {
        $this->assertEquals(array("V1", ""), $this->f->text("L1", "H1", "V1", 4, array(2, 3)));
        $this->assertEquals("text", $this->f->_rows[0]['type']);
        $this->assertRegexp('
            {
                <label[^>]*>\[LABEL\]L1\[/LABEL\]</label>
                <input\s+type="text"\s+name="element(\d+)\[\]"\s+value="V1"\s+maxlength="4"\s+size="2"[^>]*>\n
                <input\s+type="text"\s+name="element\1\[\]"\s+value=""\s+maxlength="4"\s+size="3"[^>]*>
                \[HELP\]H1\[/HELP\]
            }isx', $this->f->toHtml(false));
        $this->assertEquals(array("V2", ""), $this->f->text("L2", "H2", "V2", array(4, 5)));
        $this->assertRegexp('
            {
                <label[^>]*>\[LABEL\]L2\[/LABEL\]</label>
                <input\s+type="text"\s+name="element(\d+)\[\]"\s+value="V2"\s+maxlength="4"\s+size="4"[^>]*>\n
                <input\s+type="text"\s+name="element\1\[\]"\s+value=""\s+maxlength="5"\s+size="5"[^>]*>
                \[HELP\]H2\[/HELP\]
            }isx', $this->f->toHtml(false));
        $this->assertEquals(array("V3", "V3b"), $this->f->text("L3", "H3", array("V3", "V3b")));
        $this->assertRegexp('
            {
                <label[^>]*>\[LABEL\]L3\[/LABEL\]</label>
                <input\s+type="text"\s+name="element(\d+)\[\]"\s+value="V3"\s+maxlength="255"\s+size="20"[^>]*>\n
                <input\s+type="text"\s+name="element\1\[\]"\s+value="V3b"\s+maxlength="255"\s+size="20"[^>]*>
                \[HELP\]H3\[/HELP\]
            }isx', $this->f->toHtml(false));
        $this->assertEquals(array("", ""), $this->f->text("L4", "H4\tH4b\tH4c\tH4d", "", array(4, 5)));
        $this->assertRegexp('
            {
                <label[^>]*>\[LABEL\]L4\[/LABEL\]</label>
                <input\s+type="text"\s+name="element(\d+)\[\]"\s+value=""\s+maxlength="4"\s+size="4"[^>]*>H4b
                <input\s+type="text"\s+name="element\1\[\]"\s+value=""\s+maxlength="5"\s+size="5"[^>]*>H4c\tH4d
                \[HELP\]H4\[/HELP\]
            }isx', $this->f->toHtml(false));
    }

    function testBugTextSingle()
    {
        $_POST['element0'] = "RES";
        $f = new Apeform();
        $this->assertEquals("RES", $f->text());
        $this->assertFalse(is_array($f->_rows[0]['size']));
        $this->assertFalse(is_array($f->_rows[0]['maxLength']));
        $this->assertFalse(is_array($f->_rows[0]['value']));
    }

    function testBugTextMultiple()
    {
        $f = new Apeform();
        $this->assertEquals(array("A", "B"), $f->text("", "", array("A", "B")));
        $this->assertFalse(is_array($f->_rows[0]['size']));
        $this->assertFalse(is_array($f->_rows[0]['maxLength']));
        $this->assertEquals(2, count($f->_rows[0]['value']));
        $this->assertEquals(array("A", ""), $f->text("", "", "A", array(2, 3)));
        $this->assertFalse(is_array($f->_rows[1]['size']));
        $this->assertEquals(2, count($f->_rows[1]['maxLength']));
        $this->assertEquals(2, count($f->_rows[1]['value']));
    }

    function testPassword()
    {
        // Fake the submitted state.
        $this->f->_isSubmitted = true;
        $this->assertEquals("", $this->f->password());
        $this->assertEquals("V", $this->f->password("L", "H", "V"));
        $val = &$this->f->password("L", "H", "V");
        $this->f->error();
        $this->assertTrue($this->f->_hasErrors);
        $this->assertEquals("", $val);
    }

    function testHidden()
    {
        $this->assertEquals("", $this->f->hidden());
        $this->assertEquals("element0", $this->f->getName());
        $this->assertEquals("SPEC", $this->f->hidden("SPEC"));
        $this->assertEquals("element1", $this->f->getName());
        $this->assertEquals("SPECVAL", $this->f->hidden("SPECVAL", "SPECNAME"));
        $this->assertEquals("SPECNAME", $this->f->getName());
        $this->f->id = "UNIQue";
        $this->assertEquals("A", $this->f->hidden("A", "B"));
        $this->assertEquals("B", $this->f->getName());
        $this->assertEquals("C", $this->f->hidden("C"));
        $this->assertEquals("hidden", $this->f->_rows[4]['type']);
        $this->assertEquals("UNIQueelement4", $this->f->_rows[4]['name']);
        $this->assertEquals("C", $this->f->_rows[4]['value']);
        $this->assertEquals("UNIQueelement4", $this->f->getName());

        $this->f->_rows = array();
        $this->f->id = "form";
        $val = &$this->f->hidden("DEFVAL", "NAME");
        $val = "VAL";
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">" .
            "<input type=\"hidden\" name=\"NAME\" value=\"VAL\" />" .
            "[FORM][/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml());
        $this->f->hidden();
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">" .
            "<input type=\"hidden\" name=\"NAME\" value=\"VAL\" />" .
            "<input type=\"hidden\" name=\"element1\" value=\"\" />" .
            "[FORM][/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml());
    }

    function testCheckbox()
    {
        $this->assertEquals("", $this->f->checkbox("lABEl"));
        $this->assertEquals("OP", $this->f->checkbox("L", "", "OP", "OP"));
        $this->assertEquals("", $this->f->checkbox("L", "", "OP", ""));
        $this->assertEquals("", $this->f->checkbox("L", "", "OP"));
        $val = $this->f->checkbox("L", "", "V1|V2", "V2");
        $this->assertEquals(array('V2' => "V2"), $val);
        $val = $this->f->checkbox("L", "", array("E", "F", "G"), "F");
        $this->assertEquals(array('F' => "F"), $val);
        $val = $this->f->checkbox("L", "", "V1|V2");
        $this->assertEquals(array(), $val);
    }

    /* Reported by , not fixed yet */
/*    function testBugCheckboxFirst()
    {
        $_POST = array("element1" => "B");
        $this->assertEquals("", $this->f->checkbox("A"));
        $this->assertEquals(true, $this->f->isValid());
        $this->assertEquals(true, $this->f->submit());
        $this->assertEquals(true, $this->f->isValid());
        unset($_POST);
    } */

    /* Fixed in 2006-06-17, thanks to Julien Huon */
    function testBugFileOnly()
    {
        $_FILES['element0'] = array(
            'name' => "File name with Ümläüts.ext",
            'size' => 0,
            'type' => "imaGe/pJpeg",
            'tmp_name' => null);
        $f = $this->f->file();
        $this->assertEquals(true, $this->f->isValid());
        unset($_FILES);
    }

    function testBugFileLost()
    {
        unset($_FILES);
        $_POST['element0h'] = "a:4:{s:4:\"name\";s:1:\"x\";s:4:\"type\";s:1:\"x\";s:8:\"tmp_name\";s:1:\"x\";s:4:\"size\";i:1;}";
        $this->assertEquals(false, $this->f->file());
        $this->assertEquals(true, $this->f->_isSubmitted);
        $this->assertEquals(true, $this->f->_hasErrors);
        $this->assertEquals(false, $this->f->isValid());
        unset($_POST);
    }

    function testRadio()
    {
        $val = $this->f->radio("Radio", "", "Value 1|Value 2", "Value 2");
        $this->assertEquals("Value 2", $val);
        $val = $this->f->radio("Radio", "", "A|B");
        $this->assertEquals("", $val);
        $val = $this->f->radio("Radio", "", array(5 => "G", 6 => "H"), "H");
        $this->assertEquals(6, $val);
    }

    function testSelect()
    {
        $val = $this->f->select("LABEL", "HELP", "OPTION(S)");
        $this->assertEquals("", $val);
        $val = $this->f->select("Sel", "", array(1 => "A", 2 => "B"), 2);
        $this->assertEquals(2, $val);
        $val = $this->f->select("Sel", "", "A|B", "B");
        $this->assertEquals("B", $val);
        $val = $this->f->select("Sel", "", array('A' => "B", 'C' => "D"), "D");
        $this->assertEquals("C", $val);
        $val = $this->f->select("Sel", "", array("U", "V"), 1);
        $this->assertEquals(1, $val);
    }

    /* Fixed in 2004-12-16, thanks to René Pönitz */
    function testBugHTMLInOptions()
    {
        $this->f->radio("", "", "1&lt;2|3&amp;4");
        $this->assertRegexp('
            {
                <input.*?><label.*?>1&lt;2</label>\s*
                <input.*?><label.*?>3&amp;4</label>

            }isx', $this->f->toHtml(false));
        $this->f->select("", "", "1&lt;2|3&amp;4");
        $this->assertRegexp('
            {
                <option.*?>1&lt;2</option>\s*
                <option.*?>3&amp;4</option>\s*
            }isx', $this->f->toHtml(false));
    }

    /* Fixed in 2009-02-07 */
    function testBugUnquotedHTMLInOptions()
    {
        $this->f->select("", "", array("A" => "<b>&Auml;</b>"));
        $this->assertRegexp('
            {
                <option.*?>&lt;b>&Auml;&lt;/b></option>
            }isx', $this->f->toHtml(false));

        $this->f->select("", "", "<b>&Auml;</b>");
        $this->assertRegexp('
            {
                <option.*?>&lt;b>&Auml;&lt;/b></option>
            }isx', $this->f->toHtml(false));

        $this->f->radio("", "", "<b>&Auml;</b>");
        $this->assertRegexp('
            {
                <input.*?><label.*?><b>&Auml;</b></label>
            }isx', $this->f->toHtml(false));
    }

    /* Fixed in 2005-04-16, thanks to René Pönitz */
    function testBugMultipleTextIDs()
    {
        $this->f->text("L", "", "S");
        $this->assertRegexp('
            {
                <input.*?id="element0i".*?>
            }isx', $this->f->toHtml(false));
        $this->f->text("L", "", array("A", "B"));
        $this->assertRegexp('
            {
                <input.*?id="element1i0".*?>\s*
                <input.*?id="element1i1".*?>
            }isx', $this->f->toHtml(false));
    }

    function testSubmit()
    {
        $this->assertFalse($this->f->submit());
        $this->assertFalse($this->f->submit('"<a>&hellip;"', '"<a>&hellip;"'));
        $this->assertRegexp('
            {
                <input\s+type="submit".*?value="&quot;&lt;a&gt;&hellip;&quot;".*?>\s*
                \[HELP\]"<a>&hellip;"\[/HELP\]
            }isx', $this->f->toHtml(false));
    }

    function testSubmitMultiple()
    {
        $this->assertFalse($this->f->submit(array("X", "Y")));
        $expected = "<form action=\"[PHP_SELF]#form\" id=\"form\" method=\"post\">[FORM]" .
            "[INPUT]<input type=\"submit\" name=\"element0\" value=\"X\" />\n" .
            "<input type=\"submit\" name=\"element0\" value=\"Y\" />\n[/INPUT]" .
            "[/FORM]</form>";
        $this->assertEquals($expected, $this->f->toHtml());
        $this->assertFalse($this->f->submit("A|B|C"));
        $this->assertRegexp('
            {
                <input\s+type="submit"\s+name="element(\d+)"\s+value="A"\s*/?>\s*
                <input\s+type="submit"\s+name="element\1"\s+value="B"\s*/?>\s*
                <input\s+type="submit"\s+name="element\1"\s+value="C"\s*/?>
            }isx', $this->f->toHtml(false));
        $this->assertFalse($this->f->submit(array("V1|\\", "V1b|b")));
        $this->assertRegexp('
            {
                <input\s+type="submit"\s+name="element(\d+)"\s+value="V1\|\"\s*/?>\s*
                <input\s+type="submit"\s+name="element\1"\s+value="V1b\|b"\s*/?>
            }isx', $this->f->toHtml(false));
        $this->assertFalse($this->f->submit("V2|\\\tV2b|b"));
        $this->assertRegexp('
            {
                <input\s+type="submit"\s+name="element(\d+)"\s+value="V2\|\"\s*/?>\s*
                <input\s+type="submit"\s+name="element\1"\s+value="V2b\|b"\s*/?>
            }isx', $this->f->toHtml(false));
        $this->assertFalse($this->f->submit("V3\|\\\|V3b\|b"));
        //- var_dump($this->f->_rows[count($this->f->_rows) - 1]['value']);
        $this->assertRegexp('
            {
                <input\s+type="submit"\s+name="element(\d+)"\s+value="V3\|\"\s*/?>\s*
                <input\s+type="submit"\s+name="element\1"\s+value="V3b\|b"\s*/?>
            }isx', $this->f->toHtml(false));
    }

    function testGetName()
    {
        $this->assertFalse($this->f->getName());
        $this->f->text();
        $this->assertEquals("element0", $this->f->getName());
        $this->f->id = "ID";
        $this->f->text();
        $this->assertEquals("IDelement1", $this->f->getName());
        $this->f->id = "form";
        $this->f->select("LABEL", "HELP", "A|B");
        $this->assertEquals("element2", $this->f->getName());
        $this->f->id = "foRm";
        $this->f->checkbox("");
        $this->assertEquals("foRmelement3", $this->f->getName());
    }

    function testErrorRelative()
    {
        $this->f->_isSubmitted = true;
        $this->assertEquals(array(), $this->f->_rows);
        $this->f->textarea("L1");
        $this->f->error("E1");
        $this->assertEquals("E1", $this->f->_rows[0]['error']);
        $this->f->textarea("L2");
        $this->f->textarea("L3");
        $this->assertTrue(empty($this->f->_rows[1]['error']));
        $this->f->error("E2", -2);
        $this->assertEquals("E2", $this->f->_rows[1]['error']);
        $this->f->error("E2", -3);
        $this->assertEquals("E1", $this->f->_rows[0]['error']);
    }

    function testErrorAbsolute()
    {
        $this->f->_isSubmitted = true;
        $this->f->textarea("L1");
        $this->f->textarea("L2");
        $this->f->textarea("L3");
        $this->f->textarea("L4");
        $this->assertTrue(empty($this->f->_rows[0]['error']));
        $this->f->error("E1", 0);
        $this->assertEquals("E1", $this->f->_rows[0]['error']);
        $this->f->error("E2", 2);
        $this->assertEquals("E2", $this->f->_rows[2]['error']);
    }

    function testAddAttribute()
    {
        $this->assertEquals("", $this->f->text());
        $this->assertEquals("X", $this->f->addAttribute("class", "X"));
        $this->assertEquals("X Y", $this->f->addAttribute("class", "Y"));
        $this->assertRegexp('
            {
                <input\s+type="text"[^>]+class="X\s+Y"
            }isx', $this->f->toHtml());
        $this->assertEquals("disabled", $this->f->addAttribute("disabled"));
        $this->assertRegexp('
            {
                "\s+disabled="disabled"\s+/>
            }isx', $this->f->toHtml());
    }

    function testAddClass()
    {
        $this->f->templates['input'] = "[INPUT{class}][/INPUT]";
        $this->assertEquals("", $this->f->text());
        $this->assertEquals("X", $this->f->addClass("X"));
        $this->assertEquals("X Y", $this->f->addClass("Y"));
        $this->assertEquals("", $this->f->text());
        $this->assertEquals("Z", $this->f->addClass("Z"));
        $this->assertRegexp('
            {
                \[INPUT\s+class="X\s+Y"\]\[/INPUT\]
                \[INPUT\s+class="Z"\]\[/INPUT\]
            }isx', $this->f->toHtml());
    }

    function testBugClassInHeader()
    {
        $this->f->templates['header'] = "[HEADER{class}][/HEADER]";
        $this->assertEquals("A", $this->f->header("A"));
        $this->assertEquals("X", $this->f->addClass("X"));
        $this->assertEquals("X Y", $this->f->addClass("Y"));
        $this->assertRegexp('
            {
                \[HEADER\s+class="X\s+Y"\]\[/HEADER\]
            }isx', $this->f->toHtml());
    }

    function testMagicQuotes()
    {
        $this->assertEquals(get_magic_quotes_gpc(), $this->f->magicQuotes);
        $this->f->magicQuotes = true;
        $this->assertEquals("VALUE\'", $this->f->text("", "", "VALUE'"));
        $this->f->magicQuotes = false;
        $this->assertEquals("VALUE'", $this->f->text("", "", "VALUE'"));
    }

    function testIsValid()
    {
        $this->f->text();
        $this->assertFalse($this->f->isValid());
        // Fake the submitted state.
        $this->f->_isSubmitted = true;
        $this->assertTrue($this->f->isValid());
        $this->f->error();
        $this->assertFalse($this->f->isValid());
    }

    function testMaxlengthHack()
    {
        for ($i = 0; $i < 3; ++$i) $_POST['element' . $i] = str_repeat("a", 256);
        $this->assertEquals(str_repeat("a", 255), $this->f->text());
        $this->assertEquals("a", $this->f->text("", "", "", 1));
        $this->assertEquals("a", $this->f->password("", "", "", 1));
    }

    function testOptionsHack()
    {
        for ($i = 0; $i < 26; ++$i) $_POST['element' . $i] = "invalid";
        for ($i = 2; $i < 7; ++$i) $_POST['element' . $i] = array("invalid");
        $this->assertEquals("", $this->f->checkbox("a"));
        $this->assertEquals("", $this->f->checkbox("", "", "a"));
        $this->assertEquals(array(), $this->f->checkbox("", "", "a|b"));
        $this->assertEquals(array(), $this->f->checkbox("", "", array(1, 2)));
        $this->assertEquals(array(), $this->f->checkbox("", "", array("a", "b")));
        $this->assertEquals(array(), $this->f->checkbox("", "", array(1 => "a", 2 => "b")));
        $this->assertEquals(array(), $this->f->checkbox("", "", array("x" => "a", "y" => "b")));
        $this->assertEquals("", $this->f->radio("", "", "a"));
        $this->assertEquals("", $this->f->radio("", "", "a|b"));
        $this->assertEquals("", $this->f->radio("", "", array(1, 2)));
        $this->assertEquals("", $this->f->radio("", "", array("a", "b")));
        $this->assertEquals("", $this->f->radio("", "", array(1 => "a", 2 => "b")));
        $this->assertEquals("", $this->f->radio("", "", array("x" => "a", "y" => "b")));
        $this->assertEquals("", $this->f->select("", "", "a"));
        $this->assertEquals("", $this->f->select("", "", "a|b"));
        $this->assertEquals("", $this->f->select("", "", array(1, 2)));
        $this->assertEquals("", $this->f->select("", "", array("a", "b")));
        $this->assertEquals("", $this->f->select("", "", array(1 => "a", 2 => "b")));
        $this->assertEquals("", $this->f->select("", "", array("x" => "a", "y" => "b")));
        $this->assertEquals(true, $this->f->submit());
        $this->assertEquals(false, $this->f->submit("option"));
        $this->assertEquals("invalid", $this->f->submit("invalid"));
        $this->assertEquals(false, $this->f->submit("a|b"));
        $this->assertEquals("invalid", $this->f->submit("example|invalid"));
        $this->assertEquals(false, $this->f->submit(array(1, 2)));
        $this->assertEquals(false, $this->f->submit(array("a", "b")));
        $this->assertEquals(false, $this->f->submit(array(1 => "a", 2 => "b")));
        $this->assertEquals(false, $this->f->submit(array("x" => "a", "y" => "b")));
    }

    function testNewlineHack()
    {
        for ($i = 0; $i < 2; ++$i) $_POST['element' . $i] = "in\nvalid";
        $_POST['element2'] = array("a\nb", "c\nd");
        $this->assertEquals("in valid", $this->f->text());
        $this->assertEquals("in valid", $this->f->password());
        $this->assertEquals(array("a b", "c d"), $this->f->text("", "", array("", "")));
    }

    function testNullHack()
    {
        for ($i = 0; $i < 3; ++$i) $_POST['element' . $i] = "a" . chr(0) . "b";
        $this->assertEquals("ab", $this->f->text());
        $this->assertEquals("ab", $this->f->textarea());
        $this->assertEquals("ab", $this->f->password());
    }
}

$suite = new PHPUnit_TestSuite("ApeformTest");
//- $result = PHPUnit::run($suite);
//- echo $result->toHTML();
$gui = new PHPUnit_GUI_HTML($suite);
$gui->show();
