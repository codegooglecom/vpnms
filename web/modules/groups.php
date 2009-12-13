<?
if (!defined('IN_VPNMS')) exit;

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
	if ( empty($_GET['action']) )
	{
		if (!$_GET['orderby']) 
			$_GET['orderby']='id';
		
		$billing->ShowGroups($_GET['orderby'], $_GET['month']);
		
		//Составляем список скоростей
		$bandwidth_res  = $db->query("SELECT * FROM `bandwidth`");
		$bandwidth = $db->Fetch_array($bandwidth_res);	
		for ($i=0; $i < $db->Num_rows($bandwidth_res); $i++)
		{
  			$tpl_bandwidth .= "<OPTION VALUE='". $bandwidth['bw_id'] ."'>". $bandwidth['bandwidth_name'] ."\n";
  			$bandwidth = $db->Fetch_array($bandwidth_res);
		}	

		//Составляем массив логинов и апишников
		$groups_res  = $db->query("SELECT groupname FROM `vpnmsgroupreply`");
		$groups = $db->Fetch_array($groups_res);	
		for ($i=0; $i < $db->Num_rows($groups_res); $i++)
		{
  			$tpl_groups .= "groups[$i]='".$groups["groupname"]."';\n";
  			$groups = $db->Fetch_array($groups_res);
		}
		
		include ('templates/' . $config['template'] . '/new_group_table.html');	
	}
	else
	{
		if ( $_GET['action'] == 'groupadd' )
		{
			//проверяем данные
			$billing->CheckGroupData($_POST['groupadd_group']);

			//если акаунт безлимитный то обнуляем лимит
            if ($_POST['groupadd_limit_type'] == 'unlimited') 
            { 
            	$_POST['groupadd_limit'] = 0; 
            	$_POST['groupadd_out_limit'] = 0; 
            }
            
            $grlimit = $_POST['groupadd_limit']*$config['mb']*$config['mb'];
            
            $query1 = "INSERT INTO `radius`.`vpnmsgroupreply` (
						`id` ,
						`groupname` ,
						`allow_tcp_port` ,
						`allow_udp_port` ,
						`limit` ,
						`out_limit` ,
						`bandwidth` ,
						`limit_type` ,
						`status`
						)
					VALUES (
						NULL , '". $_POST['groupadd_group'] ."', '". $_POST['groupadd_tcp'] ."', 
						'". $_POST['groupadd_udp'] ."', '". $grlimit ."', 
						'". $_POST['groupadd_out_limit'] ."', '". $_POST['groupadd_bw'] ."',
						 '". $_POST['groupadd_limit_type'] ."', 'working'
					)";
            
            $query2 = "INSERT INTO `radgroupreply` (
            			`id`, 
            			`groupname`, 
            			`attribute`, 
            			`op`, 
            			`value`
            			) 
            		VALUES
						(NULL, '". $_POST['groupadd_group'] ."', 'Framed-Protocol', ':=', 'PPP'),
						(NULL, '". $_POST['groupadd_group'] ."', 'Framed-IP-Netmask', ':=', '255.255.255.255')";
            
            $db->query($query1);
            $db->query($query2);
            
            $page->message($l_message['group_added']);
            $page->redirect("index.php?module=Groups",$config['redirection_time']);
		}
		else if ( $_GET['action'] == 'edit' )
		{
			if (!$_GET['orderby']) 
				$_GET['orderby']='id';
		
			$billing->ShowGroups($_GET['orderby'], $_GET['month']);
			
			$res = $db->query("SELECT * FROM `vpnmsgroupreply` WHERE `groupname` = '". $_GET['GroupName'] ."'");
			$info = $db->Fetch_array($res);
			$limit = number_format($info['limit']/($config['mb']*$config['mb']), 0, '.', ' '); 

			//Составляем список скоростей
			$bandwidth_res  = $db->query("SELECT * FROM `bandwidth`");
			$bandwidth = $db->Fetch_array($bandwidth_res);	
			for ($i=0; $i < $db->Num_rows($bandwidth_res); $i++)
			{
  				$selected = "";
  				if ($info['bandwidth'] == $bandwidth['bw_id'])
  					$selected = "selected";
  				
				$tpl_bandwidth .= "<OPTION VALUE='". $bandwidth['bw_id'] ."' ". $selected .">". $bandwidth['bandwidth_name'] ."\n";
  				$bandwidth = $db->Fetch_array($bandwidth_res);
			}
			
			include ('templates/' . $config['template'] . '/edit_group_table.html');
			
		}
		/*
		 * Сохраняем настройки группы.
		 * Если не установлена галка перезаписать настройки, то настройки группы
		 * применяем ко всем пользователям, если нет, то только к тем, у кого не
		 * установлены персональные настройки.
		 */
		else if ($_GET['action'] == "save_edit")
		{
		    $user_limit = $_POST['groupedit_limit']*$config['mb']*$config['mb'];
            $user_out_limit = $_POST['groupedit_out_limit'];
            $user_limit_type = $_POST['groupedit_limit_type'];
            
		    //Если акаунт безлимитный, то обнуляем лимит и бонус
			if ($user_limit_type == 'unlimited') 
			{ 
				$user_limit = 0; 
				$user_out_limit = 0;
			}
					
			//Обновляем настройки всех пользователей группы и если надо, добавляем задание на отключение
			$query = "SELECT `username` FROM `radusergroup` WHERE `groupname` = '". $_POST['groupedit_group'] ."'";
			$res = $db->query($query);
			$num_rows = $db->Num_rows($res);
			
			for ($i = 0; $i < $num_rows; $i++)
			{
				$row = $db->Fetch_array($res);
				
				if (($billing->PersonalOpts($row['username']) == false) || ( $_POST['groupedit_rewrite'] == 'on' ))
				{
					$query = "UPDATE radcheck SET 
						`allow_tcp_port` = '". $_POST['groupedit_tcp'] ."',
						`allow_udp_port` = '". $_POST['groupedit_udp'] ."', 
						`out_limit` = '". $user_out_limit ."',
						`limit` = '". $user_limit ."',
						`bandwidth` = '". $_POST['groupedit_bw'] ."', 
						`limit_type` = '". $user_limit_type ."' 
						WHERE `username` = '". $row['username'] ."'";
					$db->query($query);
					
					if ($_POST['groupedit_disconnect'] == 'on')
						$billing->disconnect_user($row['username']);
				}
			}
			
			//Обновляем настройки группы
			$query = "UPDATE `vpnmsgroupreply` SET 
				`allow_tcp_port` = '". $_POST['groupedit_tcp'] ."',
				`allow_udp_port` = '". $_POST['groupedit_udp'] ."', 
				`out_limit` = '". $user_out_limit ."',
				`limit` = '". $user_limit ."',
				`bandwidth` = '". $_POST['groupedit_bw'] ."', 
				`limit_type` = '". $user_limit_type ."' 
				WHERE `groupname` = '". $_POST['groupedit_group'] ."'";
			$db->query($query);
			
			$page->message($l_message['group_opts_ch']);
            $page->redirect("index.php?module=Groups",$config['redirection_time']);
		}
		else if ( $_GET['action'] == 'edit_status' )
		{
			if (!$_GET['orderby']) 
				$_GET['orderby']='id';
		
			$billing->ShowGroups($_GET['orderby'], $_GET['month']);		
		
			$res = $db->query("SELECT status FROM `vpnmsgroupreply` WHERE `groupname` = '". $_GET['GroupName'] ."'");
			$info = $db->Fetch_array($res);
			
			include ('templates/' . $config['template'] . '/edit_group_status_table.html');
		}
		else if ($_GET['action'] == "save_status_edit")
		{
			//Обновляем статус группы
			$query = "UPDATE `vpnmsgroupreply` SET `status` = '". $_POST['groupedit_status'] ."' 
			WHERE `groupname` = '". $_POST['groupedit_group'] ."'";
			$db->query($query);
			
			//Обновляем статус всех пользователей группы и если надо, добавляем задание на отключение
			$query = "SELECT `username` FROM `radusergroup` WHERE `groupname` = '". $_POST['groupedit_group'] ."'";
			$res = $db->query($query);
			$num_rows = $db->Num_rows($res);
			
			for ($i = 0; $i < $num_rows; $i++)
			{
				$row = $db->Fetch_array($res);
				
				$query = "UPDATE `radcheck` SET `status` = '". $_POST['groupedit_status'] ."' 
				WHERE `username` = '". $row['username'] ."'";
				$db->query($query);
				
				if ($_POST['groupedit_disconnect'] == 'on')
					$billing->disconnect_user($row['username']);
			}
			
			$page->message($l_message['group_status_ch']);
            $page->redirect("index.php?module=Groups",$config['redirection_time']);
		}
		else if ($_GET['action'] == "delete")
		{
			//Проверяем, есть ли пользователи в этой группе.
			$query = "SELECT username FROM `radusergroup` WHERE `groupname` = '". $_GET['GroupName'] ."'";
			$res = $db->query($query);
			if ($db->num_rows($res) > 0)
			{
				$page->message($l_message['group_del_err']);
				$page->redirect("index.php?module=Groups",$config['redirection_time']*2);
			}
			else
			{
				$query1 = "DELETE FROM `radgroupreply` WHERE `groupname` = '". $_GET['GroupName'] ."'";
				$query2 = "DELETE FROM `vpnmsgroupreply` WHERE `groupname` = '". $_GET['GroupName'] ."'";
				
				$db->query($query1);
				$db->query($query2);
				
				$page->message($l_message['group_del']);
				$page->redirect("index.php?module=Groups",$config['redirection_time']);
			}
		}
		else
			$page->message($l_errors['err_action']);
	}
}
?>
