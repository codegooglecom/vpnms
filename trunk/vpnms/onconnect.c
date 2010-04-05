/*
 *   VPN Management System
 *   Copyright (C) 2005-2010  Andrey Chebotarev <admin@vpnms.org>
 *   All rights reserved.
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/*
 *  Command line arguments:
 *  argv[1] - interface name
 *  argv[2] - remote virtual ip
 *  argv[3] - username
 */

#include "vpnms.h"

int main (int argc, char **argv)
{
	char 		*if_name;
	char 		*v_ip;
	char 		*username;
	char 		*query;
	MYSQL_RES	*res;
	int			rows = 0;

	//если демон не запущен - выйти
	if ( check_daemon() == 0)
	{
		syslog (LOG_ERR, " daemon not started");
        exit(EXIT_FAILURE);
	}

	//если переданы не все параметры - выйти
	if (argc < 4)
	{
		syslog (LOG_ERR, " missing argument");
        exit(EXIT_FAILURE);
	}

	if_name = argv[1];
	v_ip = argv[2];
	username = argv[3];

    //loading config
    vpnms_config = LoadConfig();

	//закрываем незакрытые сессии, если вдруг такие образовались
	query = malloc(256);
	sprintf(query, "SELECT sessions.UserName FROM `sessions` WHERE `UserName` = '%s' AND `Connected` = '1'", username);
	res = exec_query(query);
	rows = mysql_num_rows(res);
	mysql_free_result(res);

	if (rows >= 1)
	{
		query = malloc(256);
		sprintf(query, "UPDATE `sessions` SET `Connected` = '0' WHERE `UserName` = '%s'", username);
		exec_query(query);
	}

	//чистим правила, если вдруг остались старые
	clear_rules(username);

	//открываем сессию
	query = malloc(512);
	sprintf(query, "INSERT INTO `sessions` ( `SessId` , `UserName` , `StartTime` , `StopTime` , `SessionTime`,`InternetIn` , `InternetOut` , "
			"`LocalIn` , `LocalOut` , `FramedIpAddress` , `Interface`, `Connected`, `Rotation`)"
			" VALUES ('', '%s', '%ld', '0', '0', '0', '0', '0', '0', '%s', '%s', '1', '1')"
			, username, (unsigned long)time(NULL), v_ip, if_name);
	exec_query(query);

	//добавляем правила в зависимости от статуса
	add_rules(username, if_name);

	exit(EXIT_SUCCESS);
}
