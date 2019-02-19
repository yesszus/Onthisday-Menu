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
function otd_calendar($month, $day)
{
    global $sql;
    $selmonth = $month + 1;
    $sql->db_Select("onthisday", "otd_day", "where otd_month='$selmonth'", "nowhere", false);
    $otd_activedays = array();
    while ($otd_row = $sql->db_Fetch())
    {
        $otd_activedays[] = $otd_row['otd_day'];
    } // while
    // echo"<pre>";
    // print_r($otd_activedays);
    // echo"</pre>";
    $otd_prev = $month-1;
    $otd_next = $month + 1;
    $otd_months = explode(",", OTD_MONTHS);
    $otd_days = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $text .= "<table style='border-width:1px;border-style:solid;'>";
    if ($month > 0)
    {
        $text .= "<tr><td class='forumheader2'><a href='" . e_SELF . "?show.0." . $otd_prev . ".$day'>&lt;</a></td>";
    }
    else
    {
        $text .= "<tr><td class='forumheader2'>&nbsp;</td>";
    }
    $text .= "<td class='forumheader2' style='text-align:center;'colspan='5'>&nbsp;&nbsp;
	" . $otd_months[$month] . "&nbsp;&nbsp;
	</td>
	";
    if ($month < 11)
    {
        $text .= "<td class='forumheader2'><a href='" . e_SELF . "?show.0." . $otd_next . ".$day'>&gt;</a></td>";
    }
    else
    {
        $text .= "<td class='forumheader2'>&nbsp;</td>";
    }
    $column = 0;
    $text .= "</tr>";
    $text .= "<tr><td class='forumheader2' colspan='7' style='text-align:center;'>";
    for($i = 0;$i < 12;$i++)
    {
        $text .= "&nbsp;<a href='" . e_SELF . "?show.0.$i.$day'>" . substr($otd_months[$i], 0, 1) . "</a>&nbsp;";
    }
    $text .= "</td></tr>
	<tr>";

    for ($i = 1;$i <= $otd_days[$month]; $i++)
    {
        if ($column > 6)
        {
            $text .= "</tr>
			\n<tr>";
            $column = 0;
        }
        if ($i == $day)
        {
            $highlight = "background-color:#CC9999; ";
        }
        else
        {
            $highlight = "";
        }
        if (in_array($i, $otd_activedays))
        {
            $otd_active = "*&nbsp;";
        }
        else
        {
            $otd_active = "&nbsp;&nbsp;";
        }
        if ($column == 0 || $column == 6)
        {
            $text .= "<td class='forumheader3' style='text-align:right;$highlight'>$otd_active<a href='" . e_SELF . "?show.0.$month.$i'>" . $i . "</a></td>";
        }
        else
        {
            $text .= "<td class='forumheader3' style='text-align:right;$highlight'>$otd_active<a href='" . e_SELF . "?show.0.$month.$i'>" . $i . "</a></td>";
        }
        $column++;
    }
    if ($column < 7)
    {
        for ($i = $column; $i <= 6; $i++)
        {
            if ($column == 0 || $column == 6)
            {
                $text .= "<td class='forumheader3'>&nbsp;</td>";
            }
            else
            {
                $text .= "<td class='forumheader3'>&nbsp;</td>";
            }
            $column++;
        }
    }
    $text .= "</tr></table>";
    return $text;
}

?>