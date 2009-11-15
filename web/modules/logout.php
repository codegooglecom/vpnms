<?
/*
 VPNMS Project (c) 2005-2008 Andrey Chebotarev aka Metallic
 email: metallicvrn@gmail.com

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.
*/

$_SESSION['session_login'] = "";

$page->message($l_message['logout']);
$page->redirect("index.php?module=Start",$config['redirection_time']);
?>
