<?
$db->connect();

if (empty($_SESSION['session_login'])) 
{
	$page->message($l_message['not_authorized']);
	$page->redirect("index.php?module=Login",$config['redirection_time']);
}
else if ($sec->is_it_admin($_SESSION['session_login']) == false) 
{
	$page->message($l_message['no_access']);
}
else
{
	if ( empty($_GET['action']) ) 
	{
		if (!$_GET['orderby']) 
			$_GET['orderby']='id';
		
		$billing->ShowUsers($_GET['orderby'], $_GET['month']);
		
		//Составляем массив логинов и апишников
		$logins_res  = $db->query("SELECT radcheck.UserName FROM `radcheck`");
		$logins = $db->Fetch_array($logins_res);	
		for ($i=0; $i < $db->Num_rows($logins_res); $i++)
		{
  			$tpl_logins .= "logins[$i]='".$logins["UserName"]."';\n";
  			$tpl_ips .= "ips[$i]='".$billing->get_ip_by_name($logins["UserName"])."';\n";
  			$logins = $db->Fetch_array($logins_res);
		}

		//Составляем список групп
		$groups_res  = $db->query("SELECT groupname FROM `vpnmsgroupreply`");
		$groups = $db->Fetch_array($groups_res);	
		for ($i=0; $i < $db->Num_rows($groups_res); $i++)
		{
  			$tpl_groups .= "<OPTION VALUE='". $groups['groupname'] ."'>". $groups['groupname'] ."\n";
  			$groups = $db->Fetch_array($groups_res);
		}	
		
		//Составляем список скоростей
		$bandwidth_res  = $db->query("SELECT * FROM `bandwidth`");
		$bandwidth = $db->Fetch_array($bandwidth_res);	
		for ($i=0; $i < $db->Num_rows($bandwidth_res); $i++)
		{
  			$tpl_bandwidth .= "<OPTION VALUE='". $bandwidth['bw_id'] ."'>". $bandwidth['bandwidth_name'] ."\n";
  			$bandwidth = $db->Fetch_array($bandwidth_res);
		}	
		
		include ('templates/' . $config['template'] . '/new_user_table.html');
		
	}
	else
	{
		if ( $_GET['action'] == 'accountadd' )
		{
			//Проверяем входящие данные
            $billing->CheckData($_POST['accountadd_login'],$_POST['accountadd_tcp'],$_POST['accountadd_udp'],$_POST['accountadd_ipaddr'],$_POST['accountadd_limit_type'],$_POST['accountadd_limit']);

            //если акаунт безлимитный то в базу юзеров пишем ноль
            if ($_POST['accountadd_limit_type'] == 'unlimited') 
            { 
            	$_POST['accountadd_limit'] = 0; 
            	$_POST['accountadd_out_limit'] = 0; 
            }

            $allowed_tcp_ports = $_POST['accountadd_tcp'];
            $allowed_udp_ports = $_POST['accountadd_udp']; 
            $user_limit = $_POST['accountadd_limit']*$config['mb']*$config['mb'];
            $user_out_limit = $_POST['accountadd_out_limit'];
            $user_limit_type = $_POST['accountadd_limit_type'];
            $user_bw = $_POST['accountadd_bw'];
            
            //Если включена опция "Опции группы", то выбираем опции нужной группы
            if ($_POST['accountadd_gr_opts'] == 'on')
            {
            	$query = "SELECT * FROM `vpnmsgroupreply` WHERE `groupname` = '". $_POST['accountadd_group'] ."'";
            	$res = $db->query($query);
            	$group_opts = $db->fetch_array($res);
            	
            	$allowed_tcp_ports = $group_opts['allow_tcp_port'];
            	$allowed_udp_ports = $group_opts['allow_udp_port']; 
            	$user_limit = $group_opts['limit'];
            	$user_out_limit = $group_opts['out_limit'];
            	$user_limit_type = $group_opts['limit_type'];
            	$user_bw = $group_opts['bandwidth'];
            }

            $query1 = "INSERT INTO `radcheck` ( `id` , `username` , `attribute` , `op`, `value`, `name`, `lastname`, 
            `allow_tcp_port`, `allow_udp_port`, `limit`, `out_limit`, `bandwidth`, `limit_type`, `status`, `bonus`, `admin` ) 
            VALUES 
            ('', '".$_POST['accountadd_login']."', 'Cleartext-Password', ':=', '".$_POST['accountadd_passwd']."', 
            '".$_POST['accountadd_name']."', '".$_POST['accountadd_lastname']."', '".$allowed_tcp_ports."', 
            '".$allowed_udp_ports."', '".$user_limit."', '".$user_out_limit."', '". $user_bw ."' ,
            '".$user_limit_type."', 'working', '0','0')";
            
            $query2 = "INSERT INTO `radusergroup` ( `username` , `groupname`, `priority` ) 
            VALUES 
            ('".$_POST['accountadd_login']."', '". $_POST['accountadd_group'] ."', '1')";
            	
            $query3 = "INSERT INTO `radreply` ( `id` , `username` , `attribute` , `op`, `value`) 
            VALUES 
            ('', '".$_POST['accountadd_login']."', 'Framed-IP-Address', ':=',
             '".$_POST['accountadd_ipaddr']."')";
            	
            $db->query($query1);
            $db->query($query2);
            $db->query($query3);
            
            $page->message($l_message['acnt_added']);
            $page->redirect("index.php?module=Users",$config['redirection_time']);
		}
		else if ($_GET['action'] == "edit")
		{
			if (!$_GET['orderby']) 
			$_GET['orderby']='id';
		
			$billing->ShowUsers($_GET['orderby'], $_GET['month']);
			
			$personal_opts = $billing->PersonalOpts($_GET['UserName']);
			
			$res  = $db->query("SELECT * FROM radcheck WHERE `username` ='".$_GET['UserName']."'");
            $info = $db->fetch_array($res);
			
            $ipaddress = $billing->get_ip_by_name($_GET['UserName']);
            $balance = $billing->balance($_GET['UserName']);
            
            //Составляем список групп
			$groups_res  = $db->query("SELECT groupname FROM `vpnmsgroupreply`");
			$groups = $db->Fetch_array($groups_res);	
			for ($i=0; $i < $db->Num_rows($groups_res); $i++)
			{
  				$selected = "";
  				if ($balance['groupname'] == $groups['groupname'])
  					$selected = "selected";
				
				$tpl_groups .= "<OPTION VALUE='". $groups['groupname'] ."' ". $selected .">". $groups['groupname'] ."\n";
  				$groups = $db->Fetch_array($groups_res);
			}

			//Составляем список скоростей
			$bandwidth_res  = $db->query("SELECT * FROM `bandwidth`");
			$bandwidth = $db->Fetch_array($bandwidth_res);	
			for ($i=0; $i < $db->Num_rows($bandwidth_res); $i++)
			{
  				$selected = "";
  				if ($balance['bw_id'] == $bandwidth['bw_id'])
  					$selected = "selected";
  				
				$tpl_bandwidth .= "<OPTION VALUE='". $bandwidth['bw_id'] ."' ". $selected .">". $bandwidth['bandwidth_name'] ."\n";
  				$bandwidth = $db->Fetch_array($bandwidth_res);
			}	
			
			$user_limit = "";
			$user_out_limit = "";
			$user_tcp = "";
			$user_udp = "";
			
			if ($personal_opts)
			{
				$user_limit = $info['limit']/($config['mb']*$config['mb']);
				$user_out_limit = $info['out_limit'];
				$user_tcp = $info['allow_tcp_port'];
				$user_udp = $info['allow_udp_port'];
			}
			
			$bonus_mb = $info['bonus']/($config['mb']*$config['mb']);
                        
			include ('templates/' . $config['template'] . '/edit_user_table.html');
		}
		else if ($_GET['action'] == "save_edit")
		{
			/*	
			 * 	Подготавливаем данные о пользователе в зависимости от того, 
			 *	персональные у него настройки или настройки группы
			 */
			$allowed_tcp_ports = $_POST['accountedit_tcp'];
            $allowed_udp_ports = $_POST['accountedit_udp']; 
            $user_limit = $_POST['accountedit_limit']*$config['mb']*$config['mb'];
            $user_out_limit = $_POST['accountedit_out_limit'];
            $user_limit_type = $_POST['accountedit_limit_type'];
            $user_bw = $_POST['accountedit_bw'];
            $user_bonus = $_POST['accountedit_bonus']*$config['mb']*$config['mb'];
            
		    //Если акаунт безлимитный, то обнуляем лимит и бонус
			if ($user_limit_type == 'unlimited') 
			{ 
				$user_limit = 0; 
				$user_out_limit = 0;
				$user_bonus = 0; 
			}
            
			if ($_POST['accountedit_gr_opts'] == 'on')
			{
				$query = "SELECT * FROM `vpnmsgroupreply` WHERE `groupname` = '". $_POST['accountedit_group'] ."'";
            	$res = $db->query($query);
            	$group_opts = $db->fetch_array($res);
            	
            	$allowed_tcp_ports = $group_opts['allow_tcp_port'];
            	$allowed_udp_ports = $group_opts['allow_udp_port']; 
            	$user_limit = $group_opts['limit'];
            	$user_out_limit = $group_opts['out_limit'];
            	$user_limit_type = $group_opts['limit_type'];
            	$user_bw = $group_opts['bandwidth'];	
			}
			
			/*
			 * 	Если акаунт лимитный и если у него стутус "работает", то
			 * 	проверяем его баланс с учетом нового лимита и бонуса и
			 * 	если надо - меняем статус.
			 */
			if (($user_limit_type == 'limited') AND ($_POST['accountedit_status'] == 'working') ) 
		    {
		    	$balance   = $billing->Balance($_POST['accountedit_login'],'current');
            	          	
            	//вычисляем баланс пользователя с учетом нового лимита
            	$new_balance = $user_limit + $user_bonus - $balance['input'];
                $new_balance_out = round ((($user_limit + $user_bonus)/100)*$user_out_limit - $balance['output']);
				
                if (($new_balance <= 0) OR ($new_balance_out <= 0)) 
                    $_POST['accountedit_status'] = 'limit_expire';
            }
            
            //обновляем данные в базе
            $query1 = "UPDATE `radcheck` SET `name` = '". $_POST['accountedit_name'] ."', 
            `lastname` = '". $_POST['accountedit_lastname'] ."', `allow_tcp_port` = '". $allowed_tcp_ports ."',  
            `allow_udp_port` = '". $allowed_udp_ports ."', `limit` = '". $user_limit ."',
            `out_limit` = '". $user_out_limit ."', `bandwidth` = '". $user_bw ."', 
            `limit_type` = '". $user_limit_type ."', `status` = '". $_POST['accountedit_status'] ."',
            `bonus` = '". $user_bonus ."', `value` = '". $_POST['accountedit_passwd'] ."'
            WHERE `username` = '". $_POST['accountedit_login'] ."'";
            
            $query2 = "UPDATE `radusergroup` SET `groupname` = '". $_POST['accountedit_group'] ."' 
            WHERE `username` = '". $_POST['accountedit_login'] ."'";
            
            $query3 = "UPDATE `radreply` SET `value` = '". $_POST['accountedit_ipaddr'] ."' 
            WHERE `username` = '". $_POST['accountedit_login'] ."' AND `attribute` = 'Framed-IP-Address'";
            
            $db->query($query1);
            $db->query($query2);
            $db->query($query3);
            
            //если надо - отключаем пользователя
            if ($_POST['accountedit_disconnect'] == 'on') 
            	$db->query("INSERT INTO `work` ( `id` , `username` , `data` , `operation` )  
            	VALUES 
            	('', '".$_POST['accountedit_login']."', '', 'kill')");	
            
            $page->message($l_message['user_edit']);
            $page->redirect("index.php?module=Users",$config['redirection_time']);
		}
		else if ($_GET['action'] == "delete")
		{
			//проверяем, является ли пользователь последним админом
            $result1 = $db->query("SELECT `admin` FROM radcheck WHERE `username` = '".$_GET['UserName']."';");
            $result2 = $db->query("SELECT * FROM radcheck WHERE `admin` = '1';");
            $cur_user_power =  $db->fetch_array($result1);
            $admins_count = $db->Num_rows($result2);

            if (($cur_user_power['admin'] == '1') AND ($admins_count == 1)) 
            {
            	$page->message($l_message['last_admin']);
				$page->redirect("index.php?module=Users",$config['redirection_time']);
            }
            else 
            {
				              
            	//узнаем баланс за все месяцы
              	$balance   = $billing->Balance($row['UserName'],'current');
              	$balance_1 = $billing->Balance($row['UserName'],'last');
              	$balance_2 = $billing->Balance($row['UserName'],'before_last');
              	
				
	  		  	//записываем суммарный трафик в одну сессию за каждый месяц
              	$db->query("INSERT INTO sessions   (`UserName`,`InternetIn`,`InternetOut`,`LocalIn`,`LocalOut`) VALUES ('@DELETED@','".$balance['input']."','".$balance['output']."','".$balance['local_input']."','".$balance['local_output']."')");
			  	$db->query("INSERT INTO sessions_1 (`UserName`,`InternetIn`,`InternetOut`,`LocalIn`,`LocalOut`) VALUES ('@DELETED@','".$balance_1['input']."','".$balance_1['output']."','".$balance_1['local_input']."','".$balance_1['local_output']."')");
			  	$db->query("INSERT INTO sessions_2 (`UserName`,`InternetIn`,`InternetOut`,`LocalIn`,`LocalOut`) VALUES ('@DELETED@','".$balance_2['input']."','".$balance_2['output']."','".$balance_2['local_input']."','".$balance_2['local_output']."')");
				
              	//удаляем данные о пользователе
              	$db->query("DELETE FROM `radcheck` WHERE `username` = '".$_GET['UserName']."'");
              	$db->query("DELETE FROM `radusergroup` WHERE `username` = '".$_GET['UserName']."'");
              	$db->query("DELETE FROM `radreply` WHERE `username` = '".$_GET['UserName']."'");
              	$db->query("DELETE FROM `sessions` WHERE `UserName` = '".$_GET['UserName']."'");
              	$db->query("DELETE FROM `sessions_1` WHERE `UserName` = '".$_GET['UserName']."'");
              	$db->query("DELETE FROM `sessions_2` WHERE `UserName` = '".$_GET['UserName']."'");
				
				/*
				 * добавить удаление из таблиц статистики по портам  
				 */
				
              	$page->message($l_message['user_del']);
              	$page->redirect("index.php?module=Users",$config['redirection_time']);
            }
		}
		else
			$page->message($l_errors['err_action']);
	}
}
$db->close();
?>