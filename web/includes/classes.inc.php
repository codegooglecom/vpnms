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
         	$rotation = '2';
         else if ($month == 'before_last')
         	$rotation = '3';
         else
         	$rotation = '1';

         $db = new DB ();

		 $query1 = "SELECT SUM( InternetIn )  AS 'input',SUM( InternetOut ) AS 'output',
		 SUM( LocalIn )  AS 'local_input', SUM( LocalOut ) AS 'local_output' 
		 FROM `sessions` WHERE `UserName` = '".$UserName."' AND `Rotation` = '".$rotation."'";
         
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
       	
       	//выбираем тип авторизации
       	$auth_res  = $db->query("SELECT `Value` FROM `radgroupcheck` WHERE `Attribute` = 'Auth-Type' AND `GroupName` = '".$row['groupname']."'");
       	$auth_row = $db->Fetch_array($auth_res);
       	
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
	  	$rotation = '1';
    if ($month == 'last') 
       	$rotation = '2';
    if ($month == 'before_last')
        $rotation = '3';
         	       	
    $scriptname = basename($_SERVER["SCRIPT_NAME"]);
	
	include ('templates/' . $config['template'] . '/connects_table_header.html');
    
    $result  = $db->query("SELECT * FROM `sessions` WHERE `UserName` = '".$UserName."' AND `Rotation` = '".$rotation."' ORDER BY `".$orderby."`");
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

