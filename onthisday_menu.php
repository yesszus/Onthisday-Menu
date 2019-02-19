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
if (!defined('e107_INIT'))
{
    exit;
}
e107::lan('onthisday_menu', 'admin', true);
global $sql,$tp,$pref;
$otd_now = time() + ($pref['time_offset'] * 3600);
$otd_today = date("dmY",$otd_now);
#print date("d",$pref['otd_last']) ." ". date("d",$otd_now);
if (date("d",$pref['otd_last']) != date("d",$otd_now))
{
    $e107cache->clear("nq_otdmenu");
    $e107cache->clear("otd_display");
    $pref['otd_last'] = $otd_now;
    save_prefs();
}
if ($cacheData = $e107cache->retrieve("nq_otdmenu"))
{
    echo $cacheData;
}
else
{
    if (check_class($pref['otd_readclass']))
    {
     //   include_lan(e_PLUGIN . "onthisday_menu/languages/" . e_LANGUAGE . ".php");

        $otd_thisday = date("j");
        $otd_thismonth = date("n");
        $otd_text = "";
        if ($sql->db_Select("onthisday", "*", "where otd_day='$otd_thisday' and otd_month='$otd_thismonth' order by otd_year", "nowhere", false))
        {
            while ($item = $sql->db_Fetch())
            {
                extract($item);
                $otd_text .= "<img src='" . e_PLUGIN . "onthisday_menu/images/bullet2.png' alt='' /> ";
                if ($otd_full)
                {
                    $otd_text .= "<a href='" . e_PLUGIN . "onthisday_menu/onthisday.php?$otd_today'>" . ($otd_year > 0?OTD_03 . " " . $otd_year:OTD_02);
                }

                if ($otd_full)
                {
                    $otd_text .= "</a>";
                }

                $otd_text .= "<br />" . $tp->html_truncate($tp->toHTML($otd_brief, false, "no_make_clickable emotes_off"), $pref['otd_maxlength'], OTD_MORE) . "<br />" ;
            }
            ob_start(); // Set up a new output buffer
            $ns->tablerender(OTDLAN_CAP, $otd_text); // Render the menu
            $cache_data = ob_get_flush(); // Get the menu content, and display it
            $e107cache->set("nq_otdmenu", $cache_data); // Save to cache

        }
        else
        {
            if ($pref['otd_showempty'])
            {
                $otd_text = OTDLAN_DEFAULT;
                ob_start(); // Set up a new output buffer
                $ns->tablerender(OTDLAN_CAP, $otd_text); // Render the menu
                $cache_data = ob_get_flush(); // Get the menu content, and display it
                $e107cache->set("nq_otdmenu", $cache_data); // Save to cache
            }
        }
    }
}

?>