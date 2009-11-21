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
		
	}
}
?>