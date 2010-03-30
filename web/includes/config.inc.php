<?
/*
 VPNMS Project (c) 2005-2010 Andrey Chebotarev aka Metallic
 email: admin@vpnms.org

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.
*/

/*
:::     ::: :::::::::  ::::    ::: ::::    ::::   ::::::::
:+:     :+: :+:    :+: :+:+:   :+: +:+:+: :+:+:+ :+:    :+:
+:+     +:+ +:+    +:+ :+:+:+  +:+ +:+ +:+:+ +:+ +:+
+#+     +:+ +#++:++#+  +#+ +:+ +#+ +#+  +:+  +#+ +#++:++#++
 +#+   +#+  +#+        +#+  +#+#+# +#+       +#+        +#+
  #+#+#+#   #+#        #+#   #+#+# #+#       #+# #+#    #+#
    ###     ###        ###    #### ###       ###  ########
*/

if (!defined('IN_VPNMS'))
	die("");

//---------------------------------------------------------
//                DB configuration
//---------------------------------------------------------
$config['db_name'] = "radius";
$config['db_host'] = "10.0.15.5";
$config['db_user'] = "root";
$config['db_passwd'] = "";

//---------------------------------------------------------
//                 Path
//---------------------------------------------------------
$config['reports_path'] = "/usr/local/vpnms/share/http/reports/";

//---------------------------------------------------------
//                 VPNMS config
//---------------------------------------------------------
$config['template'] = "default";
$config['lang'] = "ru";
$config['redirection_time'] = "20";
$config['mb'] = 1024;
$config['precision'] = 2;
$config['time_correction'] = 0;
$config['session_ttl'] = 5 * 60;
$config['unix_time'] = mktime();
//mpd
$config['mpd_url'] = "http://10.0.15.5:5006";
$config['mpd_user'] = "admin";
$config['mpd_pass'] = "pass";

?>

