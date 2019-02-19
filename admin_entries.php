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
$e_wysiwyg = "otd_full";
if ($pref['wysiwyg'])
{
    $WYSIWYG = true;
}
require_once(e_ADMIN . "auth.php");
if (!defined('ADMIN_WIDTH'))
{
    define(ADMIN_WIDTH, "width:100%;");
}
require_once(e_HANDLER . "ren_help.php");
require_once("otd_functions.php");
$otd_currentmonths = explode(",", OTD_MONTHS);
if (e_QUERY)
{
    $otd_temp = explode(".", e_QUERY);
    $otd_action = $otd_temp[0];
    $otd_currentid = $otd_temp[1];
    $otd_currentmonth = $otd_temp[2];
    $otd_currentday = $otd_temp[3];
}
else
{
    $otd_action = $_POST['otd_action'];
    $otd_currentid = $_POST['otd_currentid'];
    $otd_currentmonth = $_POST['otd_currentmonth'];
    $otd_currentday = $_POST['otd_currentday'];
}
if (!isset($otd_action))
{
    $otd_action = "show";
}

if (!isset($otd_currentmonth))
{
    $otd_currentmonth = 0;
}
if ($otd_currentday < 1)
{
    $otd_currentday = 1;
}
if ($otd_action == "dodel")
{
    $sql->db_Delete("onthisday", "otd_id='$otd_currentid'", false);
    $otd_action = "show";
    $e107cache->clear("nq_otdmenu");
    $e107cache->clear("otd_display");
}
if ($otd_action == "delete")
{
    if ($sql->db_Select("onthisday", "*", "otd_id=$otd_currentid"))
    {
        $otd_row = $sql->db_Fetch();
        extract($otd_row);
        $otd_monthsel = $otd_month -1 ;
        $otd_text = "<table class='fborder' style='".ADMIN_WIDTH."' >
	<tr><td class='fcaption'>" . OTD_A30 . "</td></tr>
	<tr><td class='fcaption'>" . OTD_A26 . "<br /><br /><strong>" . $tp->toHTML($otd_brief, false) . "</strong><br />
	" . OTD_A33 . ": $otd_year - " . OTD_A32 . ": " . $otd_currentmonths[$otd_monthsel] . " - " . OTD_A31 . ": $otd_day.
	<br /><br />" . OTD_A27 . "<br />
	<a href='" . e_SELF . "?dodel.$otd_id.$otd_currentmonth.$otd_currentday' >" . OTD_A28 . "</a>&nbsp;&nbsp;&nbsp
	<a href='" . e_SELF . "?show.$otd_id.$otd_currentmonth.$otd_currentday' >" . OTD_A29 . "</a>
	</td></tr>

	</table>";
    }
}
if ($otd_action == "addnew")
{
    if (!empty($_POST['otd_brief']) && !empty($_POST['otd_day']) && !empty($_POST['otd_month']))
    {
        // Create new record
        $otd_arg = "0,
	   '" . $tp->toDB($_POST['otd_brief']) . "',
	   '" . $tp->toDB($_POST['otd_day']) . "',
	   '" . $tp->toDB($_POST['otd_month']) . "',
	   '" . $tp->toDB($_POST['otd_year']) . "',
	   '" . $tp->toDB($_POST['otd_full']) . "'";

        $sql->db_Insert("onthisday", $otd_arg);
        $e107cache->clear("nq_otdmenu");
        $e107cache->clear("otd_display");
    }
    $otd_action = "show";
}
if ($otd_action == "save")
{
    if ($_POST['otd_id'] > 0)
    {
        // saving existing record
        $otd_arg = "
			otd_brief='" . $tp->toDB($_POST['otd_brief']) . "',
			otd_day='" . $tp->toDB($_POST['otd_day']) . "',
			otd_month='" . $tp->toDB($_POST['otd_month']) . "',
			otd_year='" . $tp->toDB($_POST['otd_year']) . "',
			otd_full='" . $tp->toDB($_POST['otd_full']) . "'
			where otd_id='" . $_POST['otd_id'] . "'";
        $sql->db_Update("onthisday", $otd_arg);
        $e107cache->clear("nq_otdmenu");
        $e107cache->clear("otd_display");
    }

    $otd_action = "show";
}
if ($otd_action == "add")
{
    $otd_currentmonths = explode(",", OTD_MONTHS);
    $otd_selmonth = $otd_currentmonth + 1;

    $otd_text = "
	<form id='dataform' action='" . e_SELF . "' method='post'>
	<div id='otdvar'>
	<input type='hidden' name='otd_action' value='addnew' />
	<input type='hidden' name='otd_currentmonth' value='$otd_currentmonth' />
	<input type='hidden' name='otd_currentday' value='$otd_currentday' />
	</div>
	<table class='fborder' style='".ADMIN_WIDTH."'>
	<tr><td colspan='2' class='forumheader2'>" . $otd_currentmonths[$otd_currentmonth] . " " . $otd_currentday . " " . OTD_A56 . "</td></tr>";

    $otd_text .= "<tr><td class='forumheader3'>" . OTD_A12 . ":</td>
			<td class='forumheader3'>
				<input type='text' style='width:80%' class='tbox' name='otd_brief' value='" . $tp->toFORM($otd_brief) . "' />
				<input type='hidden' name='otd_id' value='$otd_id' />
			</td></tr>";
    $otd_text .= "<tr><td class='forumheader3'>" . OTD_A17 . ":</td>
			<td class='forumheader3'>
			    <input type='text' size='5' maxlength='4' class='tbox' name='otd_year' value='" . $tp->toFORM($otd_year) . "' /> " . OTD_A15 . "&nbsp;&nbsp;&nbsp;				
				<input type='text' size='5' maxlength='2' class='tbox' name='otd_month' value='" . $tp->toFORM($otd_selmonth) . "' /> " . OTD_A14 . "&nbsp;&nbsp;&nbsp;
				<input type='text' size='5' maxlength='2' class='tbox' name='otd_day' value='" . $tp->toFORM($otd_currentday) . "' /> " . OTD_A13 . "</td></tr>";
    $otd_text .= "<tr><td class='forumheader3'>" . OTD_A16 . ":</td>
<td class='forumheader3'>";
    $insertjs = (!$pref['wysiwyg'])?"rows='10' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'":
    "rows='20' style='width:100%' ";
    $otd_full = $tp->toForm($otd_full);
    $otd_text .= "<textarea class='tbox' id='otd_full' name='otd_full' cols='80'  style='width:95%' $insertjs>" . (strstr($otd_full, "[img]http") ? $otd_full : str_replace("[img]../", "[img]", $otd_full)) . "</textarea>";

    if (!$pref['wysiwyg'])
    {
        $otd_text .= "<input id='helpb' class='helpbox' type='text' name='helpb' size='100' style='width:95%'/>
			<br />" . display_help("helpb");
    }
    $otd_text .= "</td></tr>";
    $otd_text .= "<tr>
		<td class='forumheader3' colspan='2'>
			<input type='submit' class='tbox' name='submitit' value='" . OTD_A09 . "' />
		</td></tr>";
    $otd_text .= "</table></form>";
}
if ($otd_action == "edit")
{
    $otd_text = "
	<form id='dataform' action='" . e_SELF . "' method='post'>
	<div id='otdvar'>
	<input type='hidden' name='otd_action' value='save' />
	<input type='hidden' name='otd_currentmonth' value='$otd_currentmonth' />
	<input type='hidden' name='otd_currentday' value='$otd_currentday' />
	</div>
	<table class='fborder' style='".ADMIN_WIDTH."'>
	<tr><td colspan='2' class='forumheader2'>" . $otd_currentmonths[$otd_currentmonth] . " " . $otd_currentday . " " . OTD_A11 . "</td></tr>";

    if ($sql->db_Select("onthisday", "*", "otd_id='$otd_currentid' "))
    {
        while ($otd_row = $sql->db_Fetch())
        {
            extract($otd_row);

            $otd_text .= "<tr><td class='forumheader3'>" . OTD_A12 . ":</td>
			<td class='forumheader3'>
				<input type='text' style='width:80%' class='tbox' name='otd_brief' value='" . $tp->toFORM($otd_brief) . "' />
				<input type='hidden' name='otd_id' value='$otd_id' />
			</td></tr>";
            $otd_text .= "<tr><td class='forumheader3'>" . OTD_A17 . ":</td>
			<td class='forumheader3'>
				<input type='text' size='5' maxlength='4' class='tbox' name='otd_year' value='" . $tp->toFORM($otd_year) . "' /> " . OTD_A15 . "&nbsp;&nbsp;&nbsp;
				<input type='text' size='5' maxlength='2' class='tbox' name='otd_month' value='" . $tp->toFORM($otd_month) . "' /> " . OTD_A14 . "&nbsp;&nbsp;&nbsp;
				<input type='text' size='5' maxlength='2' class='tbox' name='otd_day' value='" . $tp->toFORM($otd_day) . "' /> " . OTD_A13 . "</td></tr>";

            $otd_text .= "<tr><td class='forumheader3'>" . OTD_A16 . ":</td>
			<td class='forumheader3'>";
            $insertjs = (!$pref['wysiwyg'])?"rows='10' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'":
            "rows='20' style='width:100%' ";
            $otd_full = $tp->toForm($otd_full);
            $otd_text .= "<textarea class='tbox' id='otd_full' name='otd_full' cols='80'  style='width:95%' $insertjs>" . (strstr($otd_full, "[img]http") ? $otd_full : str_replace("[img]../", "[img]", $otd_full)) . "</textarea>";

            if (!$pref['wysiwyg'])
            {
                $otd_text .= "<input id='helpb' class='helpbox' type='text' name='helpb' size='100' style='width:95%'/>
			<br />" . display_help("helpb");
            }
            $otd_text .= "</td></tr>";
            $otd_text .= "<tr>
		<td class='forumheader3' colspan='2'>
			<input type='submit' class='button' name='submitit' value='" . OTD_A09 . "' />
		</td></tr>";
        }
    }

    $otd_text .= "</table></form>";
}
if ($otd_action == "show")
{
    $otd_text .= "<table class='fborder' style='".ADMIN_WIDTH."'>
<tr><td class='forumheader' colspan='2'>" . OTD_A01 . "</td></tr>
<tr><td class='forumheader3' colspan='2' style='text-align:center;'>" . otd_calendar($otd_currentmonth, $otd_currentday) . "</td></tr>";

    $otd_text .= "</table>";

    $otd_selmonth = $otd_currentmonth + 1;
    $otd_text .= "
	<table class='fborder' style='".ADMIN_WIDTH."'>
		<tr>
			<td class='fcaption' colspan='2'>" . OTD_A24 . " - <strong>" . $otd_currentmonths[$otd_currentmonth] . " $otd_currentday</strong></td>
		</tr>";

    if ($sql->db_Select("onthisday", "*", "where otd_month='$otd_selmonth' and otd_day='$otd_currentday'", "nowhere", false))
    {
        while ($otd_row = $sql->db_Fetch())
        {
            extract($otd_row);

            $otd_text .= "
		<tr>
			<td class='forumheader3' style='width:70%'>" . $tp->toHTML($otd_brief, false) . "</td>
			<td class='forumheader3' style='width:30%;text-align:center;'>
			<a href='" . e_SELF . "?edit.$otd_id.$otd_currentmonth.$otd_currentday' ><img src='" . e_IMAGE . "admin_images/edit_16.png' alt='edit' /></a>&nbsp;&nbsp;
			<a href='" . e_SELF . "?delete.$otd_id.$otd_currentmonth.$otd_currentday' ><img src='" . e_IMAGE . "admin_images/delete_16.png' alt='delete' /></a></td>
		</tr>";
        }
    }
    else
    {
        $otd_text .= "
		<tr>
			<td class='forumheader3' colspan='2'>" . OTD_A25 . "</td>
		</tr>";
    }
    $otd_text .= "
		<tr>
			<td class='forumheader3' colspan='2'>
				<a href='" . e_SELF . "?add.0.$otd_currentmonth.$otd_currentday'>" . OTD_A21 . "</a>
			</td>
		</tr>
	</table>";
}

$ns->tablerender(OTD_A01, $otd_text);

require_once(e_ADMIN . "footer.php");

?>