<?php
/*
+---------------------------------------------------------------+
|        On This Day Menu for e107 v7xx - by Father Barry
|
|        This module for the e107 .7+ website system
|        Copyright Barry Keal 2004-2008
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
|
+---------------------------------------------------------------+
*/
require_once("../../class2.php");
if (!defined('e107_INIT'))
{
    exit;
}
if (!getperms("P"))
{
    header("location:" . e_HTTP . "index.php");
    exit;
}

e107::lan('onthisday_menu', 'admin', true);

require_once(e_ADMIN . "auth.php");
if (!defined('ADMIN_WIDTH'))
{
    define(ADMIN_WIDTH, "width:100%;");
}
require_once(e_HANDLER . "userclass_class.php");
if (e_QUERY == "update")
{
    // Update rest
    $pref['otd_showempty'] = $_POST['otd_showempty'];
    $pref['otd_maxlength'] = $_POST['otd_maxlength'];
    $pref['otd_readclass'] = $_POST['otd_readclass'];
    $pref['otd_dateformat'] = $_POST['otd_dateformat'];
    save_prefs();
    $e107cache->clear("nq_otdmenu");
    $e107cache->clear("otd_display");
    $otd_msgtext = "<tr><td class='forumheader3' colspan='2'><strong>" . OTD_A04 . "</strong></td></tr>";
}

$otd_text .= "<form method='post' action='" . e_SELF . "?update' id='confotd'>
<table style='" . ADMIN_WIDTH . "' class='fborder'>
<tr><td colspan='2' class='fcaption'>" . OTD_A05 . "</td></tr>
 $otd_msgtext";
// Show class
$otd_text .= "
<tr>
<td style='width:30%' class='forumheader3'>" . OTD_A10 . "</td>
<td style='width:70%' class='forumheader3'>" . r_userclass("otd_readclass", $pref['otd_readclass'], "off", 'public,guest, nobody, member, admin, classes') . "
</td></tr>";
$otd_text .= "
<tr>
<td style='width:30%' class='forumheader3'>" . OTD_A06 . "</td>
<td style='width:70%' class='forumheader3'>
<select name='otd_showempty' class='tbox'>
	<option value='0' " . ($pref['otd_showempty'] != 1?"selected = 'selected'":"") . ">" . OTD_A08 . "</option>
	<option value='1' " . ($pref['otd_showempty'] == 1?"selected = 'selected'":"") . ">" . OTD_A07 . "</option>
</select>

</td>
</tr>";
$otd_text .= "
<tr>
<td style='width:30%' class='forumheader3'>" . OTD_A18 . "</td>
<td style='width:70%' class='forumheader3'>
	<input type='text' class='tbox' name='otd_maxlength' value='" . $pref['otd_maxlength'] . "' />
</td></tr>";
$otd_text .= "
<tr>
<td style='width:30%' class='forumheader3'>" . OTD_A19 . "</td>
<td style='width:70%' class='forumheader3'>
	<select name='otd_dateformat' class='tbox'>
	<option value='dmy' " . ($pref['otd_dateformat'] == "dmy"?"selected='selected'":"") . ">d-m-y</option>
	<option value='mdy' " . ($pref['otd_dateformat'] == "mdy"?"selected='selected'":"") . ">m-d-y</option>
	<option value='ymd' " . ($pref['otd_dateformat'] == "ymd"?"selected='selected'":"") . ">y-m-d</option>
</select>
</td></tr>";
// Submit button
$otd_text .= "
<tr>
<td colspan='2' class='fcaption' style='text-align: left;'><input type='submit' name='update' value='" . OTD_A09 . "' class='button' />\n
</td>
</tr>";
$otd_text .= "</table></form>";
$ns->tablerender(OTD_A01, $otd_text);
require_once(e_ADMIN . "footer.php");

?>
