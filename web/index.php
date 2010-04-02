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

$page->DisplayHeader();

//проверяем все входящие данные
$_GET = $sec->superslashes($_GET);
$_POST = $sec->superslashes($_POST);

//--------------------------------------------------------------
//        Выбор модуля для загрузки контента
//--------------------------------------------------------------

switch ($_GET[module]) {
	case "":
		include("modules/start.php");
		break;
	case "Start":
		include("modules/start.php");
		break;
	case "Balance":
		include("modules/balance.php");
		break;
	case "Autorization":
		include("modules/auth.php");
		break;
	case "OnLine":
		include("modules/online.php");
		break;
	case "Users":
		include("modules/users.php");
		break;
	case "Groups":
		include("modules/groups.php");
		break;
	case "Admin-cp":
		include("modules/admin-cp.php");
		break;
	case "Login":
		include("modules/login.php");
		break;
	case "Logout":
		include("modules/logout.php");
		break;
	default:
		$page->message($l_message['module_not_found']);
}

$page->DisplayFooter();

?>