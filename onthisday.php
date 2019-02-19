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
e107::lan('onthisday_menu', 'admin', true);
// Force cache refresh if a new day
$otd_now = time() + ($pref['time_offset'] * 3600);
$otd_today = date("dmY", $otd_now);
if (date("d", $pref['otd_last']) != date("d", $otd_now))
{
    // $e107cache->clear("nq_otdmenu");
    $e107cache->clear("otd_display");
    $pref['otd_last'] = $otd_now;
    save_prefs();
}
// If cache data available then use it
if ($cacheData = $e107cache->retrieve("otd_display"))
{
    define("e_PAGETITLE", OTD_04);
    require_once(HEADERF);
    echo $cacheData;
}
else
{
    // Otherwise generate a new page
    define("e_PAGETITLE", OTD_04);
    require_once(HEADERF);
    if (!defined("USER_WIDTH"))
    {
        define(USER_WIDTH, "width:100%;");
    }
    $otd_thisday = date("d");
    $otd_thismonth = date("m");
    $otd_currentmonth = explode(",", OTD_MONTHLIST);
    $captiondate = $otd_currentmonth[date("n")-1] ." ".date("j");
  //  $title = $captiondate ;
    if (check_class($pref['otd_readclass']))
    {
        $text = "
		<table class='fborder' style='" . USER_WIDTH . "'>
        	<tr>
        		<td class='fcaption'>$title</td>
			</tr>";
        if ($sql->db_Select("onthisday", "*", "otd_day = $otd_thisday and otd_month = $otd_thismonth order by otd_year"))
        {
            // Events occured on this day
            while ($item = $sql->db_Fetch())
            {
                extract($item);
                $text .= "
			    <tr>
					<td class='forumheader3'> ";
                $text .= "<br /><br /><h4><span style='text-decoration: underline; color: #ff6600;'>" . ($otd_year > 0?OTD_03 . " " . $otd_year:OTD_02) . " ".$captiondate." - <strong>" . $tp->toHTML($otd_brief , true, "emotes_on no_replace") . "</strong></span></h4>";
                $text .= "<br />";
                $text .= $tp->toHTML($otd_full, true, "emotes_on no_replace");
                $text .= "
					</td>
				</tr>";
            } // while;
            $text .= "
						<tr>
				<td class='fcaption'>&nbsp;</td>
			</tr>
			</table>";
        }
        else
        {
            // No records to display
            $text .= "
			<tr>
				<td class='forumheader3'>" . OTDLAN_DEFAULT . "</td>
			</tr>
			<tr>
				<td class='fcaption'>&nbsp;</td>
			</tr>
			</table>";
        }
    }
    else
    {
        // Not in correct class
        $text = "
		<table class='fborder' style='" . USER_WIDTH . "'>
        	<tr>
        		<td class='fcaption'>$title</td>
			</tr>
			<tr>
				<td class='forumheader3'>" . OTD_01 . "</td>
			</tr>
									<tr>
				<td class='fcaption'>&nbsp;</td>
			</tr>
			</table>";
    }
    ob_start(); // Set up a new output buffer
    $ns->tablerender(e_PAGETITLE, $text); // Render the page
    $cache_data = ob_get_flush(); // Get the page content, and display it
    $e107cache->set("otd_display", $cache_data); // Save to cache
}
require_once(FOOTERF);

?>