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

if (empty($_GET["action"])) 
	include ('templates/' . $config['template'] . '/auth_form.html');

if (!empty($_GET["action"])) 
{
	if ($_GET["action"] == "enter") 
	{
    	if ( $_POST["login"] && $_POST["auth_passwd"] ) 
    	{
        	if ( $sec->LogIn($_POST["login"], $_POST["auth_passwd"]) == true ) 
        	{
            	$content = $l_message['login'] . " <b>" . $_POST["login"] . "</b>";
                $page->message($content);
                $_SESSION['session_login'] = $_POST["login"];
                $page->redirect("index.php?module=Start",$config['redirection_time']);
            }
            if ($sec->LogIn($_POST["login"], $_POST["auth_passwd"]) == false) 
            {
            	$page->message($l_errors['err_user_pass']);
                $page->redirect("index.php?module=Login",$config['redirection_time']);
            }
        }
        else 
        {
        	$page->message($l_errors['no_user_pass']);
            $page->redirect("index.php?module=Login",$config['redirection_time']);
        }
        
    }
}

?>