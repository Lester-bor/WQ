<?php 	
	include 'configure.php';
	global 	$Lesterbor_Sc_Servername;
	global 	$Lesterbor_Sc_Username;
	global 	$Lesterbor_Sc_Password;
	global	$Lesterbor_Sc_Dbname;
	// 创建连接
	$link = new mysqli($Lesterbor_Sc_Servername, $Lesterbor_Sc_Username, $Lesterbor_Sc_Password,$Lesterbor_Sc_Dbname);
	if ($link->connect_error)
	{
		die("连接失败: " . $link->connect_error);
	} 
?>
	<html>
	<head>
	<meta charset="utf-8">
		<style type="text/css">	
			.neikuang{
				margin-top: 5%;
			}
			.footer{
			  height: 40px;
			  width: 100%;
			  background:#111;
			  color:white;
			  text-align:center;
			  
			}
		</style>
	</head>
	<body bgcolor="aliceblue">
	<div class = "neikuang">
	<?php 
		if(isset($_POST['user_chauxun_number']))
		{
			$user_chauxun_number	= $_POST['user_chauxun_number'];
			$user_chauxun_name 		= $_POST['user_chauxun_name'];
			$user_chauxun_class 	= $_POST['user_chauxun_class'];
			$user_chauxun_knumber 	= $_POST['user_chauxun_knumber'];
			$chaxun_db 				= $_POST['chaxun_db'];
			$chaxun_chi 			= $_POST['chaxun_chi'];
			
			if($user_chauxun_number)
			{
				$result = mysqli_query($GLOBALS['link'],"select * from `$chaxun_db` where  number = '$user_chauxun_number' ");
				$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			}
			else if($user_chauxun_knumber)
			{
				$result = mysqli_query($GLOBALS['link'],"select * from `$chaxun_db` where knumber = '$user_chauxun_knumber' ");
				$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			}
			else echo "<script> alert('信息不匹配');</script>";
			
			if($row["name"]==$user_chauxun_name)
			{
				if($user_chauxun_class)
				{
					if($row["class"]!=$user_chauxun_class)
					{
						$_error = "1";
					}
				}
				if(!$_error)
				{
					echo "<script> alert('正确输出');</script>";
					$t_result = mysqli_query($GLOBALS['link'],"select * from `$chaxun_db` where Score_id = '1' ");
					$t_row=mysqli_fetch_array($t_result,MYSQLI_ASSOC);
					?>
					<!DOCTYPE html>
					<html lang="en">
					<head>
						<meta charset="UTF-8">
						<style>
							h3 {
								text-align: center;
							}
					 
							table, tr, td, th {
								text-align: center;
								border: 1px solid gray;
								border-collapse: collapse;
							}
					 
							table {
								margin: auto;
								width: 50%;
							}
					 
							tr:nth-child(2n) {
								background-color: rgba(88, 73, 65, 0.18);
							}
					 
							tr:hover {
								background-color: rgb(255, 235, 149);
							}
					 
							#tt {
								text-align: right;
								padding-right: 20px;
							}
						</style>
					</head>
					<body>
					<div>
						<table>
							<h3 ><?php echo "$chaxun_chi"; ?></h3>
							<table>
							<?php 
							$i=0;
							foreach ($t_row as $t_value)
							{
								if($i>=1)			
								{
									for($j=0;$j<$i+1;)
									{
										foreach ($row as $value)
										{
											if($j==$i)
											{
												echo "<tr>";
													echo "<th>$t_value</th>";
													echo "<th>$value</th>";
												echo "</tr>	";
												break;
											}
											$j++;
										}
										if($j==$i)
										{
											break;
										}
									}
								}
								$i++;
							}
							?>
								
					
							</table>
						</table>
					</div>
					</body>
	<?php
				}
				else echo "<script> alert('信息不匹配');</script>";
			}
			else echo "<script> alert('信息不匹配');</script>";	
		}
	?>
	</div>
	<div class='footer'><span style="line-height: 50px;">版权所有Copy2021 武山校园平台 ||Lesterbor ||感谢云落</span></div>
</body>
</html>