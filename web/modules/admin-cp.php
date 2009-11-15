<?
$db->connect();

if (empty($_SESSION['session_login'])) 
{
	$page->message($l_message['not_authorized']);
	$page->redirect("index.php?module=Login",$config['redirection_time']);
}
else if ($sec->is_it_admin($_SESSION['session_login']) == false) 
	$page->message($l_message['no_access']);
else
{
	
	if (empty($_GET['action']) OR $_GET['action'] == 'admins')
	{
		if ( empty($_GET['subaction']) ) 
		{
       		$result = $db->query("SELECT * FROM radcheck WHERE `admin` = '1'");
      		$num_results = $db->Num_rows($result);
       		include ('templates/' . $config['template'] . '/admins_table_header.html');

	   		$admin_number = 0;
			
       		for ($i = 0; $i < $num_results; $i++) 
       		{
       			$admin_number++;
       			$row = $db->Fetch_array($result);
       			include ('templates/' . $config['template'] . '/admins_table_body.html');
       		}
			
       		include ('templates/' . $config['template'] . '/admins_table_footer.html');
       		
       		include ('templates/' . $config['template'] . '/new_admin_table.html');
       		include ('templates/' . $config['template'] . '/admin-cp_menu.html');
    	}
    	else
    	{
    		if ($_GET['subaction'] == "adminadd")
    		{
    			//Проверяем существует ли такой пользователь
         		$result = $db->query("SELECT * FROM radcheck WHERE `username` = '".$_POST['adminadd_login']."';");
          		if ($db->Num_rows($result) < 1) 
          		{ 
          			$page->message($l_message['admin_nf']); 
          			$page->redirect("index.php?module=Admin-cp",$config['redirection_time']); 
          		}
          		else 
          		{
          			//если существует, делаем его админом
          			$db->query("UPDATE `radcheck` SET `admin` = '1' WHERE `username` = '".$_POST['adminadd_login']."' LIMIT 1 ;");
          			$page->message($l_message['admin_added']);
          			$page->redirect("index.php?module=Admin-cp",$config['redirection_time']);
          		}
    		}
    		else if ($_GET['subaction'] == "delete") 
    		{
         		$result = $db->query("SELECT * FROM radcheck WHERE `admin` = '1';");
       		  	if ($db->Num_rows($result) == 1) 
       		  	{
			  		$page->message($l_message['last_admin']);
			  		$page->redirect("index.php?module=Admin-cp",$config['redirection_time']);
       		  	}
       		  	
       		  	if ($db->Num_rows($result) > 1) 
       		  	{
       		  		$db->query("UPDATE `radcheck` SET `admin` = '0' WHERE `username` = '".$_GET['AdminName']."' LIMIT 1 ;");
       		  		$page->message($l_message['admin_deleted']);
       		  		$page->redirect("index.php?module=Admin-cp",$config['redirection_time']);
       		  	}
    		}
    	}
	}
	else if ($_GET['action'] == 'subnets')
	{
		if ( empty($_GET['subaction']) ) 
		{
			$result = $db->query("SELECT * FROM subnets;");
       		$num_results = $db->Num_rows($result);
       		include ('templates/' . $config['template'] . '/subnets_table_header.html');

	   		$subnet_number = 0;
			
       		for ($i = 0; $i < $num_results; $i++) {
       		$subnet_number++;
       		$row = $db->Fetch_array($result);
       		include ('templates/' . $config['template'] . '/subnets_table_body.html');
       		}

	   		include ('templates/' . $config['template'] . '/subnets_table_footer.html');
	   		
       		include ('templates/' . $config['template'] . '/new_subnet_table.html');
       		include ('templates/' . $config['template'] . '/admin-cp_menu.html');
	 	}
		else if ( $_GET['subaction'] == 'add_subnet' ) 
		{
			$result = $db->query("SELECT * FROM subnets WHERE `Subnet_Address` = '".$_POST['new_subnet_subnet']."';");
       		$num_results = $db->Num_rows($result);
         	
       		if ($num_results > 0) 
         	{
         		$page->message($l_message['subnet_found']);
         		$page->redirect("index.php?module=Admin-cp&action=subnets",$config['redirection_time']);
         	} 
         	else 
         	{
         		$db->query("INSERT INTO `subnets` (`id`, `Subnet_Address`, `NetMask`, `Masklength`) 
         		VALUES 
         		('','".$_POST['new_subnet_subnet']."','".$_POST['new_subnet_mask']."',
         		'".$billing->mask_2_cidr($_POST['new_subnet_mask'])."') ;");
         		$page->message($l_message['subnet_added']);
         		$page->redirect("index.php?module=Admin-cp&action=subnets",$config['redirection_time']);
         	}
		}
		else if ( $_GET['subaction'] == 'del_subnet' ) 
		{
			$db->query("DELETE FROM `subnets` WHERE `Subnet_Address` = '".$_GET['SubNet']."';");
       		$page->message($l_message['subnet_del']);
       		$page->redirect("index.php?module=Admin-cp&action=subnets",$config['redirection_time']);
		}
	}
	else if ($_GET['action'] == 'bws')
	{
		if ( empty($_GET['subaction']) )
		{
			$result = $db->query("SELECT * FROM bandwidth;");
       		$num_results = $db->Num_rows($result);
       		include ('templates/' . $config['template'] . '/bw_table_header.html');

	   		$subnet_number = 0;
			
       		for ($i = 0; $i < $num_results; $i++) {
       		$subnet_number++;
       		$row = $db->Fetch_array($result);
       		include ('templates/' . $config['template'] . '/bw_table_body.html');
       		}

	   		include ('templates/' . $config['template'] . '/table_footer.html');
	   		
       		include ('templates/' . $config['template'] . '/new_bw_table.html');
       		include ('templates/' . $config['template'] . '/admin-cp_menu.html');
	
		}
		else if ( $_GET['subaction'] == 'add_bw' )
		{
			$result = $db->query("SELECT * FROM bandwidth WHERE `bandwidth_name` = '".$_POST['new_bw_name']."';");
       		$num_results = $db->Num_rows($result);
         	
       		if ($num_results > 0) 
         	{
         		$page->message($l_message['bw_found']);
         		$page->redirect("index.php?module=Admin-cp&action=bws",$config['redirection_time']);
         	} 
         	else 
         	{
         		
         		$db->query("INSERT INTO `bandwidth` (`bw_id`, `bandwidth_name`) 
         		VALUES ('','".$_POST['new_bw_name']."') ;");
         		
         		$page->message($l_message['bw_added']);
         		$page->redirect("index.php?module=Admin-cp&action=bws",$config['redirection_time']);
         	}
			
		}
		else if ( $_GET['subaction'] == 'del_bw' )
		{
			//перед удалением проверяем, не прописана ли эта скорость у какого-либо пользователя или группы.
			$res = $db->query("SELECT bw_id FROM `bandwidth` WHERE `bandwidth_name` = '".$_GET['bw']."';");
			$bw = $db->Fetch_array($res);
			
			$res = $db->query("SELECT username FROM `radcheck` WHERE `bandwidth` = '".$bw['bw_id']."';");
			$users = $db->Num_rows($res);
			
			$res = $db->query("SELECT groupname FROM `vpnmsgroupreply` WHERE `bandwidth` = '".$bw['bw_id']."';");
			$groups = $db->Num_rows($res);
			
			if ( ($users > 0) OR ($groups > 0) )
			{
         		$page->message($l_message['bw_used']);
         		$page->redirect("index.php?module=Admin-cp&action=bws",$config['redirection_time']*2);
			}
			else
			{
				$db->query("DELETE FROM `bandwidth` WHERE `bandwidth_name` = '".$_GET['bw']."';");
       			$page->message($l_message['bw_del']);
       			$page->redirect("index.php?module=Admin-cp&action=bws",$config['redirection_time']);
			}
			
		}
	}
}
?>