function ShowHourlyStat ($UserName, $month = 'current')
{
	GLOBAL $db, $config, $l_tables;

	/*
	 *  $days - кол-во дней в месяце 
	 */
	
	if (empty($month) OR $month == 'current')
	{ 
	  	$rotation = '1';
	  	$days = date(j);
	  	$month = date(F);
	  	$year = date(Y);
	}	
    if ($month == 'last')
    { 
       	$rotation = '2';
       	
       	$n=date("j");
		$n=intval($n);
		$n=time()-$n*86400+1;

		$days=date("j",$n);
       	$month=date("F",$n);
       	$year=date("Y",$n);
    }
    if ($month == 'before_last')
    {
        $rotation = '3';

        $time=time();
        $n=date("j");
		$n=intval($n);
		$n=$time-$n*86400+1;
        $days=date("j",$n);
        $n=$n-$days*86400;

        $days=date("j",$n);
       	$month=date("F",$n);
       	$year=date("Y",$n);
    }
    
    $script_parts = explode("/",$_SERVER['PHP_SELF']);
    $script_name = $script_parts[sizeof($script_parts)-1];   

//в цикле, входящий, исходящий, лок. вх., лок. исх.
for ($j=1; $j <= 4; $j++)
{    
	switch ($j) {
		case 1:
			$title = $l_tables['input'];
			$direction = "input";
			$local = '0';
			break;
		case  2:
			$title = $l_tables['output'];
			$direction = "output";
			$local = '0';
			break;
		case  3:
			$title = $l_tables['loc_input'];
			$direction = "input";
			$local = '1';
			break;
		case  4:
			$title = $l_tables['loc_output'];
			$direction = "output";
			$local = '1';
			break;
	}
	
	include ('templates/' . $config['template'] . '/hourlystat_table_header.html');
	
	if (empty($_GET['day']))
	{
		$cnt = $days;
	}
	
	else if (preg_match("/^[0-9]{1,2}$/" ,$_GET['day']))
	{
		$start_hour = 0;
		$cnt = 24;
	}
	
	/*
	 *  Если отображаем за день статистику, то $i - день
	 *  Если за каждый час, то $i - час
	 */
	
	for ($i=1; $i <= $cnt; $i++)
	{
		if (empty($_GET['day']))
		{
			$cur_date = $i." ".$month." ".$year;
			
			$day_timestamp =  strtotime("$i $month $year");
			$period_start = $day_timestamp;
			$period_end = $day_timestamp + 86400;
			
			$sql_add = "AND `timestamp` >= ".$period_start." AND `timestamp` <= ".$period_end;
		}
		else if (preg_match("/^[0-9]{1,2}$/" ,$_GET['day']))
		{
			$cur_date = $start_hour."-".++$start_hour;
			
			$day = $_GET['day'];
			$period = strtotime("$i:00 $day $month $year");
			$sql_add = "AND `timestamp` = ".$period;
		}
		
		$sql = "SELECT 
				SUM( HTTP )	AS 'http',  
				SUM( HTTPS ) AS 'https',
				SUM( SSH ) AS 'ssh',
				SUM( ICQ ) AS 'icq',
				SUM( SMTP ) AS 'smtp',
				SUM( SSMTP ) AS 'ssmtp',
				SUM( POP3 ) AS 'pop3',
				SUM( POP3S ) AS 'pop3s',
				SUM( IMAP ) AS 'imap',
				SUM( IMAPS ) AS 'imaps',
				SUM( IMAPSSL ) AS 'imapssl',
				SUM( DNS ) AS 'dns',
				SUM( other ) AS 'other',
				SUM( `all` ) AS 'all' 
 			
				FROM `hourlystat` 
				WHERE `owner` = '".$UserName."' 
				AND `direction` = '".$direction."'
				AND `local` = '".$local."' ".$sql_add;
		
		$result  = $db->query($sql);
		$bytes = $db->Fetch_array($result);
		
		$http = number_format($bytes['http']/($config['mb']*$config['mb']), $config['precision'], '.', ' '); 
		$https = number_format($bytes['https']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$ssh = number_format($bytes['ssh']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$icq = number_format($bytes['icq']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$smtp = number_format($bytes['smtp']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$ssmtp = number_format($bytes['ssmtp']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$pop3 = number_format($bytes['pop3']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$pop3s = number_format($bytes['pop3s']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$imap = number_format($bytes['imap']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$imaps = number_format($bytes['imaps']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$imapssl = number_format($bytes['imapssl']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$dns = number_format($bytes['dns']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$other = number_format($bytes['other']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		$all = number_format($bytes['all']/($config['mb']*$config['mb']), $config['precision'], '.', ' ');
		
		include ('templates/' . $config['template'] . '/hourlystat_table_body.html');
		//echo $sql."<br><br>";
	}	
	
	include ('templates/' . $config['template'] . '/table_footer.html');
}
	   
}

function ShowWWWStat ($UserName, $month = 'current')
{
	GLOBAL $config, $l_tables, $navbar;
	
	if (empty($month) OR $month == 'current')
	{ 
	  	$days = date(j);
	  	$month = date(F);
	  	$year = date(Y);
	}	
    if ($month == 'last')
    { 
       	$n=date("j");
		$n=intval($n);
		$n=time()-$n*86400+1;

		$days=date("j",$n);
       	$month=date("F",$n);
       	$year=date("Y",$n);
    }
    if ($month == 'before_last')
    {
        $time=time();
        $n=date("j");
		$n=intval($n);
		$n=$time-$n*86400+1;
        $days=date("j",$n);
        $n=$n-$days*86400;

        $days=date("j",$n);
       	$month=date("F",$n);
       	$year=date("Y",$n);
    }
    
    $script_parts = explode("/",$_SERVER['PHP_SELF']);
    $script_name = $script_parts[sizeof($script_parts)-1];   

    //общая статистика по дням
    if (empty($_GET['day']))
    {
    include ('templates/' . $config['template'] . '/hosts_main_header.html');
    
    for ($i=1; $i <= $days; $i++)
    {
    	$host_bytes = 0;
    	$host_connects = 0;
    	$rep_path = $config['reports_path'].date("Ymd",strtotime("$i $month $year"));
    	$cur_date = $i." ".$month." ".$year;
    	
    	@ $file = fopen($rep_path.'/.total',"r");
    	
    	if ( $file <> false )
    	{
    		while ($data = fgets ($file, 1000)) 
    		{
  				//удаляем все лишние пробелы
    			$data = preg_replace("/(\s+)/", " ",$data);
  				
    			$data_parts = explode(" ",$data);
    			
				if ($data_parts[0] == $UserName) 
				{
					$host_bytes = $data_parts[1];
					$host_connects = $data_parts[2];
					
					$host_bytes = $this->bytes_format($host_bytes);
					break;
				}
  			}
    	}
		
    	//echo $rep_path."<br>";
    	include ('templates/' . $config['template'] . '/hosts_main_body_usr.html');
    }
	
    include ('templates/' . $config['template'] . '/table_footer.html');
    }
    //********************************
    else if (preg_match("/^[0-9]{1,2}$/" ,$_GET['day']))
    {
    	$day = $_GET['day'];
    	$rep_path = $config['reports_path'].date("Ymd",strtotime("$day $month $year"));
    	$cur_date = $_GET['day']." ".$month." ".$year;

    	//Навигация по дням
    	if ( ( $_GET['day'] == '1' ) AND ($_GET['day'] != $days) )
    	{
    		$back_day = 1;
    		$forward_day = $_GET['day'] + 1; 
    	}
    	else if ( ( $_GET['day'] == '1' ) AND ($_GET['day'] == $days) )
    	{
    		$back_day = 1;
    		$forward_day = 1; 
    	}
    	else if ( ( $_GET['day'] != '1' ) AND ($_GET['day'] == $days) )
    	{
    		$back_day = $_GET['day'] - 1;
    		$forward_day = $_GET['day']; 
    	}
    	else
    	{
    		$back_day = $_GET['day'] - 1;
    		$forward_day = $_GET['day'] + 1;    			    			
    	}
    	
    	//Детальная статистика по сайтам
    	if ($_GET['subaction'] == 'main')
    	{
    		//Составляем массив сайтов и запоминаем общий объем трафика
    		@ $file = fopen($rep_path.'/'.$UserName,"r");
    		if ($file <> false) 
    		{
    			$cnt = 0;
    			
    			while ($data = fgets ($file, 3000)) 
    			{
    				$data = preg_replace("/(\s+)/", " ",$data);
   					$data_parts = explode(" ",$data);
     				
   					if ( $data_parts['0'] <> 'total:' ) 
   					{
     					$urls[$cnt]['url'] 	  	= $data_parts['0'];
     					$urls[$cnt]['bytes'] 	= $this->bytes_format($data_parts['1']);
     					$urls[$cnt]['connects'] = $data_parts['2'];
     					$cnt++;
   					}
   					if ( $data_parts['0'] == 'total:' )
   					{
   						$total = $data_parts['1'];
   						$total = $this->bytes_format($total);
   					}
    			}
    		}
    		
    		include ('templates/' . $config['template'] . '/sites_report_navigation.html');
    		include ('templates/' . $config['template'] . '/sites_main_report_header.html');
    		$cnt = 0;
    		
    		while(each($urls)) 
    		{
    			include ('templates/' . $config['template'] . '/sites_main_report_body.html');
    			$cnt++;
    		}
    		
			include ('templates/' . $config['template'] . '/table_footer.html');
    	}
    	//**************************
    	
    	//Отчет по скачанным большим файлам
    	if ($_GET['subaction'] == 'bigfiles') 
    	{
    		@ $file = fopen($rep_path.'/.bigfiles',"r");
    		
    		if ($file <> false)
    		{
    			$cnt = 0;
    			
    			while ($data = fgets ($file, 3000)) 
    			{
    				$data = preg_replace("/(\s+)/", " ",$data);
   					$data_parts = explode(" ",$data);
     				
   					if ( $data_parts['0'] == $UserName ) 
   					{
     					$bfiles[$cnt]['url'] 	  	= $data_parts['3'];
     					$bfiles[$cnt]['bytes'] 	= $this->bytes_format($data_parts['2']);
     					$bfiles[$cnt]['time'] = $data_parts['1'];
     					$cnt++;
   					}
    			}
    		}
    		
    		include ('templates/' . $config['template'] . '/sites_report_navigation.html');
    		include ('templates/' . $config['template'] . '/sites_bigfiles_header.html');
    		$cnt = 0;
    		
    		while(each($bfiles))
    		{
    			include ('templates/' . $config['template'] . '/sites_bigfiles_body.html');
    			$cnt++; 	
    		}
    		
    		include ('templates/' . $config['template'] . '/table_footer.html');
    	}
    	//*********************************************************
    	
    	if ($_GET['subaction'] == 'time')
    	{
    		include ('templates/' . $config['template'] . '/sites_report_navigation.html');
    		include ('templates/' . $config['template'] . '/sites_time_header.html');
    		
    	}
    	
    }    
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


function bytes_format($bytes,$mode='main') {include "config.inc.php";
if ($mode == 'main') {	if ($bytes <  $config['mb'] ) $bytes = $bytes.' B';
	if ( ($bytes >= $config['mb'] )     	  AND ($bytes < $config['mb']*$config['mb']) ) 	   	   $bytes = number_format($bytes/($config['mb']), $config['precision'], '.', ' ').' KB';
	if ( ($bytes >= $config['mb']*$config['mb'] ) 	  AND ($bytes < $config['mb']*$config['mb']*$config['mb']) ) 	   $bytes = number_format($bytes/($config['mb']*$config['mb']), $config['precision'], '.', ' ').' MB';
	if ( ($bytes >= $config['mb']*$config['mb']*$config['mb'] ) AND ($bytes < $config['mb']*$config['mb']*$config['mb']*$config['mb']) ) $bytes = number_format($bytes/($config['mb']*$config['mb']*$config['mb']), $config['precision'], '.', ' ').' GB';

}

if ($mode == 'time') {
	if ($bytes < 100*$config['mb']*$config['mb']) 	   	   $bytes = number_format($bytes/($config['mb']*$config['mb']), 1, '.', ' ').'M';
	if ($bytes >= 100*$config['mb']*$config['mb'] ) 	   $bytes = number_format($bytes/($config['mb']*$config['mb']*$config['mb']), 1, '.', ' ').'G';
}


return $bytes;
}

}
?>
