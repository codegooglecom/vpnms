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
	if (empty($_GET['action'])) 
	{
		$result  = $db->query("SELECT * FROM sessions WHERE `Connected` = 1 ORDER BY `SessId`");
       	$num_results = $db->Num_rows($result);

       	include ('templates/' . $config['template'] . '/online_table_header.html');
		
       	for ($i = 0; $i < $num_results; $i++) 
       	{
       		$online = $db->Fetch_array($result);
       		$sum_speed_in  = $sum_speed_in  + $online['Speed_in'];
       		$sum_speed_out = $sum_speed_out + $online['Speed_out'];
       		
       		$tmp = $d = floor($online['SessionTime']/86400);
       		$connection_time = date("$tmp G:i", mktime (0,0,$online['SessionTime']));
       		$in = number_format($online['InternetIn']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
       		$out = number_format($online['InternetOut']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
       		$loc_in = number_format($online['LocalIn']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
       		$loc_out = number_format($online['LocalOut']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
       		$connected = date("m/d/Y G:i:s",$online['StartTime']+$time_correction);
		    if ( $online['Speed_in'] < $config['mb']*$config['mb'] ) 
      			$speed_in = number_format($online['Speed_in']/($config['mb']), $config['precision'], '.', ' ').' kb/s';
      		else 
      			$speed_in = number_format($online['Speed_in']/($config['mb']*$config['mb']), $config['precision'], '.', ' ').' Mb/s';
      
      		if ( $online['Speed_out'] < $config['mb']*$config['mb'] ) 
      			$speed_out = number_format($online['Speed_out']/($config['mb']), $config['precision'], '.', ' ').' kb/s';
      		else 
      			$speed_out = number_format($online['Speed_out']/($config['mb']*$config['mb']), $config['precision'], '.', ' ').' Mb/s';
      			
       		include ('templates/' . $config['template'] . '/online_table_body.html');
       	}

       	include ('templates/' . $config['template'] . '/table_footer.html');
       	
       	if ( $sum_speed_in < $config['mb']*$config['mb'] ) 
			$speed_in_str = number_format($sum_speed_in/($config['mb']), $config['precision'], '.', ' ').' kb/s'; 
		else 
			$speed_in_str = number_format($sum_speed_in/($config['mb']*$config['mb']), $config['precision'], '.', ' ').' Mb/s'; 

		if ( $sum_speed_out < $config['mb']*$config['mb'] ) 
			$speed_out_str = number_format($sum_speed_out/($config['mb']), $config['precision'], '.', ' ').' kb/s'; 
		else 
			$speed_out_str = number_format($sum_speed_out/($config['mb']*$config['mb']), $config['precision'], '.', ' ').' Mb/s'; 
       	
       	include ('templates/' . $config['template'] . '/online_summ_speed_table.html');
	}
	else 
	{
		if ($_GET['action'] == "disconnect") 
		{
            $billing->disconnect_user($_GET['UserName']);  
            $page->message($l_message['onl_disc']);
            $page->redirect("index.php?module=OnLine",$config['redirection_time']);
        }
	}
}
?>
