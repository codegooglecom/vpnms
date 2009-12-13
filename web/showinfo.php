<?
//Регистрируем сессию----
session_start();

//Отключаем вывод информационных сообщений
error_reporting(E_ERROR);

define('IN_VPNMS', 1);

include ("includes/config.inc.php");
include ("lang/" . $config['lang'] . ".php");
include ("includes/classes.inc.php");

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
		$billing->ShowMainInfo($_GET['UserName'], $_GET['month']);
	}
	else if ($_GET['action'] == 'connects')
	{      	
    	$billing->ShowConnections($_GET['UserName'], $_GET['orderby'], $_GET['month']);   
	}

include ('templates/' . $config['template'] . '/user_info_menu.html');
include ('templates/' . $config['template'] . '/showinfo_footer.html');
}
?>
