<?
/*
 VPNMS Project (c) 2005-2008 Andrey Chebotarev aka Metallic
 email: metallicvrn@gmail.com

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.
*/

if (!defined('IN_VPNMS')) exit;

class Page
{

function DisplayHeader () 
{
	GLOBAL $config, $navbar;        
	
	include ('templates/' . $config['template'] . '/style.css');
	include ('templates/' . $config['template'] . '/header.html');
	include ('templates/' . $config['template'] . '/navbar.html');
	include ('templates/' . $config['template'] . '/cur_position.html');	
}

function DisplaySmallHeader ()
{
	GLOBAL $config;        
	
	include ('templates/' . $config['template'] . '/style.css');
	include ('templates/' . $config['template'] . '/showinfo_header.html');
}

function DisplayFooter ()
{ 
	GLOBAL $config;
	
	include ('templates/' . $config['template'] . '/footer.html');
}

function message ($content) 
{
	GLOBAL $config, $l_message;

	include ('templates/' . $config['template'] . '/message.html');
}

function redirect ($url,$delay) 
{
	GLOBAL $config;

	include ('templates/' . $config['template'] . '/redirect.html');
}

}

class Security
{
var $auth_status = 0;


function LogIn ($login,$passwd) {
        $db = new DB ();
        $db->connect();
        $query = "SELECT radcheck.id FROM `radcheck` WHERE UserName='".$login."' AND Value='".$passwd."' AND Attribute='Cleartext-Password'";
        $result = $db->query($query);
        $count = $db->num_rows($result);

        if ($count > 0) 
        	return true;
        else
        	return false;
}

//-----------------------------------------------------------------------------------
//-- ф-ия определяет, является ли залогиненный пользователь админом.
//-----------------------------------------------------------------------------------
function is_it_admin ($login) {        $db = new DB ();
        $db->connect();
        $query = "SELECT radcheck.admin FROM `radcheck` WHERE UserName='".$login."'";
        $result = $db->query($query);
        $row = $db->Fetch_array($result);

        if ($row['admin'] == '1') 
        	return true;
        else
        	return false;}

function superslashes($var)
{
	if(is_array($var))
	{
		$outvar = array();
		
		foreach($var as $k => $v)
			$outvar[$k] = $this->superslashes($v);
			
		return $outvar;
	}
	else
	{
		return addslashes($var);
	}
}

}

class DB
{
var $connect_id;

function Connect () {
        GLOBAL $config, $connect_id, $l_errors;
        
        $this->connect_id = @mysql_connect($config['db_host'],$config['db_user'],$config['db_passwd']) OR 
        DIE($l_errors['db_connect_error']);
        @mysql_select_db($config['db_name'], $this->connect_id) or die(mysql_error());
        return $this->connect_id;
        }

function Query ($query) {
		GLOBAL $l_errors;
		
        //echo "<br> ".$query;
		$id = mysql_query($query, $this->connect_id);
        
		if (!$id) 
        {        	 echo $l_errors['sql_error'] . "<br>";        	 echo "<br> ".$query." <br> <br>";
        	 exit;        }        
        
        return $id;
        }

function Num_rows ($result) {
        return @mysql_num_rows($result);
        }

function Fetch_array ($result) {
        return mysql_fetch_array($result, MYSQL_ASSOC);
        }

function Free_result ($result) {
                if ($result) {
                @mysql_free_result($result);
                }
        }

function Close () {
        @mysql_close($this->connect_id);
        }
}

