/*
 * hourlystat.c
 *
 *  Created on: 29.10.2009
 *      Author: BURUT\admin
 */

#include "vpnms.h"

myint hourly_sum_bytes (char *port, char *mode, unsigned long int StartTime, unsigned long int EndTime, char *username)
{
	char				*query;
	char				*table;
	char				*colum;
	char				*port_dst;
	MYSQL_RES			*res;
	MYSQL_ROW			row;

	query = malloc(1024);

	if (0 == strcasecmp (mode, "input"))
	{
		table = "flows";
		colum = "DstIp";
		port_dst = "SrcPort";
	}
	if (0 == strcasecmp (mode, "output"))
	{
		table = "flows";
		colum = "SrcIp";
		port_dst = "DstPort";
	}
	if (0 == strcasecmp (mode, "local_input"))
	{
		table = "flows_local";
		colum = "DstIp";
		port_dst = "SrcPort";
	}
	if (0 == strcasecmp (mode, "local_output"))
	{
		table = "flows_local";
		colum = "SrcIp";
		port_dst = "DstPort";
	}

	if (0 == strcasecmp (port, "all"))
	{
		sprintf(query,
			"SELECT SUM(Bytes) AS Bytes FROM `%s` WHERE `TimeStamp` > %lu AND `TimeStamp` < %lu AND `Owner` = '%s' AND `%s` LIKE '10.%%.%%.%%'",
			table, StartTime, EndTime, username, colum
			);
	}
	else if (0 == strcasecmp (port, "other"))
	{
		sprintf(query,
			"SELECT SUM(Bytes) AS Bytes FROM `%s` WHERE `TimeStamp` > %lu AND `TimeStamp` < %lu AND `Owner` = '%s' AND `%s` LIKE '10.%%.%%.%%' "
			"AND %s != 80 AND %s != 443 AND %s != 22 AND %s != 5190 AND %s != 25 AND %s != 465 AND %s != 110 AND %s != 995 AND %s != 143 AND %s != 993 "
			"AND %s != 585 AND %s != 53",
			table, StartTime, EndTime, username, colum, port_dst, port_dst, port_dst, port_dst, port_dst, port_dst, port_dst, port_dst, port_dst, port_dst,
			port_dst, port_dst
			);
	}
	else
	{
		sprintf(query,
			"SELECT SUM(Bytes) AS Bytes FROM `%s` WHERE `TimeStamp` > %lu AND `TimeStamp` < %lu AND `Owner` = '%s' AND `%s` LIKE '10.%%.%%.%%' AND `%s` = %s ",
			table, StartTime, EndTime, username, colum, port_dst, port
			);
	}

	res = exec_query(query);
	row = mysql_fetch_row(res);

	int nRes = 0;

	if ( row[ 0 ] )
	{
		nRes = atoll( row[0] );
	}

	mysql_free_result(res);

	return nRes;
}

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
	unsigned int		hour;
	unsigned int		day;
	myint				HTTPin, HTTPout, HTTPSin, HTTPSout, SSHin, SSHout, ICQin, ICQout, SMTPin, SMTPout,
	SSMTPin, SSMTPout, POP3in, POP3out, POP3Sin, POP3Sout, IMAPin, IMAPout, IMAPSin, IMAPSout, IMAPSSLin, IMAPSSLout, DNSin, DNSout,
	OTHERin, OTHERout, ALLin, ALLout;

	//loading config
    vpnms_config = LoadConfig();

	ALLin = 0;
	ALLout = 0;

	//вычисляем диапазон времени, который обрабатывать
	EndTime = (unsigned long)time(NULL);
	StartTime = EndTime - 3600;

	//запоминаем дату и час (в базе время храниться в GMT, поэтому делаем поправку на наш часовой пояс)
	t = time(NULL);
	gm = gmtime(&t);
	day = gm->tm_mday;
	hour = gm->tm_hour + vpnms_config.vpnms_time_correction;

	//Генерим отчет по интернет-трафику
	//while(1)
    //{

		//выбираем первую попавшуюся запись из нужного диапазона и смотрим имя владельца
    	query = malloc(256);
    	sprintf(query, "SELECT `flows`.`Owner` FROM `flows` WHERE `TimeStamp` > %lu AND `TimeStamp` < %lu LIMIT 1", StartTime, EndTime);
    	res = exec_query(query);
    	rows = mysql_num_rows(res);

    	//если не осталось не обработанных записей - выходим
    	if (rows < 1)
    		exit(EXIT_SUCCESS);

    	row = mysql_fetch_row(res);
    	username = strdup(row[0]);
    	mysql_free_result(res);

    	HTTPin = hourly_sum_bytes("80", "input", StartTime, EndTime, username);
    	HTTPSin = hourly_sum_bytes("443", "input", StartTime, EndTime, username);
    	SSHin = hourly_sum_bytes("22", "input", StartTime, EndTime, username);
    	ICQin = hourly_sum_bytes("5190", "input", StartTime, EndTime, username);
    	SMTPin = hourly_sum_bytes("25", "input", StartTime, EndTime, username);
    	SSMTPin = hourly_sum_bytes("465", "input", StartTime, EndTime, username);
    	POP3in = hourly_sum_bytes("110", "input", StartTime, EndTime, username);
    	POP3Sin = hourly_sum_bytes("995", "input", StartTime, EndTime, username);
    	IMAPin = hourly_sum_bytes("143", "input", StartTime, EndTime, username);
      	IMAPSin = hourly_sum_bytes("993", "input", StartTime, EndTime, username);
    	IMAPSSLin = hourly_sum_bytes("585", "input", StartTime, EndTime, username);
    	DNSin = hourly_sum_bytes("53", "input", StartTime, EndTime, username);
    	OTHERin = hourly_sum_bytes("other", "input", StartTime, EndTime, username);
    	ALLin = hourly_sum_bytes("all", "input", StartTime, EndTime, username);

    	HTTPout = hourly_sum_bytes("80", "output", StartTime, EndTime, username);
    	HTTPSout = hourly_sum_bytes("443", "output", StartTime, EndTime, username);
    	SSHout = hourly_sum_bytes("22", "output", StartTime, EndTime, username);
    	ICQout = hourly_sum_bytes("5190", "output", StartTime, EndTime, username);
    	SMTPout = hourly_sum_bytes("25", "output", StartTime, EndTime, username);
    	SSMTPout = hourly_sum_bytes("465", "output", StartTime, EndTime, username);
    	POP3out = hourly_sum_bytes("110", "output", StartTime, EndTime, username);
    	POP3Sout = hourly_sum_bytes("995", "output", StartTime, EndTime, username);
    	IMAPout = hourly_sum_bytes("143", "output", StartTime, EndTime, username);
      	IMAPSout = hourly_sum_bytes("993", "output", StartTime, EndTime, username);
    	IMAPSSLout = hourly_sum_bytes("585", "output", StartTime, EndTime, username);
    	DNSout = hourly_sum_bytes("53", "output", StartTime, EndTime, username);
    	OTHERout = hourly_sum_bytes("other", "output", StartTime, EndTime, username);
    	ALLout = hourly_sum_bytes("all", "output", StartTime, EndTime, username);

    	//чистим память
    	free(username);

    //}

	return 0;
}
