<?
if (!defined('IN_VPNMS')) exit;

$db->connect();

if (empty($_SESSION['session_login'])) 
{
	$page->message($l_message['not_authorized']);
	$page->redirect("index.php?module=Login",$config['redirection_time']);
}
else
{
	if (empty($_GET['action']) OR $_GET['action'] == 'main') 
	{
		$billing->ShowMainInfo($_SESSION['session_login']);
	}
	else if ($_GET['action'] == 'connects')
	{
		$billing->ShowConnections($_SESSION['session_login']);
	}
	else if ($_GET['action'] == 'hourlystat')
	{
		$billing->ShowHourlyStat($_SESSION['session_login']);
	}
	else if ($_GET['action'] == 'hosts')
	{
		$billing->ShowWWWStat($_SESSION['session_login']);
	}
	else if ($_GET['action'] == 'change_pass')
	{
		include ('templates/' . $config['template'] . '/user_change_pass.html');
	}
	else if ($_GET['action'] == 'save_pass')
	{
		if ( ($_POST['user_change_pass_passwd'] == $_POST['user_change_pass_confim']) AND ($_POST['user_change_pass_passwd'] <> '') ) 
		{
    	$db->query("UPDATE `radcheck` SET `Value` = '".$_POST['user_change_pass_passwd']."' WHERE `username` = '".$_SESSION['session_login']."'");
    	
    	$page->message($l_message['pass_changed']);
    	$page->redirect("index.php?module=Balance",$config['redirection_time']);	
		}
	}
	else
		$page->message($l_errors['err_action']);

	include ('templates/' . $config['template'] . '/user_menu.html');
}

?>