Class Billing
{

Function Balance ($UserName,$month='current') {
         GLOBAL $config;

         $balance_rec = array ("balance"=>0,
         					   "balance_mb"=>0,
         					   "input"=>0,
         					   "input_mb"=>0,
         					   "output"=>0,
         					   "output_mb"=>0,
         					   "local_input"=>0,
         					   "local_input_mb"=>0,
         					   "local_output"=>0,
         					   "local_output_mb"=>0,
         					   "bonus"=>0,
         					   "bonus_mb"=>0,
         					   "limit"=>0,
         					   "limit_mb"=>0,
         					   "out_limit"=>0,
         						"limit_type"=>"",
         						"groupname"=>"",
         						"bw_id"
         						);

         if ($month == 'last') 
         	$month = 'sessions_1';
         else if ($month == 'before_last')
         	$month = 'sessions_2';
         else
         	$month = 'sessions';

         $db = new DB ();

		 $query1 = "SELECT SUM( InternetIn )  AS 'input',SUM( InternetOut ) AS 'output',
		 SUM( LocalIn )  AS 'local_input', SUM( LocalOut ) AS 'local_output' 
		 FROM `".$month."` WHERE `UserName` = '".$UserName."'";
         
         $query2 = "SELECT * FROM radcheck WHERE `UserName` = '".$UserName."'";
         $query3 = "SELECT `groupname` FROM `radusergroup` WHERE `UserName` = '".$UserName."'";

         $db->connect();

         $result1 = $db->query($query1);
         $result2 = $db->query($query2);
         $result3 = $db->query($query3);

         $traf = $db->fetch_array($result1);
         $info = $db->fetch_array($result2);
         $group = $db->fetch_array($result3);

         $balance_rec["input"]  = $traf["input"];
         $balance_rec["output"] = $traf["output"];
         $balance_rec["local_input"] = $traf["local_input"];
         $balance_rec["local_output"] = $traf["local_output"];
         $balance_rec["balance"] = $info['limit'] - $traf['input'] + $info['bonus'];
         $balance_rec["bonus"] = $info['bonus'];
         $balance_rec["limit"] = $info['limit'];
         $balance_rec["out_limit"] = $info['out_limit'];
         $balance_rec["limit_type"] = $info['limit_type'];
         $balance_rec["bw_id"] = $info['bandwidth'];
         $balance_rec["groupname"] = $group['groupname'];

         $balance_rec["input_mb"]  = number_format($traf["input"]/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
         $balance_rec["output_mb"] = number_format($traf["output"]/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
         $balance_rec["local_input_mb"] = number_format($traf["local_input"]/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
         $balance_rec["local_output_mb"] = number_format($traf["local_output"]/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
         $balance_rec["balance_mb"] = number_format($balance_rec["balance"]/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
         $balance_rec["bonus_mb"] = number_format($info['bonus']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
         $balance_rec["limit_mb"] = number_format($balance_rec["limit"]/($config['mb']*$config['mb']), $config['precision'], '.', ' ');

return $balance_rec;

return $balance_rec;
}

/*
 *  Конвертирует длинную маску в укороченный вид
 *  Пример: 255.255.255.0 конвертируется в 24
 */
function mask_2_cidr($mask)
{
    $a=strpos(decbin(ip2long($mask)),"0");
    if (!$a){$a=32;}
    return $a;
}


function ShowUsers($orderby, $month)
{
		Global $db, $config, $l_tables, $sum_input, $sum_output, $sum_local_input, $sum_local_output,
		$working_accounts, $blocked_accounts, $expire_accounts, $localonly_accounts, $account_summ;
	
		$result  = $db->query("SELECT * FROM `radcheck` ORDER BY `".$orderby."`");
		$num_results = $db->Num_rows($result);
		
		include ('templates/' . $config['template'] . '/users_table_header.html');
		
		$working_accounts = 0;
        $blocked_accounts = 0;
        $expire_accounts = 0;
        $localonly_accounts = 0;
        $account_summ = 0;
		$account_number = 1;

	    for ($i = 0; $i < $num_results; $i++) 
	    {
        	$row = $db->Fetch_array($result);

          	$balance = $this->Balance($row['username'],$month);

          	$sum_input = $sum_input + $balance["input"];
          	$sum_output = $sum_output + $balance["output"];
          	$sum_local_input = $sum_local_input + $balance["local_input"];
          	$sum_local_output = $sum_local_output + $balance["local_output"];

          	if ($row['status'] == 'working') {$working_accounts++;}
          	if ($row['status'] == 'blocked') {$blocked_accounts++;}
          	if ($row['status'] == 'limit_expire') {$expire_accounts++;}
          	if ($row['status'] == 'local_only') {$localonly_accounts++;}

          	include ('templates/' . $config['template'] . '/users_table_body.html');
          	
          	$account_number++;
        }
		
		include ('templates/' . $config['template'] . '/users_table_footer.html');
		
		//приплюсовываем трафик удаленных пользователей
        $balance = $this->Balance('@DELETED@',$month);

        $sum_input = $sum_input + $balance["input"];
        $sum_output = $sum_output + $balance["output"];
        $sum_local_input = $sum_local_input + $balance["local_input"];
        $sum_local_output = $sum_local_output + $balance["local_output"];
        
        $sum_input = number_format($sum_input/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
        $sum_output = number_format($sum_output/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
        $sum_local_input = number_format($sum_local_input/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
        $sum_local_output = number_format($sum_local_output/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
        
        $account_summ = $account_number - 1;
}

function ShowGroups($orderby)
{
	Global $db, $config, $l_tables;
	
	$result  = $db->query("SELECT * FROM `vpnmsgroupreply` ORDER BY `".$orderby."`");
	$num_results = $db->Num_rows($result);
		
	include ('templates/' . $config['template'] . '/groups_table_header.html');

	$group_number = 1;
	
	for ($i = 0; $i < $num_results; $i++) 
	{
       	$row = $db->Fetch_array($result);
       	
       	$gr_limit = number_format($row['limit']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
       	$bw_res = $db->query("SELECT bandwidth_name FROM `bandwidth` WHERE `bw_id` = ". $row['bandwidth']);
       	$bw_name = $db->Fetch_array($bw_res); 

       	include ('templates/' . $config['template'] . '/groups_table_body.html');
          	
       	$group_number++;
    }
	
	include ('templates/' . $config['template'] . '/users_table_footer.html');
	
}

function ShowMainInfo($UserName, $month = 'current')
{
    GLOBAL $db, $billing, $config, $user_power_admin, $l_tables, $l_forms;
	
    $result  = $db->query("SELECT * FROM radcheck WHERE `UserName` = '".$UserName."'");
    $balance = $billing->balance($UserName,$month);
    $info = $db->Fetch_array($result);
    $ip = $billing->get_ip_by_name($info['username']);
    $scriptname = basename($_SERVER["SCRIPT_NAME"]);
    $bw_name = $billing->get_bw_name($info['bandwidth']);
		
    include ('templates/' . $config['template'] . '/account_info_table.html');
}

function ShowConnections($UserName, $orderby = 'SessId', $month = 'current')
{
    GLOBAL $db, $config, $user_power_admin, $l_tables;
	
	if (empty($orderby)) 
    	$orderby = 'SessId';
    	
   	if (empty($month) OR $month == 'current') 
	  	$month = 'sessions';
    if ($_GET['month'] == 'last')
       	$month = 'sessions_1';
    if ($_GET['month'] == 'before_last')
       	$month = 'sessions_2';
       	
    $scriptname = basename($_SERVER["SCRIPT_NAME"]);
	
	include ('templates/' . $config['template'] . '/connects_table_header.html');
    
    $result  = $db->query("SELECT * FROM `".$month."` WHERE `UserName` = '".$UserName."' ORDER BY `".$orderby."`");
    $num_rows = $db->num_rows($result);
    for ($i=0; $i < $num_rows; $i++ ) 
    {
    	$data = $db->Fetch_array($result);
    	$StartTime = date("Y-n-d H:i:s",$data['StartTime'] + $config['time_correction']);
    	$StopTime = date("Y-n-d H:i:s",$data['StopTime'] + $config['time_correction']);
    	$d = floor($data['SessionTime']/86400);
    	$InOnline = date("$d H:i:s", mktime(0, 0, $data['SessionTime']));
    	$InternetIn = number_format($data['InternetIn']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
    	$InternetOut = number_format($data['InternetOut']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
    	$LocalIn = number_format($data['LocalIn']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
    	$LocalOut = number_format($data['LocalOut']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
    	
        include ('templates/' . $config['template'] . '/connects_table_body.html');
    }
	
	include ('templates/' . $config['template'] . '/table_footer.html');
}

function get_bw_name ($bw_id)
{
	GLOBAL $db;
	
	$result  = $db->query("SELECT bandwidth_name FROM `bandwidth` WHERE `bw_id` = '".$bw_id."'");
	$data = $db->Fetch_array($result);
	
	return $data['bandwidth_name'];
}

function disconnect_user($username)
{
	GLOBAL $config;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $config['mpd_url']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);	
	curl_setopt($ch, CURLOPT_USERPWD, $config['mpd_user'].":".$config['mpd_pass']);

	$parts = explode ("<TR>" ,curl_exec($ch));

	for ($i = 0; $i < count($parts); $i++)
	{
		if (strpos($parts[$i], "auth\">". $username ."</a>") != '')
		{
			$part = $parts[$i];
			break;
		}		
	}

	if (!empty($part))
	{
		$parts = explode ("link\">" ,$part);

		for ($i = 0; $i < count($parts); $i++)
		{
			if (strpos($parts[$i], "auth\">". $username ."</a>") != '')
			{
				$part = $parts[$i];
				break;
			}		
		}

		$parts = explode ("</a></TD>" ,$part);
		$link = $parts[0];

		$close_url = $config['mpd_url'] ."/cmd?link%20". $link ."&close";

		curl_setopt($ch, CURLOPT_URL, $close_url);
		curl_exec($ch);
	}

	curl_close($ch);
}

function CheckData ($UserName,$tcp_ports,$udp_ports,$ip_addr,$limit_type,$limit) {
/*
1 - Имя пользователя уже занято
  - Имя пользователя пропущено
2 - Неправильно введены данные в поле tcp-порты
3 - Неправильно введены данные в поле udp-порты
4 - IP-адрес занят
5 - Неправильно введен IP-адрес
6 - Не введен лимит для лимитированного аккаунта
ok - Проверка прошла успешно
*/

$db = new DB ();
$page = new Page ();
$db->connect();

//1
if (empty($UserName)) {
$page->message($l_forms['no_login']);
exit;
}

$result = $db->query("SELECT * FROM `radcheck` WHERE `username` = '".$UserName."'");
if ($db->num_rows($result) > 0) {
$page->message($l_forms['login_used']);
exit;
}

//4
$result = $db->query("SELECT * FROM `radreply` WHERE `value` = '".$ip_addr."' AND `attribute` = 'Framed-IP-Address'");
if ($db->num_rows($result) > 0) {
$page->message($l_forms['ip_used']);
exit;
}

}

function get_ip_by_name ($UserName) {
$db = new DB ();
$db->connect();
$result = $db->query("SELECT value FROM `radreply` WHERE `username` = '".$UserName."' AND `attribute` = 'Framed-IP-Address'");
$data = $db->fetch_array($result);
return $data['value'];
}

function get_name_by_ip ($IP) {
$db = new DB ();
$db->connect();
$result = $db->query("SELECT * FROM `radreply` WHERE `Value` = '".$IP."' AND `Attribute` = 'Framed-IP-Address'");
$data = $db->fetch_array($result);
return $data['UserName'];
}

function get_status ($UserName) {
$db = new DB ();
$db->connect();
$result = $db->query("SELECT * FROM `radacct` WHERE `UserName` = '".$UserName."' AND `AcctStopTime` = '0000-00-00 00:00:00'");
$count = $db->num_rows($result);
if ($count > 0) { return true; } else { return false; }
}

function CheckGroupData ($GroupName) 
{
$db = new DB ();
$page = new Page ();
$db->connect();

if (empty($GroupName)) 
{
	$page->message($l_forms['no_group']);
	exit;
}

$result = $db->query("SELECT groupname FROM `vpnmsgroupreply` WHERE `groupname` = '".$GroupName."'");
if ($db->num_rows($result) > 0) 
{
	$page->message($l_forms['group_used']);
	exit;
}

}

function PersonalOpts ($UserName)
{
	$db = new DB ();
	
	//выбираем опции пользователя
	$query = "SELECT * FROM radcheck WHERE `username` = '". $UserName ."'";
	$db->connect();
	$res = $db->query($query);
	$user_opts = $db->Fetch_array($res); 
	
	//вычисляем имя группы
	$query = "SELECT groupname FROM radusergroup WHERE `username` = '". $UserName ."'";
	$db->connect();
	$res = $db->query($query);
	$groupname = $db->Fetch_array($res);
	
	//выбираем опции группы
	$query = "SELECT * FROM vpnmsgroupreply WHERE `groupname` = '". $groupname['groupname'] ."'";
	$db->connect();
	$res = $db->query($query);
	$group_opts = $db->Fetch_array($res);
	
	if ($user_opts['allow_tcp_port'] != $group_opts['allow_tcp_port'])
		return true;
	if ($user_opts['allow_udp_port'] != $group_opts['allow_udp_port'])
		return true;
	if ($user_opts['limit'] != $group_opts['limit'])
		return true;
	if ($user_opts['out_limit'] != $group_opts['out_limit'])
		return true;
	if ($user_opts['bandwidth'] != $group_opts['bandwidth'])
		return true;
	if ($user_opts['limit_type'] != $group_opts['limit_type'])
		return true;	
	
	return false;
}

function get_report_dir ($UserName,$day,$month='current') {//-------------------------------------------------------------------------------------------
//-- Вычисляем путь до отчетов
//-------------------------------------------------------------------------------------------

include "config.inc.php";
$change_year = false;

//Вычисляем год
if ($month == 'current') $year = date("Y");

//Вычисляем месяц
if ($month == 'current') $month = date("m");
	if ($month == 'last') {
    $month = date("n");
    $month = $month - 1;
     if ($month == 0) { $month = '12'; $change_year = true; }
     if ( ($month == '1') OR ($month == '2') OR ($month == '3') OR ($month == '4') OR ($month == '5') OR ($month == '6') OR ($month == '7') OR ($month == '8') OR ($month == '9') ) $month = '0'.$month;
	}

	if ($month == 'before_last') {
    $month = date("n");
    $month = $month - 2;
     if ($month == 0) { $month = '12'; $change_year = true; }
     if ($month == -1) { $month = '12'; $change_year = true; }
     if ( ($month == '1') OR ($month == '2') OR ($month == '3') OR ($month == '4') OR ($month == '5') OR ($month == '6') OR ($month == '7') OR ($month == '8') OR ($month == '9') ) $month = '0'.$month;
	}

//Корректируем год если надо
if ($change_year == true) $year = $year - 1;

//Вычисляем день
if ( ($day == '1') OR ($day == '2') OR ($day == '3') OR ($day == '4') OR ($day == '5') OR ($day == '6') OR ($day == '7') OR ($day == '8') OR ($day == '9') ) $day = '0'.$day;

$path = $path_to_http_reports.$year.$month.$day;
//-------------------------------------------------------------------------------------------

if ($debug == true) echo 'путь к отчетам: '.$path.'<br>';

return $path;
}

function get_http_main_stat ($UserName,$day,$month='current') {include "config.inc.php";
$path = $this->get_report_dir($UserName,$day,$month);

$http_main_stat['connects'] = 0;
$http_main_stat['bytes'] = 0;

@ $file = fopen($path.'/.total',"r");

$user_ip = $this->get_ip_by_name($UserName);

  if ($file <> false) {
  while ($data = fgets ($file, 1000)) {  $data = preg_replace("/(\s+)/", " ",$data);
  $data_parts = explode(" ",$data);
	if ($data_parts[0] == $user_ip) {	$http_main_stat['bytes'] = $data_parts[1];
	$http_main_stat['connects'] = $data_parts[2];
	break;	}
  }}

return $http_main_stat;
}

function bytes_format($bytes,$mode='main') {include "config.inc.php";
if ($mode == 'main') {	if ($bytes <  $mb ) $bytes = $bytes.' B';
	if ( ($bytes >= $mb )     	  AND ($bytes < $mb*$mb) ) 	   	   $bytes = number_format($bytes/($mb), $precision, '.', ' ').' KB';
	if ( ($bytes >= $mb*$mb ) 	  AND ($bytes < $mb*$mb*$mb) ) 	   $bytes = number_format($bytes/($mb*$mb), $precision, '.', ' ').' MB';
	if ( ($bytes >= $mb*$mb*$mb ) AND ($bytes < $mb*$mb*$mb*$mb) ) $bytes = number_format($bytes/($mb*$mb*$mb), $precision, '.', ' ').' GB';

}

if ($mode == 'time') {
	if ($bytes < 100*$mb*$mb) 	   	   $bytes = number_format($bytes/($mb*$mb), 1, '.', ' ').'M';
	if ($bytes >= 100*$mb*$mb ) 	   $bytes = number_format($bytes/($mb*$mb*$mb), 1, '.', ' ').'G';
}


return $bytes;
}

function report_bytes_total($UserName,$day,$month='current') {$total = 0;
$path = $this->get_report_dir($UserName,$day,$month);
$user_ip = $this->get_ip_by_name($UserName);

@ $file = fopen($path.'/'.$user_ip,"r");

  if ($file <> false) {
  $data = fgets ($file, 1000);
  $data = preg_replace("/(\s+)/", " ",$data);
  $data_parts = explode(" ",$data);
  $total = $data_parts[1];
  }


return $total;
}

function report_check_big_files ($UserName,$day,$month='current') {include "config.inc.php";
$path = $this->get_report_dir($UserName,$day,$month);

$counter = 0;
$BigFiles['0'] = 'empty';

@ $file = fopen($path.'/.bigfiles',"r");
$user_ip = $this->get_ip_by_name($UserName);

  if ($file <> false) {   while ($data = fgets ($file, 1000)) {
   $data = preg_replace("/(\s+)/", " ",$data);
   $data_parts = explode(" ",$data);
  	 if ($data_parts[0] == $user_ip) {  	 $bigfile['time'] = $data_parts['1'];
  	 $bigfile['size'] = $data_parts['2'];
  	 $bigfile['url']  = $data_parts['3'];     $BigFiles[$counter] = $bigfile;
	 $counter++;
	 }
   }  }

return $BigFiles;}

function report_get_urls ($UserName,$day,$month='current') {include "config.inc.php";
$path = $this->get_report_dir($UserName,$day,$month);

$urls['0'] = 'empty';
$counter = 0;
$user_ip = $this->get_ip_by_name($UserName);
@ $file = fopen($path.'/'.$user_ip,"r");

  if ($file <> false) {   while ($data = fgets ($file, 3000)) {   $data = preg_replace("/(\s+)/", " ",$data);
   $data_parts = explode(" ",$data);
     if ( $data_parts['0'] <> 'total:' ) {     $url['url'] 	  = $data_parts['0'];
     $url['bytes'] 	  = $data_parts['1'];
     $url['connects'] = $data_parts['2'];
     $urls[$counter]  = $url;
     $counter++;
   	 }   }
  }

return $urls;
}

function report_get_urls_time ($UserName,$day,$month='current') {include "config.inc.php";
$path = $this->get_report_dir($UserName,$day,$month);

$urls['0'] = 'empty';
$counter = 0;

$user_ip = $this->get_ip_by_name($UserName);
@ $file = fopen($path.'/'.$user_ip,"r");

  if ($file <> false) {
   while ($data = fgets ($file, 3000)) {
   $data = preg_replace("/(\s+)/", " ",$data);
   $data_parts = explode(" ",$data);
     if ( $data_parts['0'] <> 'total:' ) {     $t00 = $data_parts['3'];  $t00 = explode("-",$t00); $t00 = $t00['1'];
     $t01 = $data_parts['4'];  $t01 = explode("-",$t01); $t01 = $t01['1'];
     $t02 = $data_parts['5'];  $t02 = explode("-",$t02); $t02 = $t02['1'];
     $t03 = $data_parts['6'];  $t03 = explode("-",$t03); $t03 = $t03['1'];
     $t04 = $data_parts['7'];  $t04 = explode("-",$t04); $t04 = $t04['1'];
     $t05 = $data_parts['8'];  $t05 = explode("-",$t05); $t05 = $t05['1'];
     $t06 = $data_parts['9'];  $t06 = explode("-",$t06); $t06 = $t06['1'];
     $t07 = $data_parts['10']; $t07 = explode("-",$t07); $t07 = $t07['1'];
     $t08 = $data_parts['11']; $t08 = explode("-",$t08); $t08 = $t08['1'];
     $t09 = $data_parts['12']; $t09 = explode("-",$t09); $t09 = $t09['1'];
     $t10 = $data_parts['13']; $t10 = explode("-",$t10); $t10 = $t10['1'];
     $t11 = $data_parts['14']; $t11 = explode("-",$t11); $t11 = $t11['1'];
     $t12 = $data_parts['15']; $t12 = explode("-",$t12); $t12 = $t12['1'];
     $t13 = $data_parts['16']; $t13 = explode("-",$t13); $t13 = $t13['1'];
     $t14 = $data_parts['17']; $t14 = explode("-",$t14); $t14 = $t14['1'];
     $t15 = $data_parts['18']; $t15 = explode("-",$t15); $t15 = $t15['1'];
     $t16 = $data_parts['19']; $t16 = explode("-",$t16); $t16 = $t16['1'];
     $t17 = $data_parts['20']; $t17 = explode("-",$t17); $t17 = $t17['1'];
     $t18 = $data_parts['21']; $t18 = explode("-",$t18); $t18 = $t18['1'];
     $t19 = $data_parts['22']; $t19 = explode("-",$t19); $t19 = $t19['1'];
     $t20 = $data_parts['23']; $t20 = explode("-",$t20); $t20 = $t20['1'];
     $t21 = $data_parts['24']; $t21 = explode("-",$t21); $t21 = $t21['1'];
     $t22 = $data_parts['25']; $t22 = explode("-",$t22); $t22 = $t22['1'];
     $t23 = $data_parts['25']; $t23 = explode("-",$t23); $t23 = $t23['1'];

     $url['url'] 	  = $data_parts['0'];
     $url['total'] 	  = $data_parts['1'];
     $url['00'] 	  = $t00;
     $url['01'] 	  = $t01;
     $url['02'] 	  = $t02;
     $url['03'] 	  = $t03;
     $url['04'] 	  = $t04;
     $url['05'] 	  = $t05;
     $url['06'] 	  = $t06;
     $url['07'] 	  = $t07;
     $url['08'] 	  = $t08;
     $url['09'] 	  = $t09;
     $url['10'] 	  = $t10;
     $url['11'] 	  = $t11;
     $url['12'] 	  = $t12;
     $url['13'] 	  = $t13;
     $url['14'] 	  = $t14;
     $url['15'] 	  = $t15;
     $url['16'] 	  = $t16;
     $url['17'] 	  = $t17;
     $url['18'] 	  = $t18;
     $url['19'] 	  = $t19;
     $url['20'] 	  = $t20;
     $url['21'] 	  = $t21;
     $url['22'] 	  = $t22;
     $url['23'] 	  = $t23;

     $urls[$counter]  = $url;
     $counter++;
   	 }
   }
  }


return $urls;
}

function test1 ($a) {return 0;}

}
?>
