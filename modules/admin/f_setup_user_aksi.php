<? session_start();
//print_r($_POST); 
//print_r($_GET); 

if (isset($_SESSION['app_glt'])) {

		include("../inc/inc_procedure.php"); 
		include("../inc/inc_akses.php");
		include("../inc/inc_aed.php");
		
		//echo "$tmbl_add , $tmbl_edit, $tmbl_del ";

		if($_GET[btn_save_user]=='true'){				
			
			if($_GET[kd_aksi]=='EDIT') { 
			
				$upd_user = "UPDATE mst_login SET mlog_name='$_POST[txt_fullname]' , mlog_keterangan='$_POST[txt_ket]', mlog_email='$_POST[txt_email]' ,mlog_stamp_date=now() WHERE mlog_username='$_GET[id_us]'" ;
				$que_saved = mysql_query($upd_user, $conn) or die("Error Query Update User Description") ;			
				$textout="Update Success !";
			}
			
			if($_GET[kd_aksi]=='ADD') { 
				
				//$n_passwrd = $app_glt."24".$_POST[tx_password]."086666";
				//$nue_pass = md5(md5("wawan".$n_passwrd));

				
				$n_passwrd = $_POST[txt_username]."24".$_POST[txt_password]."086666";
				$nue_passwrd = md5(md5("wawan".$n_passwrd));

				$s_user = "";
				$s_user ="INSERT INTO mst_login (mlog_username, mlog_password, mlog_name, mlog_status, mlog_stamp_date, mlog_stamp_user, mlog_email, mlog_keterangan ) VALUES ( '$_POST[txt_username]', '$nue_passwrd', '$_POST[txt_fullname]', '1', '$_POST[cdatetime]', '$_SESSION[app_glt]', '$_POST[txt_email]', '$_POST[txt_ket]' )";
				$que_saved = mysql_query($s_user, $conn) or die("Error Query add user");			
				$textout="Add new data success !";
			}
			
			if($_GET[kd_aksi]=='DELETE') { 
				$del_user_com = "UPDATE mst_login SET mlog_status='0' WHERE mlog_username='$_GET[id_us]' " ;
				$que_saved=mysql_query($del_user_com, $conn) or die("Error Query Deactived User") ; 
				$textout="Deactived Success !";
			}
			if($_GET[kd_aksi]=='AKTIFKAN') { 
				$del_user_com = "UPDATE mst_login SET mlog_status='1' WHERE mlog_username='$_GET[id_us]' " ;
				$que_saved=mysql_query($del_user_com, $conn) or die("Error Query Actived User") ; 
				$textout="Actived Success !";
			}
			if($_GET[kd_aksi]=='RESET') { 
				$n_passwrd = $_GET[txt_username]."24".$_GET[txt_password]."086666";
				$nue_passwrd = md5(md5("wawan".$n_passwrd));
				
				$upd_user = "";
				$upd_user = "UPDATE mst_login SET mlog_password='$nue_passwrd', mlog_stamp_date='$_POST[cdatetime]' WHERE mlog_username='$_GET[id_us]'" ;
				
				$que_saved = mysql_query($upd_user, $conn) or die("Error Query Update User Description") ;			
				$textout="Password Reset Success !";
			}
			
		}
		
		//-------------END POST SAVE USER----------------
		
		
		if($_GET[btn_save_company]=='true'){
										
			//	Granting Tabel Perusahaan
			$sel_pt = "DELETE FROM mst_granting_company WHERE mgc_username='$_GET[id_us]'";
			$que_pt = mysql_query($sel_pt, $conn) or die("Error Query Delete user Company");
			
			$sel_pt = "SELECT * FROM mst_company WHERE mcom_status='1' ORDER BY mcom_id ASC";
			$que_pt = mysql_query($sel_pt, $conn) or die("Error Query Select Company");
			
			$no=0;
			WHILE($fet_sel_pt = mysql_fetch_array($que_pt)){
				$no ++;					

				$chk_pil_pt="ckb_pil_pt_".$fet_sel_pt[0] ;			
				$chk_def_pt="ckb_def_pt_".$fet_sel_pt[0] ;
				$chk_date_pt="ckb_date_pt_".$fet_sel_pt[0] ;

				//echo " Save..... $_GET[id_us] $_GET[$chk_pil_pt].....";			
				
				
				//echo "posted-> $_POST[ckb_pil_pt_1] <br>";			
				
				if ($_POST[$chk_pil_pt]){
					//echo "$no posted ";			
					// Isi hak akses perusahaan
					$ins_user_com="INSERT INTO mst_granting_company (mgc_username,mcom_id,mcom_periode) VALUES ('$_GET[id_us]',$fet_sel_pt[0],'N')";
					
					//echo $ins_user_com ;
					
					$query=mysql_query($ins_user_com, $conn) or die("Error Query Insert Company User Description") ;
				} 
							
				if ($_POST[$chk_def_pt]){
					//Update Default akses perusahaan
					$upd_user = "UPDATE mst_login SET mcom_id='$fet_sel_pt[0]', mcom_id_last='$fet_sel_pt[0]',aktif_tgl='$fet_sel_pt[sys_tgl]' WHERE mlog_username='$_GET[id_us]'" ;
					//echo $upd_user  ;
					
					$query=mysql_query($upd_user, $conn) or die("Error Query Update User Company Default Access") ;
				} 
							
				if ($_POST[$chk_date_pt]){
					//Update Active Date perusahaan
					$upd_user = "UPDATE mst_granting_company SET mcom_periode='Y' WHERE mgc_username='$_GET[id_us]' AND mcom_id='$fet_sel_pt[0]'" ;		
					//echo $upd_user;
									
					$query=mysql_query($upd_user, $conn) or die("Error Query Update User Company Active Periode") ;
				}
			}
			$que_saved = 1;
			$textout="Company Update Success !";
		}	
		
		if($_GET[btn_save_menu]=='true'){
			
					
			$sh_parent = "";
			$sh_parent = $sh_parent."SELECT mst_menu_parent.mmpar_parent_menu, mst_menu_parent.mmpar_parent_id FROM mst_menu_parent ";
			$sh_parent = $sh_parent."WHERE mst_menu_parent.mmpar_status =  '1' ORDER BY mst_menu_parent.mmpar_parent_id ASC ";
			$que_parent = mysql_query($sh_parent, $conn) or die("Error Query Show Parent");
			
			$no =0;
			
			WHILE($fet_sh_parent = mysql_fetch_array($que_parent)){
				
				//--Showing Submenu Level
				$sh_sub = "";
				$sh_sub = $sh_sub."SELECT mst_menu_sub.mmdet_sort_list, mst_menu_sub.mmdet_menu_name, mst_menu_sub.mmdet_menu_id ";
				$sh_sub = $sh_sub."FROM mst_menu_sub WHERE mst_menu_sub.mmdet_status = '1' AND mst_menu_sub.mmdet_parent_id = '$fet_sh_parent[1]' ";
				$sh_sub = $sh_sub."ORDER BY mst_menu_sub.mmdet_menu_id, mst_menu_sub.mmdet_sort_list ";
				$que_sub = mysql_query($sh_sub, $conn) or die("Error Query sh_sub");

				$ins_stat = 0;
				WHILE($ft_shsub = mysql_fetch_array($que_sub)){
					
					$no++;
					$ckb_submenu = "ckb_submenu_".$no;
					$ckb_add = "ckb_add_".$no;
					$ckb_edit = "ckb_edit_".$no;
					$ckb_del = "ckb_del_".$no;

					if($_POST[$ckb_submenu]){				

					//--check available data in database
						$check_present = "";
						$check_present = mysql_query("SELECT mst_granting_menu.muacces_username, mst_granting_menu.muacces_menu_id FROM mst_granting_menu WHERE mst_granting_menu.muacces_username = '$_GET[id_us]' AND  mst_granting_menu.muacces_menu_id = '$_POST[$ckb_submenu]' ", $conn) or die("Error Query Check Keberadaan Current Menu");
						$row_present = mysql_num_rows($check_present);

						if($row_present){
						}
						else{
							//--insert from form
							$ins_present = "";
							$ins_present = $ins_present."INSERT INTO mst_granting_menu VALUES ( ";
							$ins_present = $ins_present."'$_GET[id_us]','$_POST[$ckb_submenu]','$_POST[cdatetime]','$_SESSION[app_glt]' ) ";
							
							$ins_present = mysql_query($ins_present, $conn) or die("Error Query Insert Granting Menu");
						}
					}
					else{
						//--check available data in database
						$check_present = "";
						$check_present = mysql_query("SELECT mst_granting_menu.muacces_username, mst_granting_menu.muacces_menu_id FROM mst_granting_menu WHERE mst_granting_menu.muacces_username = '$_GET[id_us]' AND  mst_granting_menu.muacces_menu_id = '$ft_shsub[2]' ", $conn) or die("Error Query Check Keberadaan Current Menu");
						$row_present = mysql_num_rows($check_present);

						if($row_present){
							//--delete from table
							$del_present = "";
							$del_present = mysql_query("DELETE FROM mst_granting_menu WHERE muacces_username = '$_GET[id_us]' AND muacces_menu_id = '$ft_shsub[2]' ", $conn) or die("Error Query Delete");
						}
						else{
						}

					}
					//--end form
					
					//================================================================
					
					//--start add
					if($_POST[$ckb_add]){
						//--check available data in database
						$ck_access_add = "";
						$ck_access_add = $ck_access_add."SELECT * FROM mst_granting_button ";
						$ck_access_add = $ck_access_add."WHERE mst_granting_button.mgbut_username = '$_GET[id_us]' AND mst_granting_button.mgbut_menu_id = '$_POST[$ckb_submenu]' ";
						$ck_access_add = $ck_access_add."AND mst_granting_button.mgbut_button_id = '$_POST[$ckb_add]' ";
						$qr_access_add = mysql_query($ck_access_add, $conn) or die("Error Query Check Available Access ADD");
						$row_present_add = mysql_num_rows($qr_access_add);
						
						if($row_present_add){
							//nothing
						}
						else{
							//--insert from form
							$ins_access_add = "";
							$ins_access_add = $ins_access_add."INSERT INTO mst_granting_button VALUES( ";
							$ins_access_add = $ins_access_add."'$_GET[id_us]', '$_POST[$ckb_submenu]', '$_POST[$ckb_add]', '$_POST[cdatetime]', '$_SESSION[app_glt]') ";
							$que_access_add = mysql_query($ins_access_add, $conn) or die("Error Query Insert Button Access");
						}
					}
					else{
						//--check available data in database
						$ck_access_add = "";
						$ck_access_add = $ck_access_add."SELECT * FROM mst_granting_button ";
						$ck_access_add = $ck_access_add."WHERE mst_granting_button.mgbut_username = '$_GET[id_us]' AND mst_granting_button.mgbut_menu_id = '$ft_shsub[2]' ";
						$ck_access_add = $ck_access_add."AND mst_granting_button.mgbut_button_id = '1' ";
						$qr_access_add = mysql_query($ck_access_add, $conn) or die("Error Query Check Available Access ADD");
						$row_present_add = mysql_num_rows($qr_access_add);

						if($row_present_add){
							//--delete from table
							$del_access_add = "";
							$del_access_add = $del_access_add."DELETE FROM mst_granting_button WHERE mgbut_username = '$_GET[id_us]' AND mgbut_menu_id = '$ft_shsub[2]' AND mgbut_button_id = '1' ";
							$que_access_add = mysql_query($del_access_add, $conn) or die("Error Delete Button Access Add");
						}
						else{
							//nothing
						}

					}
					//--end add
					
					//================================================================

					
					//--start edit
					if($_POST[$ckb_edit]){
						//--check available data in database
						$ck_access_edit = "";
						$ck_access_edit = $ck_access_edit."SELECT * FROM mst_granting_button ";
						$ck_access_edit = $ck_access_edit."WHERE mst_granting_button.mgbut_username = '$_GET[id_us]' AND mst_granting_button.mgbut_menu_id = '$_POST[$ckb_submenu]' ";
						$ck_access_edit = $ck_access_edit."AND mst_granting_button.mgbut_button_id = '$_POST[$ckb_edit]' ";
						$qr_access_edit = mysql_query($ck_access_edit, $conn) or die("Error Query Check Available Access EDIT");
						$row_present_edit = mysql_num_rows($qr_access_edit);
						
						if($row_present_edit){
							//nothing
						}
						else{
							//--insert from form
							$ins_access_edit = "";
							$ins_access_edit = $ins_access_edit."INSERT INTO mst_granting_button VALUES( ";
							$ins_access_edit = $ins_access_edit."'$_GET[id_us]', '$_POST[$ckb_submenu]', '$_POST[$ckb_edit]', '$_POST[cdatetime]', '$_SESSION[app_glt]') ";
							$que_access_edit = mysql_query($ins_access_edit, $conn) or die("Error Query Insert Button Access");
						}
					}
					else{
						//--check available data in database
						$ck_access_edit = "";
						$ck_access_edit = $ck_access_edit."SELECT * FROM mst_granting_button ";
						$ck_access_edit = $ck_access_edit."WHERE mst_granting_button.mgbut_username = '$_GET[id_us]' AND mst_granting_button.mgbut_menu_id = '$ft_shsub[2]' ";
						$ck_access_edit = $ck_access_edit."AND mst_granting_button.mgbut_button_id = '2' ";
						$qr_access_edit = mysql_query($ck_access_edit, $conn) or die("Error Query Check Available Access EDIT");
						$row_present_edit = mysql_num_rows($qr_access_edit);

						if($row_present_edit){
							//--delete from table
							$del_access_edit = "";
							$del_access_edit = $del_access_edit."DELETE FROM mst_granting_button WHERE mgbut_username = '$_GET[id_us]' AND mgbut_menu_id = '$ft_shsub[2]' AND mgbut_button_id = '2' ";
							$que_access_edit = mysql_query($del_access_edit, $conn) or die("Error Delete Button Access Add");
						}
						else{
							//nothing
						}

					}
					//--end edit
					
					//================================================================

					//--start delete
					if($_POST[$ckb_del]){
						//--check available data in database
						$ck_access_del = "";
						$ck_access_del = $ck_access_del."SELECT * FROM mst_granting_button ";
						$ck_access_del = $ck_access_del."WHERE mst_granting_button.mgbut_username = '$_GET[id_us]' AND mst_granting_button.mgbut_menu_id = '$_POST[$ckb_submenu]' ";
						$ck_access_del = $ck_access_del."AND mst_granting_button.mgbut_button_id = '$_POST[$ckb_del]' ";
						$qr_access_del = mysql_query($ck_access_del, $conn) or die("Error Query Check Available Access DELETE");
						$row_present_del = mysql_num_rows($qr_access_del);
						
						if($row_present_del){
							//nothing
						}
						else{
							//--insert from form
							$ins_access_del = "";
							$ins_access_del = $ins_access_del."INSERT INTO mst_granting_button VALUES( ";
							$ins_access_del = $ins_access_del."'$_GET[id_us]', '$_POST[$ckb_submenu]', '$_POST[$ckb_del]', '$_POST[cdatetime]', '$_SESSION[app_glt]') ";
							$que_access_del = mysql_query($ins_access_del, $conn) or die("Error Query Insert Button Access");
						}
					}
					else{
						//--check available data in database
						$ck_access_del = "";
						$ck_access_del = $ck_access_del."SELECT * FROM mst_granting_button ";
						$ck_access_del = $ck_access_del."WHERE mst_granting_button.mgbut_username = '$_GET[id_us]' AND mst_granting_button.mgbut_menu_id = '$ft_shsub[2]' ";
						$ck_access_del = $ck_access_del."AND mst_granting_button.mgbut_button_id = '3' ";
						$qr_access_del = mysql_query($ck_access_del, $conn) or die("Error Query Check Available Access EDIT");
						$row_present_del = mysql_num_rows($qr_access_del);

						if($row_present_del){
							//--delete from table
							$del_access_del = "";
							$del_access_del = $del_access_del."DELETE FROM mst_granting_button WHERE mgbut_username = '$_GET[id_us]' AND mgbut_menu_id = '$ft_shsub[2]' AND mgbut_button_id = '3' ";
							$que_access_del = mysql_query($del_access_del, $conn) or die("Error Delete Button Access Add");
						}
						else{
							//nothing
						}
					}
				//--end delete
				
				//====================
				}
			}
			$que_saved = 1;
			$textout="Menu Update Success !";
		}
		
		

?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GL TEMPO</title>

<!-- Bootstrap core CSS -->
    <link href="../../bootstrap-3/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet">
    <link href="../../style/style_utama.css" rel="stylesheet">
</head>
<body>
<? if ($_GET[kd_aksi]=='ADD' || $_GET[kd_aksi]=='EDIT' || $_GET[kd_aksi]=='RESET' ) { ?>
<!--<form name="frm_user" method="POST">-->
  <div class="panel panel-default" name="panelsatu">
    <div class="panel-heading">
      <h4 class="panel-title">
        
          <b>User Account</b>
        
      </h4>
    </div>
      <div class="panel-body">
			<?
				$s_username = "SELECT * FROM mst_login WHERE mlog_username = '$_GET[id_us]' ORDER BY mlog_username ";
				$q_user = mysql_query($s_username, $conn) or die("Error Query Username...");
				
				$ft_user = mysql_fetch_array($q_user);
			?>
			
			
			User ID.
			<div class="input-group input-group-sm">			
			<div class="input-group-addon"><span class="glyphicon glyphicon-user" style="color:#0000FF;"></span></div>
				<input id="btn01" name="txt_username" tabindex="1" type="text" class="form-control" placeholder="User id. ?"  value="<? if ($_GET[kd_aksi]=='EDIT' || $_GET[kd_aksi]=='RESET'){ echo $ft_user[mlog_username] ; } else { echo $_GET[id_us]; } ?>"data-toggle="tooltip" data-placement="bottom" title="User ID."  required autofocus <? if ($_GET[kd_aksi]<>'ADD' ) { echo "disabled" ;} ?>/>
				<span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span>
			</div>
			<br>
			
			<? if ($_GET[kd_aksi]=='ADD' || $_GET[kd_aksi]=='RESET' ) { ?>
			
			Password
			<div class="input-group input-group-sm" data-toggle="tooltip" data-placement="bottom" title="Password"><span class="input-group-addon"><span class="glyphicon glyphicon-lock" style="color:#0000FF;"></span></span>
				<input id="btn02" name="txt_password" tabindex="2" type="password" class="form-control" placeholder="Password ?" required value="<? if ($_GET[kd_aksi]=='EDIT' || $_GET[kd_aksi]=='RESET' || $_GET[kd_aksi]=='ADD'){ echo $_POST[txt_password] ; } ?>">
				<span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span>
			</div><br> <? } ?> 
			
			Fullname 
			<div class="input-group input-group-sm" data-toggle="tooltip" data-placement="bottom" title="Nama Lengkap">
				<span class="input-group-addon"><span class="glyphicon glyphicon-user" style="color:#0000FF;"></span></span>
				<input id="btn03" name="txt_fullname" tabindex="3" type="text" class="form-control" placeholder="Fullname ?" required autofocus <? if ($_GET[kd_aksi]=='RESET') { echo "disabled" ;}?> value="<? if ($_GET[kd_aksi]<>'ADD'){ echo $ft_user[mlog_name] ; } else { echo $_POST[txt_fullname] ; } ?>" >
				<span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span>			
			</div><br>
			
			Email Address			
			<div class="input-group input-group-sm" data-toggle="tooltip" data-placement="bottom" title="Alamat Email">
				<span class="input-group-addon"><span class="glyphicon glyphicon-globe" style="color:#0000FF;"></span></span>
				<input name="txt_email" tabindex="4" type="text" class="form-control" placeholder="Email ?" <? if ($_GET[kd_aksi]=='RESET') { echo "disabled" ;}?> value="<? if ($_GET[kd_aksi]<>'ADD'){ echo $ft_user[mlog_email] ; } else { echo $_POST[txt_email]; } ?>">			
			</div><br>
			
			Description
			<div class="input-group input-group-sm" data-toggle="tooltip" data-placement="bottom" title="Keterangan">
				<span class="input-group-addon"><span class="glyphicon glyphicon-user" style="color:#0000FF;"></span></span>
				<input name="txt_ket" tabindex="5" type="text" class="form-control" placeholder="Job and Description ?"  <? if ($_GET[kd_aksi]=='RESET' ) { echo "disabled" ;}?> value="<? if ($_GET[kd_aksi]<>'ADD'){ echo $ft_user[mlog_keterangan] ; } else { echo $_POST[txt_ket]; } ?>">		
			</div><br>
            
			
			<!--<button class="btn btn-primary btn-block" type='submit' name="btn_save_user"> SAVE </button>-->
			
		<!--end table-->
			
		</div>
	</div>
<!--</form>	-->
<?php
}
		
if($_GET[kd_aksi]=='COMPANY')
	{
?>
  <div class="panel panel-default" name="paneldua">
    <div class="panel-heading">
      <h4 class="panel-title">
        
          <b>Company Granting</b>
        
      </h4>
    </div>
      <div class="panel-body">
		<!--<form name="frm_granting_company" method="POST">-->
		<div class='table'>
<?
//	Granting Tabel Perusahaan

	$sel_pt = "SELECT * FROM mst_company WHERE mcom_status='1' ORDER BY mcom_id ASC";
	$que_pt = mysql_query($sel_pt, $conn) or die("Error Query Select Company");
	
	echo "
	<table width='450px' bgcolor='#FFFFFF' align='center' class='table table-border table-condensed'>
	<tr>
		<th width='2%'>#</th>
		<th width='75%' class='field_head'>Company Name</th>
		<th align=center width='5%' class='field_head'>Access</th>
		<th align=center width='5%' class='field_head'>Default</th>
		<th align=center width='13%' class='field_head'>Active Date</th>
	</tr>";


	$no=0;
	WHILE($fet_sel_pt = mysql_fetch_array($que_pt)){
		$no ++;
		/*echo "<script> alert('no post SAVE comp!!');</script>";*/
		
		echo"<tr class='field_data'>
					<td>$no</td>
					<td>$fet_sel_pt[1]</td>";
					
					$sel_grant_pt = "SELECT * FROM mst_granting_company WHERE mgc_username='$_GET[id_us]' AND mcom_id='$fet_sel_pt[0]' ORDER BY mcom_id ASC";
					$que_grant_pt = mysql_query($sel_grant_pt, $conn) or die("Error Query Select User Access Company");
					$row_ada1 = mysql_num_rows($que_grant_pt);
					$row_ada2 = mysql_fetch_array($que_grant_pt);
					
					if ($row_ada1){
						echo "<td align=left><input type='checkbox' id='ckb_pil_pt_$fet_sel_pt[0]' name='ckb_pil_pt_$fet_sel_pt[0]' checked ></td>";
						}
					else {
						echo "<td align=left><input type='checkbox' id='ckb_pil_pt_$fet_sel_pt[0]' name='ckb_pil_pt_$fet_sel_pt[0]' ></td>";}	
					
					$sel_defa_pt = "SELECT mcom_id FROM mst_login WHERE mlog_username='$_GET[id_us]' AND mcom_id='$fet_sel_pt[0]' ";
					$que_defa_pt = mysql_query($sel_defa_pt, $conn) or die("Error Query Select User Access Default Company");
					$row_ada3 = mysql_num_rows($que_defa_pt);
					
					if ($row_ada3){
						echo "<td align=left><input type='checkbox' id='ckb_def_pt_$fet_sel_pt[0]' name='ckb_def_pt_$fet_sel_pt[0]' checked  ></td>";
						}
					else {
						echo "<td align=left><input type='checkbox' id='ckb_def_pt_$fet_sel_pt[0]' name='ckb_def_pt_$fet_sel_pt[0]' ></td>";
						}	
						
					if ($row_ada2[2]=='Y'){						
						echo "<td align=left><input type='checkbox' id='ckb_date_pt_$fet_sel_pt[0]' name='ckb_date_pt_$fet_sel_pt[0]' checked  ></td>";						
						}
					else {
						echo "<td align=left><input type='checkbox' id='ckb_date_pt_$fet_sel_pt[0]' name='ckb_date_pt_$fet_sel_pt[0]' ></td>";											
					}
					
		}
	echo "</table>";


?>  
		</div>
        <!--<input type='hidden' name='txt_test' id="btn_test" value="" required />-->
        <input type='hidden' name='txt_test' id="btn_test" value="<? echo $no; ?>" />  
              
		<!--<button class="btn btn-primary btn-block" type='submit' name='btn_save_company'> SAVE </button>-->        
        
		<!--</form>-->
    </div>
  </div>
  
<?
}

if($_GET[kd_aksi]=='MENU')
	{
?>
  <div class="panel panel-default" name="paneltiga">
    <div class="panel-heading">
      <h4 class="panel-title">

          <b>Menu Granting</b>
        
      </h4>
    </div>
      <div class="panel-body">
	  <!--<form name="frm_granting_menu" method="POST">	-->

<?
/*	if($_POST[btn_save_menu]){
		$sel_pt = "DELETE FROM mst_granting_button WHERE mgbut_username='$_GET[id_us]'";
		$que_pt = mysql_query($sel_pt, $conn) or die("Error Query Delete user Company");						
	}
*/

//--Showing Parent Menu 
	$sh_parent = "";
	$sh_parent = $sh_parent."SELECT mst_menu_parent.mmpar_parent_menu, mst_menu_parent.mmpar_parent_id FROM mst_menu_parent ";
	$sh_parent = $sh_parent."WHERE mst_menu_parent.mmpar_status =  '1' ORDER BY mst_menu_parent.mmpar_parent_id ASC ";
	$que_parent = mysql_query($sh_parent, $conn) or die("Error Query Show Parent");

	echo "<table width='100%' bgcolor='#FFFFFF'>
	<tr>
		<th align=center colspan=2 width='60%' class='field_head'>Menu Name</th>
		<th align=center width='5%' class='field_head'>Add</th>
		<th align=center width='5%' class='field_head'>Edit</th>
		<th align=center width='5%' class='field_head'>Delete</th>
		<th align=center width='5%' class='field_head'>Form</th>
	</tr>";

	$no =0;
	
	WHILE($fet_sh_parent = mysql_fetch_array($que_parent)){
		echo"
		<tr bgcolor=#5BA0D6>
			<td colspan='6' class='field_data'><span class='glyphicon glyphicon-collapse-down'></span>&nbsp;&nbsp;$fet_sh_parent[0]</td>
		</tr>";
		
		//--Showing Submenu Level
		$sh_sub = "";
		$sh_sub = $sh_sub."SELECT mst_menu_sub.mmdet_sort_list, mst_menu_sub.mmdet_menu_name, mst_menu_sub.mmdet_menu_id ";
		$sh_sub = $sh_sub."FROM mst_menu_sub WHERE mst_menu_sub.mmdet_status = '1' AND mst_menu_sub.mmdet_parent_id = '$fet_sh_parent[1]' ";
		$sh_sub = $sh_sub."ORDER BY mst_menu_sub.mmdet_menu_id, mst_menu_sub.mmdet_sort_list ";
		$que_sub = mysql_query($sh_sub, $conn) or die("Error Query sh_sub");

		$ins_stat = 0;
		WHILE($ft_shsub = mysql_fetch_array($que_sub)){
			
			$no++;
			$ckb_submenu = "ckb_submenu_".$no;
			$ckb_add = "ckb_add_".$no;
			$ckb_edit = "ckb_edit_".$no;
			$ckb_del = "ckb_del_".$no;
			
			if ($i==0){			// color table per baris
				echo "<tr bgcolor=#5BA0D6 class='field_data'>";
				$i++;
			}
			else{
				echo "<tr bgcolor=#ABE4FE class='field_data'>";
				$i--;
			}

			echo"
				<td align=center width='6%'><span class='glyphicon glyphicon-collapse-up'></span></td>
				<td>$ft_shsub[1]</td>";
				
				//--Check Button Access --ADD
				$ck_available_add = "";
				$ck_available_add = $ck_available_add."SELECT * FROM mst_granting_button ";
				$ck_available_add = $ck_available_add."WHERE mst_granting_button.mgbut_username = '$_GET[id_us]' AND mst_granting_button.mgbut_menu_id = '$ft_shsub[2]' ";
				$ck_available_add = $ck_available_add."AND mst_granting_button.mgbut_button_id = '1' ";
				$qu_available_add = mysql_query($ck_available_add, $conn) or die("Error Query Check Available Access ADD Form");
				$row_available_add = mysql_num_rows($qu_available_add);
					
				if($row_available_add)
					echo "<td width=40 align=center><input type='checkbox' name='ckb_add_$no' value='1' checked></td> ";
				else
					echo "<td width=40 align=center><input type='checkbox' name='ckb_add_$no' value='1'></td> ";
				
				//--Check Button Access --EDIT
				$ck_available_edit = "";
				$ck_available_edit = $ck_available_edit."SELECT * FROM mst_granting_button ";
				$ck_available_edit = $ck_available_edit."WHERE mst_granting_button.mgbut_username = '$_GET[id_us]' AND mst_granting_button.mgbut_menu_id = '$ft_shsub[2]' ";
				$ck_available_edit = $ck_available_edit."AND mst_granting_button.mgbut_button_id = '2' ";
				$qu_available_edit = mysql_query($ck_available_edit, $conn) or die("Error Query Check Available Access EDIT Form");
				$row_available_edit = mysql_num_rows($qu_available_edit);

				if($row_available_edit)
					echo "<td width=40 align=center><input type='checkbox' name='ckb_edit_$no' value='2' checked></td>";
				else
					echo "<td width=40 align=center><input type='checkbox' name='ckb_edit_$no' value='2'></td>";

				
				//--Check Button Access --DELETE
				$ck_available_del = "";
				$ck_available_del = $ck_available_del."SELECT * FROM mst_granting_button ";
				$ck_available_del = $ck_available_del."WHERE mst_granting_button.mgbut_username = '$_GET[id_us]' AND mst_granting_button.mgbut_menu_id = '$ft_shsub[2]' ";
				$ck_available_del = $ck_available_del."AND mst_granting_button.mgbut_button_id = '3' ";
				$qu_available_edit = mysql_query($ck_available_del, $conn) or die("Error Query Check Available Access DELETE Form");
				$row_available_edit = mysql_num_rows($qu_available_edit);

				if($row_available_edit)
					echo "<td width=40 align=center><input type='checkbox' name='ckb_del_$no' value='3' checked></td>";
				else
					echo "<td width=40 align=center><input type='checkbox' name='ckb_del_$no' value='3'></td>";


				//--Check User Menu Access --Form
				$ch_available = "";
				$ch_available = $ch_available."SELECT mst_granting_menu.muacces_username, mst_granting_menu.muacces_menu_id FROM mst_granting_menu ";
				$ch_available = $ch_available."WHERE mst_granting_menu.muacces_username = '$_GET[id_us]' AND mst_granting_menu.muacces_menu_id = '$ft_shsub[2]' ";
				$que_available = mysql_query($ch_available, $conn) or die("Error Query Check Available");
				$row_available = mysql_num_rows($que_available);
			
				if($row_available){
					echo "<td align=center width=40><input type='checkbox' name='ckb_submenu_$no' value='$ft_shsub[2]' checked></td>";
					$ins_stat = 1; 
				}
				else{
					echo "<td align=center width=40><input type='checkbox' name='ckb_submenu_$no' value='$ft_shsub[2]'></td>";
					$ins_stat = 0; 
				}

			echo "</tr>";

			
			
		}
		//--end while submenu
	}
	//--end while parent menu
	

echo " </table><br>";
?>
		<!--<button class="btn btn-primary btn-block" type='submit' name='btn_save_menu'> SAVE </button>-->
		<!--</form>-->

        <input type='hidden' name='txt_test' id="btn_test" value="<? echo $no; ?>" />

      </div>
    </div>
	
<?
}
?>

<div id="tempat-pesan">
</div>
<?
	if ($que_saved==1){
		echo "<div class='button btn-sm'><div class='alert alert-success alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><b>$_GET[id_us]</b>, $textout </div></div>";
		$que_saved=0;					
	}

?>

    <script src="../../js/jquery-1.11.0.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js"></script>  
	
</body>
</html>

<?

} else {
	include "f_noakses.php";
}

?>