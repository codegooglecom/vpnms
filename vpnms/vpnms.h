/*
 * vpnms.h
 *
 *  Created on: 31.08.2009
 *      Author: Andrey Chebotarev
 *        mail:	admin@vpnms.org
 */

#ifndef VPNMS_H_
#define VPNMS_H_

#include <getopt.h>
#include <stdio.h>
#include <fcntl.h>
#include <stdlib.h>
#include <syslog.h>
#include <unistd.h>
#include <string.h>
#include <arpa/inet.h>
#include <netinet/in.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <sys/stat.h>
#include <sys/shm.h>
#include <signal.h>
#include <pthread.h>
#include <time.h>
#include "/usr/local/include/mysql/mysql.h"
#include "config.h"
#include "vpnms_functions.h"
#include <assert.h>

#define VERSION "1.0.0 "
#define REVISION "22"
#define PIDFILE "/var/run/vpnmsd.pid"
#define CONFIGFILE "/usr/local/etc/vpnms.conf"

//PF config
#define PF_VPNMS_ANCHOR "vpnms"
#define PF_VPNMSP_ANCHOR "vpnmsp"

//status config
#define STATUS_BLOKED "blocked"
#define STATUS_WORKING "working"
#define STATUS_LIMIT_EXPIRE "limit_expire"
#define STATUS_LOCAL_ONLY "local_only"

#define LIMIT_TYPE_LIMITED "limited"
#define LIMIT_TYPE_UNLIMITED "unlimited"

extern short received_kill_sig;

struct s_vpnms_config
{
	char	*mysql_host;
	char	*mysql_username;
	char	*mysql_password;
	char	*mysql_database;
	int		mysql_port;

	char	*vpnms_close_console;
	int		vpnms_daemon_interval;
	char	*vpnms_network;
	char	*vpnms_netmask;
	char	*vpnms_altq;
	char	*vpnms_transparent_proxy;
	char	*vpnms_sql_debug;
	char	*vpnms_cmd_debug;
	char	*vpnms_pf_file_debug;
	char	*vpnms_hourly_stat;
	int		vpnms_time_correction;
	unsigned int vpnms_transparent_proxy_port;

	char	*vars_pfctl;
	char	*vars_echo;
	char	*vars_ond;
};

#define NF_BUFLEN 2048
#define NF_PORT 9999

typedef unsigned long long int myint;

typedef struct node {
	char *ip;
	myint input;
	myint output;
	myint local_input;
	myint local_output;
	struct node *next;
} LLIST;

struct s_balance {
	char	*limit_type;
	myint	limit;
	myint	out_limit;
	myint	input;
	myint	output;
	myint	local_input;
	myint	local_output;
};

LLIST *nf_list_add(LLIST **p, char *ip, myint input, myint output, myint local_input, myint local_output);
LLIST *nf_list_search(LLIST **n, char *ip);
void nf_list_destroy(LLIST **n);

extern LLIST	*cur;
extern LLIST	*last;

extern struct s_vpnms_config	vpnms_config;
struct s_vpnms_config	vpnms_config;

struct s_subnets {
	in_addr_t address;
	in_addr_t mask;
};

extern struct s_subnets *subnets;
extern size_t num_subnets;
extern pthread_mutex_t	mutex;
extern int nf_thread_initialized;
extern int waiting_mutex;
//extern MYSQL mysql;
unsigned long int timestamp;

int is_it_local (char *ip);
int is_it_vpn (char *ip, char *mask, char *net);

#endif /* VPNMS_H_ */
