<?php
///Writeen by Saturngod

date_default_timezone_set('Asia/Singapore') ;
include('utf8myconv.php');


	$db_name="newmz";
	$host="localhost";
	$username="root";
	$pwd="root";
	$curr_line=0;
	
	//change latin string &#4801; to unicode
	//default is false and not change
	$changestring=false;
	
	$link=mysql_connect($host, $username, $pwd);
	$result = mysql_query("SHOW TABLES FROM ".$db_name);
	mysql_query("SET NAMES 'utf8'");
	mysql_query('ALTER DATABASE `'.$db_name.'` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci');
	mysql_select_db($db_name, $link) or die('Could not select database.');
	$num_rows = mysql_num_rows($result);
	for ($i = 0; $i < $num_rows; $i++) 
	{
	 
		$tb_name=mysql_tablename($result, $i); 
		 
		$query="Alter Table `".$tb_name."` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		  $result_query=mysql_query($query);
		
		//Change Table
		if (!$result_query) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			//die($message);
		}
			
	//Change Field
	
	
		$result_field = mysql_query("SHOW COLUMNS FROM ".$tb_name);
		if (!$result_field) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		if (mysql_num_rows($result_field) > 0) {
			while ($row = mysql_fetch_assoc($result_field)) {
				  //  foreach ($row as $k => $v) {
		 /*   
				$row[Field]=>loc_uuid
					$row[Type]=>varchar(60)
					$row[Null]=>NO
					$row[Key]=>PRI
					$row[Default]=>
					$row[Extra]=>
		*/
				 //echo '$row['.$k.']=>'.$v.'\n';
		//}
				mysql_query('ALTER Table '.$tb_name.' CHANGE `'.$row['Field'].'` `'.$row['Field'].'` '.$row['Type'].' CHARACTER SET utf8 COLLATE utf8_unicode_ci');
		 
					}
					
					if(changestring==true)
					{
						$data_query="Select * from ".$tb_name;
						$result_query=mysql_query($data_query);
						
						// Printing results in HTML
						while ($line = mysql_fetch_array($result_query, MYSQL_ASSOC)) {
						$field_count=0;
						$curr_line++;
							foreach ($line as $col_value) {
								  $type= mysql_field_type($result_query,$field_count);
								  if (($type=="string") or ($type=="blob"))
								  {
	
									//Convert TO String
									echo 'Convert Line.... '.$curr_line."\n";
									$field_name=mysql_field_name($result_query,$field_count);
								mysql_query("update ".mysql_tablename($result, $i) ." set `$field_name` = '". myan_conv($col_value)."' where `$field_name`='$col_value'");
	
								  }
											
								$field_count++;
								
							}
							echo "\n";
	
						}
					}					
			}						
			echo "Complete Table:   ".mysql_tablename($result, $i)."\n";
			
	}
	
	echo "Complete ALL Written by Saturngod";
	
?>