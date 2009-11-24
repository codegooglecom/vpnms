<?
include ("includes/config.inc.php");
include ("lang/" . $config['lang'] . ".php");
include ("includes/classes.inc.php");

//Регистрируем сессию----
session_start();

//Отключаем вывод информационных сообщений
error_reporting(E_ERROR);

//Объявляем классы-------
$page = new page();
$db = new DB ();
$sec = new Security ();
$billing = new Billing ();

$page->DisplaySmallHeader();

$db->connect();

$user_power_admin = $sec->is_it_admin($_SESSION['session_login']);

if (empty($_SESSION['session_login'])) 
{
	$page->message($l_message['not_authorized']);
	$page->redirect("index.php?module=Login",$config['redirection_time']);
}
else if ($user_power_admin == false)
	$page->message($l_message['no_access']);
else
{
	if (empty($_GET['action']) OR $_GET['action'] == 'main')
	{
		$result  = $db->query("SELECT * FROM radcheck WHERE `UserName` = '".$_GET['UserName']."'");
        $balance = $billing->balance($_GET['UserName'],$_GET['month']);
        $info = $db->Fetch_array($result);
        $ip = $billing->get_ip_by_name($info['username']);
		
        include ('templates/' . $config['template'] . '/account_info_table.html');
	}
	else if ($_GET['action'] == 'connects')
	{      	
    	$billing->ShowConnections("qwe");   
	}

include ('templates/' . $config['template'] . '/user_info_menu.html');
include ('templates/' . $config['template'] . '/showinfo_footer.html');
}
?>