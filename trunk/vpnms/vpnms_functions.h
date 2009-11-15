/*
 * vpnms_functions.h
 *
 *  Created on: 31.08.2009
 *      Author: BURUT\admin
 */

#ifndef VPNMS_FUNCTIONS_H_
#define VPNMS_FUNCTIONS_H_

void StopDaemon();
struct s_vpnms_config LoadConfig();
int check_daemon();
char *username_by_ip(char *ip);
char *check_status(char *username);
long long int get_sess_id(char *username);
struct s_balance check_balance(char *username);
MYSQL_RES *exec_query(char *query);
int exec_cmd(char *cmd);
int clear_rules(char *username);
char *ip_by_username(char *username);

#endif /* VPNMS_FUNCTIONS_H_ */
