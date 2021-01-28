<?php
/*
Plugin Name: 成绩查询
Description: 成绩查询系统
Author: Lesterbor
Version: 1.0
*/

?>

<?php
	//获取服务器配置文件
	include 'configure.php';
	global 	$Lesterbor_Sc_Servername;
	global 	$Lesterbor_Sc_Username;
	global 	$Lesterbor_Sc_Password;
	global	$Lesterbor_Sc_Dbname;
	// 创建连接
	$link = new mysqli($Lesterbor_Sc_Servername, $Lesterbor_Sc_Username, $Lesterbor_Sc_Password);
	if ($link->connect_error)
	{
		die("连接失败: " . $link->connect_error);
	} 
	//判断数据库是否存在
	$result = mysqli_query($GLOBALS['link'],"show databases;"); 
	While($row=mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		$dataarr[] = $row['Database'];
	}
	unset($result,$row); 
	if (in_array(strtolower($Lesterbor_Sc_Dbname),$dataarr))
	{
		;
	}
	else
	{
		$sql = "CREATE DATABASE $Lesterbor_Sc_Dbname";
		mysqli_query($GLOBALS['link'], $sql);
	}
	set_time_limit(0);//不限制程序执行时间
?>

<?php  
	//取CSV文件字符
	function _fgetcsv(& $handle, $length = null, $d = ',', $e = '"') {
		 $d = preg_quote($d);
		 $e = preg_quote($e);
		 $_line = "";
		 $eof=false;
		 while ($eof != true) {
			 $_line .= (empty ($length) ? fgets($handle) : fgets($handle, $length));
			 $itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);
			 if ($itemcnt % 2 == 0)
				 $eof = true;
		 }
		 $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
		 $_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';
		 preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
		 $_csv_data = $_csv_matches[1];
		 for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++) {
			 $_csv_data[$_csv_i] = preg_replace('/^' . $e . '(.*)' . $e . '$/s', '$1' , $_csv_data[$_csv_i]);
			 $_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);
		 }
		 return empty ($_line) ? false : $_csv_data;
	}
	
	//获取图像id
	function wpjam_get_attachment_id ($img_url) {
		$cache_key	= md5($img_url);
		$post_id	= wp_cache_get($cache_key, 'wpjam_attachment_id' );
		if($post_id == false){

			$attr		= wp_upload_dir();
			$base_url	= $attr['baseurl']."/";
			$path = str_replace($base_url, "", $img_url);
			if($path){
				global $wpdb;
				$post_id	= $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_value = '{$path}'");
				$post_id	= $post_id?$post_id:'';
			}else{
				$post_id	= '';
			}

			wp_cache_set( $cache_key, $post_id, 'wpjam_attachment_id', 86400);
		}
		return $post_id;
	}

	function Score_inquiry()
	{   
		add_menu_page( '成绩查询系统', '成绩查询', 'administrator', '成绩查询','Score_inquiry_menu_page_function','',100);   
	} 
	
	function Score_inquiry_menu_page_function()
	{ 
		echo "<br/>";
		echo "<br/>";
		echo "<font size='5' color='black' face='微软雅黑'>成绩查询系统</font>";
		echo "<br/>";
		echo "<br/>";
		echo "<font size='4' color='black' face='微软雅黑'>新建成绩查询</font>";
		echo "<br/>";
		echo "<br/>";
		//上传学生信息CSV文件
		echo "<form action='' method='POST' enctype='multipart/form-data'>";
			echo "<font size='3' color='black' face='宋体'>新建查询中文名称：</font>";
			echo "<input type='text' name='Score_inquiry_chi_tittle' placeholder='如 武山三中2019届高三理科第二次月考' style='width: 30%;height:30px;'/>";
			echo "<br/>";
			echo "<br/>";
			echo "<font size='3' color='black' face='宋体'>新建查询英文名称：</font>";
			echo "<input type='text' name='Score_inquiry_eng_tittle' placeholder='如 wssz_2019_g3_l_2' style='width: 30%;height:30px;'/>";
			echo "<br/>";
			echo "<br/>";
			echo "<font size='3' color='black' face='宋体'>输入特色图像URL：</font>";
			echo "<input type='text' name='Score_inquiry_image' placeholder='如 http://120.53.247.94/wp-content/uploads/2020/12/2020122916004077.png' style='width: 30%;height:30px;'/>";
			echo "<br/>";
			echo "<br/>";
			echo "<font size='3' color='black' face='宋体'>成绩文章分类目录：</font>";
			echo "<input type='text' name='Score_inquiry_ml' placeholder='如 8 ' style='width: 30%;height:30px;'/>";
			echo "<br/>";
			echo "<br/>";
			echo "<font size='3' color='black' face='宋体'>选择学生成绩CSV文件：</font>";
			echo "<input name='Score_CSV' type='file' accept='.csv' style='width: 20%;height:30px;' >";
			echo "<br/>";
			echo "<br/>";
			echo "<font size='3' color='black' face='宋体'>选择查询条件：</font>";
			echo "<input type='checkbox' name='Found_name' id='Found_name' value='1'>姓名&emsp;</input>";
			echo "<input type='checkbox' name='Found_number' id='Found_number' value='1'>学号&emsp;</input>";
			echo "<input type='checkbox' name='Found_class' id='Found_class' value='1'>班级&emsp;</input>";
			echo "<input type='checkbox' name='Found_knumber' id='Found_knumber' value='1'>考号&emsp;</input>";
			echo "<br/>";
			echo "<br/>";
			echo "<input type='submit' value='生成查询表单' style='width: 120px;height:30px;' />";
			echo "<br/>";
			echo "<br/>";
		echo "</form>";
		if(isset($_POST['Score_inquiry_eng_tittle']))
		{
			$Score_inquiry_eng_tittle = $_POST['Score_inquiry_eng_tittle'];		//获取建表的英文名称
			$Score_inquiry_chi_tittle = $_POST['Score_inquiry_chi_tittle'];		//获取建表的中文名称
			$Score_inquiry_ml         = $_POST['Score_inquiry_ml'];        		//获取新建成绩分类目录
			mysqli_select_db($GLOBALS['link'],$GLOBALS['Lesterbor_Sc_Dbname']);	//选择所要操作的数据库
			$result=mysqli_query($GLOBALS['link'],"
				CREATE TABLE `$Score_inquiry_eng_tittle`
				(
				`Score_id` INT UNSIGNED AUTO_INCREMENT,
					PRIMARY KEY ( `Score_id` )
				)ENGINE=InnoDB DEFAULT CHARSET=utf8;
			");										//新建数据表表头
			
			$fileInfo = $_FILES["Score_CSV"];		//获取上传文件的名称
			$fname = $fileInfo["name"];				//获取上传文件的名称
			$fPath = $fileInfo["tmp_name"];			//获取文件临时名称
			copy($fPath,$fname);
			setlocale(LC_ALL, 'zh_CN');
			$handle=@fopen("$fname","r");
			$data=_fgetcsv($handle,1000,",");
			for($i = 0;$data[$i];$i++)
			{
				$result=mysqli_query($GLOBALS['link'],"
					ALTER TABLE `$Score_inquiry_eng_tittle` ADD `$data[$i]` VARCHAR(50) DEFAULT NULL
				");									//更新数据表将所有列名称全部添加至数据库
				$STBT[$i] = $data[$i];
			}
			while($data=_fgetcsv($handle,1000,","))	//将所有CSV数据上传至数据库
			{
				$result=mysqli_query($GLOBALS['link'],"
					INSERT INTO `$Score_inquiry_eng_tittle`(`$STBT[0]`,`$STBT[1]`) VALUES ('$data[0]','$data[1]')
				");
				for($i = 2;$STBT[$i];$i++)
				{
					$result=mysqli_query($GLOBALS['link'],"
						UPDATE `$Score_inquiry_eng_tittle` SET `$STBT[$i]` = '$data[$i]' WHERE number = '$data[1]'");	
				}
			}
			fclose($handle);	
			mysqli_close($GLOBALS['link']);
			
			$Lesterbor_Score_infm = strval(plugins_url('Score_infm.php',__FILE__));		//获取成绩详情界面绝对路径
			$Lesterbor_Score_css  = strval(plugins_url('/css/Score_CSS.css',__FILE__));	//获取css样式路径
			
			$a = "<html>";
			$b = "	<head>
					<meta charset='utf-8'>
					<link rel='stylesheet' href=$Lesterbor_Score_css type='text/css'>
					</head>";
			$c = "<body>";
			$d = "<form action = $Lesterbor_Score_infm method='post'>";
			$e = "";
			$f = "<div class='head_PT'>";
			
			if(isset($_POST['Found_name']))
			{
				$g = "	<div class='form_PT'><input class='input_PT' placeholder='请输入姓名' id='user_chauxun_name' name='user_chauxun_name' required='required'>
						</div>";
			}
			else $g = '';
			
			if(isset($_POST['Found_number']))
			{
				$h = "	<div class='form_PT'><input class='input_PT' placeholder='请输入学号' id='user_chauxun_number' name='user_chauxun_number' required='required'>
						</div>";
			}
			else $h = '';
			
			if(isset($_POST['Found_knumber']))
			{
				$i = "	<div class='form_PT'><input class='input_PT' placeholder='请输入考号' id='user_chauxun_knumber' name='user_chauxun_knumber' required='required'>
						</div>";
			}
			else $i = '';
			
			if(isset($_POST['Found_class']))
			{
				$j = "	<div class='form_PT'>
							<input class='input_PT' placeholder='请输入班级' id='user_chauxun_class' name='user_chauxun_class' required='required'>
						</div>";
			}
			else $j = '';
			
			$k = "<div style='text-align:center;vertical-align:middle;'><input type=hidden name='chaxun_db' value= $Score_inquiry_eng_tittle ></div>";
			$l = "<div style='text-align:center;vertical-align:middle;'><input type=hidden name='chaxun_chi' value= $Score_inquiry_chi_tittle ></div>";
			$m = "<div class='butt_PT'><button type='submit' style='width:100%;background: #666666;color: #FFFFFF;height: 60px; border-radius: 15px;margin-top:20px;'>查询</button></div>";
			
			$n = "</div>";
			$o = "</form>";
			
			$contet = "$a$b$c$d$e$f$g$h$i$j$k$l$m$n$o";
			$my_post = array(
				'post_title' => $Score_inquiry_chi_tittle,
				'post_content' => $contet,
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
				'post_category' => array($Score_inquiry_ml,39)
			);
			$post_idd = wp_insert_post( $my_post );//如果插入成功则返回post_id
			if(isset($_POST['Score_inquiry_image']))
			{
				$Score_inquiry_image = $_POST['Score_inquiry_image'];
				$Scire_image_id = wpjam_get_attachment_id ($Score_inquiry_image);
				//设置成绩查询页面的特色图像
				global $wpdb;	
				$table = "$wpdb->postmeta";  
				$data_array = array(   
				'post_id' => $post_idd ,  
				'meta_key' => "_thumbnail_id",
				'meta_value' => $Scire_image_id ,  
				);  
				$wpdb->insert($table,$data_array); 
			}
			echo "<script> alert('数据提交成功');</script>";	
		}	
	}
	add_action('admin_menu', 'Score_inquiry');   
?>