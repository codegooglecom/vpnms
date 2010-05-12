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

#include "vpnms.h"

void SigHandler()
{
	if (waiting_mutex == 1)
		StopDaemon();
	else
	{
		extern short received_kill_sig;
		received_kill_sig = 1;
	}
}

void * vpnmsd_nf_thread(void * arg);

#define NF_BUFLEN 2048

LLIST *nf_list_add(LLIST **p, char *ip, myint input, myint output, myint local_input, myint local_output)
{
	LLIST *n = (LLIST *) malloc(sizeof(LLIST));
	if (n == NULL)
		return NULL;

	n->next = *p;
	*p = n;
	n->input = input;
	n->output = output;
	n->local_input = local_input;
	n->local_output = local_output;
	n->ip = malloc (strlen(ip)+1);
	strcpy(n->ip,ip);

	return *p;
}

LLIST *nf_list_search(LLIST **n, char *ip)
{
	while (*n != NULL)
	{
		if (strcasecmp ( (*n)->ip, ip ) == 0)
		{
			return *n;
		}
		n = &(*n)->next;
	}
	return NULL;
}

void nf_list_destroy(LLIST **n)
{
	LLIST *p = *n;

	while ( p )
	{
		LLIST *to_delete = p;
		p = p->next;
		free( to_delete->ip );
		free( to_delete );
	}

	*n = NULL;
}

int ShowConfig()
{
	vpnms_config = LoadConfig();
	printf("\n[mysql]\nhost = %s\nusername = %s\npassword = %s\ndatabase = %s\nport = %u\n\n[vpnms]\nclose_console = %s\ndaemon_interval = %u\n"
			"network = %s\nnetmask = %s\naltq = %s\ntransparent_proxy = %s\ntransparent_proxy_port = %u\nhourly_stat = %s\nsql_debug = %s\n"
			"cmd_debug = %s\npf_file_debug = %s\ndisconnect_on_crash = %s\n\n[vars]\npfctl = %s\necho = %s\nond = %s\nmpd_rc_script = %s\n\n",
			vpnms_config.mysql_host, vpnms_config.mysql_username, vpnms_config.mysql_password, vpnms_config.mysql_database, vpnms_config.mysql_port,
			vpnms_config.vpnms_close_console, vpnms_config.vpnms_daemon_interval, vpnms_config.vpnms_network, vpnms_config.vpnms_netmask, vpnms_config.vpnms_altq,
			vpnms_config.vpnms_transparent_proxy, vpnms_config.vpnms_transparent_proxy_port, vpnms_config.vpnms_hourly_stat, vpnms_config.vpnms_sql_debug,
			vpnms_config.vpnms_cmd_debug, vpnms_config.vpnms_pf_file_debug, vpnms_config.vpnms_disconnect_on_crash, vpnms_config.vars_pfctl, vpnms_config.vars_echo, vpnms_config.vars_ond, vpnms_config.vars_mpd_rc_script);

	return 0;
}

LLIST	*cur = NULL;
LLIST	*last = NULL;

//struct s_vpnms_config	vpnms_config;
struct s_subnets		*subnets;
size_t					num_subnets = 0;
pthread_mutex_t			mutex = PTHREAD_MUTEX_INITIALIZER;
int						nf_thread_initialized = 0;
int						waiting_mutex = 0;
MYSQL 					mysql;

