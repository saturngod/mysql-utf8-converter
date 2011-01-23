<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MySQL Field Change To UTF-8</title>

<script>
function checker()
{

	if (document.frm.host.value=="")
	{
		alert("Need To Fill Database Address (host)");
		return false;
	}
	if (document.frm.dbname.value=="")
	{
		alert("Need To Fill Database Name");
		return false;
	}
	if (document.frm.usr.value=="")
	{
		alert("Need To Fill User Name");
		return false;
	}
	
return ture;
}
</script>
</head>
<body>

<?php
///Written by Saturngod
include('utf8myconv.php');
if ((strlen($_GET['dbname'])>0) && (strlen($_GET['host'])>0))
{

	$db_name=$_GET['dbname'];
	$host=$_GET['host'];
	$username=$_GET['usr'];
	$pwd=$_GET['pwd'];
	$link=mysql_connect($host, $username, $pwd);
	$result = mysql_list_tables($db_name);
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
			$message  = 'Invalid query: ' . mysql_error() . "<br>";
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
				 //echo '$row['.$k.']=>'.$v.'<br>';
		//}
				mysql_query('ALTER Table '.$tb_name.' CHANGE `'.$row['Field'].'` `'.$row['Field'].'` '.$row['Type'].' CHARACTER SET utf8 COLLATE utf8_unicode_ci');
		 
					}
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
//							  	echo "Convert<br/>";
								//Convert TO String
								echo 'Convert Line.... '.$curr_line;
								$field_name=mysql_field_name($result_query,$field_count);
							mysql_query("update ".mysql_tablename($result, $i) ." set `$field_name` = '". myan_conv($col_value)."' where `$field_name`='$col_value'");

								//exit();
							  }
								        
					      //  echo "($type)".myan_conv($col_value)." | ";
					        $field_count++;
					        
					    }
					    echo "<br>";

					}
					
			}						
			echo "Complete Table: <b>".mysql_tablename($result, $i)."</b><br>";
			
	}
	
	echo "<h3>Complete ALL</h3>Written by Saturngod";
	

//echo $query;

exit();
}
else
{
?>
<form action="<?= $_SERVER['PHP_SELF']?>" method="get" name="frm">
<h3>Change Database To UTF-8</h3>
<table border="0">
  <tr>
    <td>Database Host</td>
    <td><input name="host" type="text" value="localhost" /><font color="red">*</font></td>
  </tr>
  <tr>
    <td>Database Name</td>
    <td><input type="text" name="dbname" /><font color="red">*</font></td>
  </tr>
  <tr>
    <td>Username</td>
    <td><input type="text" name="usr" /><font color="red">*</font></td>
  </tr>
  <tr>
    <td>Password</td>
    <td><input type="text" name="pwd" /></td>
  </tr>
</table>
<input type="submit" value="Change Now" onClick="checker();" />
</form>
<font color="red">*</font> = Require
<h6>writteny by <a href="http://www.saturngod.net">Saturngod</a><br />
Feedback : saturngod@mysteryzillion.com</h6>

<?php
}
?>
</body>
</html>