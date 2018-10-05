<HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" type="text/css" href="../../style/sdmenu.css" />
<script type="text/javascript" src="../../inc/sdmenu.js">
</script>
<script type="text/javascript">
	var myMenu;
	window.onload = function() {
		myMenu = new SDMenu("my_menu");
		myMenu.init();
		//viewForm();
	};
</script>
</HEAD>
<BODY>
<?php
	$q_parent = "";
	$q_parent = $q_parent."SELECT DISTINCT mst_menu_sub.mmdet_parent_id, mst_menu_parent.mmpar_parent_menu, mst_menu_parent.mmpar_desc ";
	$q_parent = $q_parent."FROM mst_granting_menu ";
	$q_parent = $q_parent."Inner Join mst_menu_sub ON mst_granting_menu.muacces_menu_id = mst_menu_sub.mmdet_menu_id ";
	$q_parent = $q_parent."Inner Join mst_menu_parent ON mst_menu_sub.mmdet_parent_id = mst_menu_parent.mmpar_parent_id ";
	$q_parent = $q_parent."WHERE mst_menu_sub.mmdet_status =  '1' AND mst_menu_parent.mmpar_status =  '1' AND muacces_username = '$_SESSION[app_glt]' ";
	$q_parent = $q_parent."ORDER BY mst_menu_sub.mmdet_parent_id ";
	$s_parent = mysql_query($q_parent, $conn) or die("Error Query Show Parent Menu ");
?>
<table border="0">
	<tr>
		<td>
		<?php
			echo "<div style='float: left;' id='my_menu' class='sdmenu'>";
			
			WHILE($fet_parent = mysql_fetch_array($s_parent)){
				$q_user_access = "";
				$q_user_access = $q_user_access."SELECT mst_menu_sub.mmdet_parent_id, mst_menu_sub.mmdet_sort_list, mst_menu_sub.mmdet_menu_name, ";
				$q_user_access = $q_user_access."mst_menu_sub.mmdet_desc, mst_menu_sub.mmdet_url, mst_menu_sub.mmdet_target_frame, mst_menu_sub.mmdet_menu_id ";
				$q_user_access = $q_user_access."FROM mst_granting_menu ";
				$q_user_access = $q_user_access."Inner Join mst_menu_sub ON mst_granting_menu.muacces_menu_id = mst_menu_sub.mmdet_menu_id ";
				$q_user_access = $q_user_access."WHERE mst_menu_sub.mmdet_status =  '1' AND mst_menu_sub.mmdet_parent_id = '$fet_parent[0]' AND muacces_username = '$_SESSION[app_glt]' ";
				$q_user_access = $q_user_access."ORDER BY mst_menu_sub.mmdet_parent_id, mst_menu_sub.mmdet_menu_id, mst_menu_sub.mmdet_sort_list ";
				$q_user_access = mysql_query($q_user_access, $conn) or die("Error Query Sub Access Level");
				echo "
				<div>
					<span>$fet_parent[1]</span>";					
					WHILE ($fet_submenu = mysql_fetch_array($q_user_access)){						
						echo "<a href = '$fet_submenu[4]?id_menu=$fet_submenu[6]' target = '$fet_submenu[5]'>$fet_submenu[2]</a>";
					}
				echo "
				</div>";
			}
			echo "</div>";
		?>
		</td>
	</tr>
</table>
</BODY>
</HTML>