int main(int argc, char **argv)
{
        static int 			opt, pidfd;
        pid_t 					pid, sid;
        char 					chpid[6];
        char 					rpid[6];
        struct sigaction		act;
    	MYSQL_RES 				*res;
    	MYSQL_ROW 				row;
    	size_t					rows;
    	char 					*query;
    	pthread_t				nf_thread;
    	LLIST					*cur_copy = NULL;
    	LLIST					*last_copy = NULL;
    	struct s_balance		balance;
    	myint					SessId;
    	unsigned long int		SpeedIn;
    	unsigned long int		SpeedOut;
    	unsigned long int		TimePassed;
    	char					*cmd;
    	char					*pUsername;
    	char					*pStatus;

        char help_str[] =
        		"Usage: vpnmsd [OPTION]\n"
        		"without options start the daemon.\n"
        		"-s, --stop	to stop the daemon.\n"
        		"-v, --version	to show version.\n"
        		"-c, --showconfig to show config.\n"
        		"-h, --help	to show help.\n";

        const struct option opts[] = {
        		{"stop", no_argument, NULL, 's'},
        		{"version", no_argument, NULL, 'v'},
        		{"showconfig", no_argument, NULL, 'c'},
        		{"help", no_argument, NULL, 'h'},
        		{NULL, 0, NULL, 0}
        };

        while ((opt = getopt_long(argc, argv, "svch:", opts, NULL)) != -1) {
                switch (opt) {
                case 'h':
						printf("%s", help_str);
						exit(EXIT_SUCCESS);

                case 'v':
						printf("VPNMS version %s revision %s\nAuthor: Andrey Chebotarev\nMail: admin@vpnms.org\nVPNMS 2005-2010\n", VERSION, REVISION);
						exit(EXIT_SUCCESS);

                case 's':
						pidfd = open(PIDFILE, O_RDONLY);
						if (pidfd == -1)
				        {
				        	syslog (LOG_ERR, " error in open %s, daemon not started?", PIDFILE);
				        	exit(EXIT_FAILURE);
				        }
						if ( read(pidfd, &rpid, 5) < 1 )
						{
				        	syslog (LOG_ERR, " error in read %s", PIDFILE);
				        	exit(EXIT_FAILURE);
						}
						if ( kill (atoi(rpid), SIGTERM) == -1 )
						{
				        	syslog (LOG_ERR, " cannot send termination signal to vpnmsd");
				        	exit(EXIT_FAILURE);
						}
						exit(EXIT_SUCCESS);

                case 'c':
						ShowConfig();
						exit(EXIT_SUCCESS);

//                default:
//						break;
                }
        }

//        if (argc < 2)
//        	printf("%s", help_str);

        //check vpnmsd
        if ( check_daemon() == 1)
        {
			syslog (LOG_ERR, " daemon already running");
            exit(EXIT_FAILURE);
        }

        //Demonize vpnmsd
        pid = fork();

        if (pid < 0) {
        	syslog (LOG_ERR, " error in fork()");
            exit(EXIT_FAILURE);
        } else if (pid > 0) {
            exit(EXIT_SUCCESS);
        }

        umask(0);

        sid = setsid();

        if (sid < 0) {
        	syslog (LOG_ERR, " error in setsid()");
            exit(EXIT_FAILURE);
        }

        if ((chdir("/")) < 0) {
			syslog (LOG_ERR, " error in chdir()");
            exit(EXIT_FAILURE);
        }

        //signals
        sigemptyset(&act.sa_mask);
        act.sa_handler = &SigHandler;
        act.sa_flags = 0;

        if ( sigaction (SIGTERM, &act, NULL) == -1 )
        {
        	syslog (LOG_ERR, " sigaction error");
        	exit(EXIT_FAILURE);
        }

        //loading config
        vpnms_config = LoadConfig();

        //closing stdin, stdout and stderr
        if ( strcasecmp (vpnms_config.vpnms_close_console, "yes") == 0 )
        {
        	close(0);
            close(1);
            close(2);
        }

        //reading local subnets...
        query = malloc(256);
        sprintf(query, "SELECT Subnet_Address, NetMask FROM `subnets`");

        res = exec_query(query);
        rows = mysql_num_rows(res);
        subnets = calloc(rows + 1, sizeof(struct s_subnets));

        while ((row = mysql_fetch_row(res)))
        {
        	num_subnets++;
        	subnets[num_subnets].address = inet_addr(row[0]);
        	subnets[num_subnets].mask = inet_addr(row[1]);
        }
        mysql_free_result(res);

        //creating netflow thread...
        if (pthread_create(&nf_thread, NULL, &vpnmsd_nf_thread, NULL) != 0)
        {
    		syslog (LOG_ERR, "Erron in create nf_thread\n");
    		exit(EXIT_FAILURE);
        }

        //writing pid
        pidfd = open (PIDFILE, O_WRONLY | O_CREAT, 0640);
        if (pidfd == -1)
        {
        	syslog (LOG_ERR, " error in open %s", PIDFILE);
        	exit(EXIT_FAILURE);
        }

        if ( lockf(pidfd, F_TLOCK, 0) < 0)
        {
        	syslog (LOG_ERR, " error in lock %s", PIDFILE);
        	exit(EXIT_FAILURE);
        }

        ftruncate(pidfd, 0);
        sprintf( chpid, "%d", sid );
        if ( write(pidfd, chpid, strlen(chpid) ) == -1 )
        {
        	syslog (LOG_ERR, " error in writing to %s", PIDFILE);
        	exit(EXIT_FAILURE);
        }

        //vpnmsd demonized and configured, writing to log
        syslog (LOG_NOTICE, " started");

        //waiting nf_thread...
        //while(nf_thread_initialized == 0)
        	//sleep(1);

       /***************************************************************************************************************************************************
        starting work...
        ***************************************************************************************************************************************************/

        //daemon cycle...
    	while (1)
        {
    		sleep(vpnms_config.vpnms_daemon_interval);

        	waiting_mutex = 1;
			pthread_mutex_lock(&mutex);
       		cur_copy = last;
            last_copy = last;
            cur = NULL;
            last = NULL;
            pthread_mutex_unlock(&mutex);
            waiting_mutex = 0;

  	        //обновляем время сессии
  	        query = malloc(128);
  	        sprintf(query, "UPDATE sessions SET  "
							"`SessionTime` = UNIX_TIMESTAMP( ) - sessions.StartTime, "
							"`StopTime` = UNIX_TIMESTAMP( ) "
							"WHERE Connected = 1");
			exec_query_write(query);

            //обнуляем скорость
  			query = malloc(256);
  	        sprintf(query, "UPDATE `sessions` SET `Speed_in` = 0, `Speed_out` = 0 WHERE `Connected` = 1");
  	        exec_query_write(query);

          	while (cur_copy != NULL)
           	{
          		char *fcmd;
          		fcmd = malloc(2048);
          		sprintf(fcmd, "echo \"%s %lld %lld\" >> /tmp/flows_summ", cur_copy->ip, cur_copy->input, cur_copy->output);
          		exec_cmd(fcmd);

          		// Смотрим последнюю сессию, если ее еще нет - значит еще не успел создасться. Тогда пропускаем этого пользователя
                // т.к. без добавления правил в цепочку VPNMS все равно трафик не пойдет.
          		pUsername = username_by_ip(cur_copy->ip);
      	        SessId = get_sess_id(pUsername);
      	        free(pUsername);

          		if (SessId != -1)
          		{
          	        // Высчитываем среднюю входящуюю и исходящую скорость
          	        SpeedIn = (cur_copy->input + cur_copy->local_input)/vpnms_config.vpnms_daemon_interval;
          	        SpeedOut = (cur_copy->output + cur_copy->local_output)/vpnms_config.vpnms_daemon_interval;

          	        // Записываем в сессию данные о трафике, времени сессии, скорость
          			query = malloc(1024);
          	        sprintf(query,
          	        		"UPDATE `sessions` SET "
          	        		"`InternetIn` = InternetIn + %llu, "
          	        		"`InternetOut` = InternetOut + %llu, "
          	        		"`LocalIn` = LocalIn + %llu,"
          	        		"`LocalOut` = LocalOut + %llu, "
          	        		"`Speed_in` = %lu, "
          	        		"`Speed_out` = %lu "
          	        		"WHERE `SessId` = %llu LIMIT 1",
          	        		cur_copy->input, cur_copy->output, cur_copy->local_input, cur_copy->local_output,
          	        		SpeedIn, SpeedOut, SessId);
          	        exec_query_write(query);

          	        // Проверяем баланс пользователя, если все прокачал - отключаем
          	        pUsername = username_by_ip(cur_copy->ip);
          	        pStatus = check_status(pUsername);
          	        balance = check_balance(pUsername);

          	        if ( strcasecmp(pStatus, STATUS_WORKING) == 0)
          	        	if (strcasecmp(balance.limit_type, LIMIT_TYPE_LIMITED) == 0 )
          	        		if ( (balance.input >= balance.limit) || (balance.output >= balance.out_limit) )
          	        		{
          	        			syslog (LOG_DEBUG, " limit expired: %s", pUsername);
          	        			cmd = malloc(256);
          	        			sprintf(cmd, "%s %s", vpnms_config.vars_ond, pUsername);
          	        			exec_cmd(cmd);
          	        		}

					free(balance.limit_type);
					free(pUsername);
					free(pStatus);
          		}
          		cur_copy = cur_copy->next;
           	}

         	//освобождаем память
          	nf_list_destroy (&last_copy);
           	cur_copy = NULL;
           	last_copy = NULL;

          	if ( received_kill_sig == 1 ) StopDaemon();
        }

        //*************************************************************************************************************************************************

    	exit(EXIT_SUCCESS);
}

