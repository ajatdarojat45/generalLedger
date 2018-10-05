<?php
include "../inc/inc_akses.php";

$s_username = "";
$s_username = $s_username."SELECT mst_menu_parent.mmpar_parent_menu, mst_menu_sub.mmdet_menu_name, mst_menu_sub.mmdet_desc, trans_menu.tmenu_stamp_date ";
$s_username = $s_username."FROM trans_menu ";
$s_username = $s_username."INNER JOIN mst_menu_sub ON trans_menu.tmenu_menu_id = mst_menu_sub.mmdet_menu_id ";
$s_username = $s_username."INNER JOIN mst_menu_parent ON mst_menu_sub.mmdet_parent_id = mst_menu_parent.mmpar_parent_id ";
$s_username = $s_username."WHERE trans_menu.tmenu_username = '$_GET[id_us]' ORDER BY trans_menu.tmenu_stamp_date DESC ";
$q_username = mysql_query($s_username, $conn) or die("Error Query s_parentmenu");
?>
<HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css">
</HEAD>
<BODY>

<table border=0 width="90%">
	<tr>
		<td class="font_label_form" height="30">Detail List Menu User Aktif Login >> <? echo $_GET[id_us]; ?></td>
	</tr>
</table>

<table border=0 width="90%" bgcolor='#FFFFFF'>
	<tr>
		<th class="field_head">No</th>
		<th class="field_head">Parent Menu</th>
		<th class="field_head">Sub Menu</th>
		<th class="field_head">Description</th>
		<th class="field_head">Last Open Form</th>
	</tr>
<?php
$i  = 0;
$no = 0;
WHILE($fetch_username = mysql_fetch_array($q_username)){
	if ($i==0){			// color table per baris
		echo "<tr class='field_data'>";
		$i++;
	}
	else{
		echo "<tr class='field_data'>";
		$i--;
	}

	$no++;

	echo "
		<td>$no</td>
		<td>$fetch_username[0]</td>
		<td>$fetch_username[1]</td>
		<td>$fetch_username[2]</td>
		<td align=right>$fetch_username[3]</td>
	</tr>";
}
echo "</table>";
?>
</BODY>
</HTML>
