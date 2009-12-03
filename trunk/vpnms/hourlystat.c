/*
 * hourlystat.c
 *
 *  Created on: 29.10.2009
 *      Author: BURUT\admin
 */

#include "vpnms.h"

int main ()
{
	char				*query;
	long unsigned int	StartTime;
	long unsigned int	EndTime;
	MYSQL_RES			*res;
	MYSQL_ROW			row;
	long unsigned int	rows = 0;
	char				*username;
	time_t				t;
	struct tm			*gm;
	myint				HTTPin, HTTPout, HTTPSin, HTTPSout, SSHin, SSHout, ICQin, ICQout, SMTPin, SMTPout,
	SSMTPin, SSMTPout, POP3in, POP3out, POP3Sin, POP3Sout, IMAPin, IMAPout, IMAPSin, IMAPSout, DNSin, DNSout,
	OTHERin, OTHERout, ALLin, ALLout;

	ALLin = 0;
	ALLout = 0;

	//вычисляем диапазон времени, который обрабатывать
	EndTime = (unsigned long)time(NULL);
	StartTime = EndTime - 3600;

	//запоминаем дату и час
	t = time(NULL);
	gm = gmtime(&t);

	//loading config
    vpnms_config = LoadConfig();

    //while(1)
    //{
        //выбираем первую попавшуюся запись из нужного диапазона и смотрим имя владельца
    	query = malloc(256);
    	sprintf(query, "SELECT `flows`.`Owner` FROM `flows` WHERE `TimeStamp` > %lu AND `TimeStamp` < %lu", StartTime, EndTime);
    	res = exec_query(query);
    	rows = mysql_num_rows(res);
    	if (rows < 1)
    		exit(EXIT_SUCCESS);
    	row = mysql_fetch_row(res);
    	username = strdup(row[0]);
    	mysql_free_result(res);

    	//printf("u: %s, d: %u, h: %u \n", username, gm.tm_mday, gm.tm_hour);

    	//HTTP
    	query = malloc(512);
    	sprintf(query, "SELECT SUM(Bytes) AS Bytes FROM `flows` WHERE `TimeStamp` > %lu AND `TimeStamp` < %lu AND `Owner` = '%s' AND `SrcPort` = 80",
    			StartTime, EndTime, username);
    	res = exec_query(query);
    	rows = mysql_num_rows(res);
    	if (rows < 1)
    		exit(EXIT_SUCCESS);
    	row = mysql_fetch_row(res);
    	HTTPin = atoll(row[0]);
    	ALLin = ALLin + HTTPin;
    	mysql_free_result(res);


    	//чистим память
    	free(username);
    //}

	return 0;
}
