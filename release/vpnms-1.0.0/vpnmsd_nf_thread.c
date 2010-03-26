/*
 * vpnmsd_nf_thread.c
 *
 *  Created on: 16.09.2009
 *      Author: BURUT\admin
 */

#include "vpnms.h"
#include "netflow.h"

int is_it_local (char *ip)
{
    int sbnts;
    for (sbnts = 1; sbnts <= num_subnets; sbnts++)
    	if ( (inet_addr(ip) & subnets[sbnts].mask) == subnets[sbnts].address )
    		return 1;

    return 0;
}

int is_it_vpn (char *ip, char *mask, char *net)
{
	if ( (inet_addr(ip) & inet_addr(mask)) == inet_addr(net) )
		return 1;

	return 0;
}

void * vpnmsd_nf_thread(void * arg)
{
    struct sockaddr_in si_me;
    struct in_addr ip;
    struct netflow_v5_export_dgram * pData;
    int s, i, cnt;
    char buf[NF_BUFLEN];
    char src_ip[17], dst_ip[17];
    char *query;
    int local_flow;

    if ((s=socket(AF_INET, SOCK_DGRAM, IPPROTO_UDP))==-1)
    {
        syslog (LOG_ERR, " faild to create socket");
        StopDaemon();
    }

    memset((char *) &si_me, 0, sizeof(si_me));

    si_me.sin_family = AF_INET;
    si_me.sin_port = htons(NF_PORT);
    si_me.sin_addr.s_addr = htonl(INADDR_ANY);

    if (bind(s, &si_me, sizeof(si_me))==-1)
    {
        syslog (LOG_ERR, " faild to bind socket");
        StopDaemon();
    }

    nf_thread_initialized = 1;

    while (1)
    {
        if ( recvfrom(s, buf, NF_BUFLEN, 0, 0, 0) == -1 )
        {
            syslog (LOG_ERR, " unable to obtain data");
            StopDaemon();
        }
        pData = &buf;

        cnt = ntohs (pData->header.count);

        pthread_mutex_lock(&mutex);

        for (i=0; i < cnt; i++)
        {
            ip.s_addr  = pData->r[i].src_addr;
            strcpy( src_ip, inet_ntoa( ip ) );
            ip.s_addr  = pData->r[i].dst_addr;
            strcpy( dst_ip, inet_ntoa( ip ) );

            //printf("№%d %s:%d -> %s:%d  %d bytes\n", i, src_ip, ntohs (pData->r[i].s_port ), dst_ip,  ntohs (pData->r[i].d_port ), ntohl (pData->r[i].octets ));

            if ( (is_it_vpn(src_ip, vpnms_config.vpnms_netmask, vpnms_config.vpnms_network) == 1) && (is_it_vpn(dst_ip, vpnms_config.vpnms_netmask, vpnms_config.vpnms_network) == 1) )
            {
            	//ничего не делаем, источник и пункт назначения vpn
            }
            else
            {
            	//исходящий трафик
            	if ( is_it_vpn(src_ip, vpnms_config.vpnms_netmask, vpnms_config.vpnms_network) == 1 )
            	{
                    //Добавляем поток в базу, для ежечасного парсинга
                    if ( 0 == strcasecmp(vpnms_config.vpnms_hourly_stat, "yes") )
                    {
                    	local_flow = 0;
                    	if(is_it_local(dst_ip)) local_flow = 1;

                        query = malloc(512);

                        sprintf(query,
              	        		"INSERT INTO `flows` ("
              	        		"`TimeStamp` ,"
              	        		"`Owner` ,"
              	        		"`SrcIp` ,"
              	        		"`SrcPort` ,"
              	        		"`DstIp` ,"
              	        		"`DstPort`, "
              	        		"`Bytes`, "
                        		"`Local`"
              	        		") "
              	        		"VALUES ("
              	        		" %lu, '%s', '%s', %u, '%s', %u, %u, %u"
              	        		")",
              	        		(unsigned long)time(NULL), username_by_ip(src_ip), src_ip, ntohs (pData->r[i].s_port), dst_ip,
              	        		ntohs (pData->r[i].d_port), ntohl (pData->r[i].octets ), local_flow);
              	        exec_query(query);
                    }
          	        //----

            		cur = last;
					//если уже есть такой IP в списке
					if ( nf_list_search(&cur, src_ip) != NULL )
					{
						//если пункт назначения локальный адрес
						if (is_it_local(dst_ip) == 1)
							cur->local_output = cur->local_output + ntohl (pData->r[i].octets );
						else
							cur->output = cur->output + ntohl (pData->r[i].octets );
					}
					else
					//если нет - добавляем в список
					{
						//если пункт назначения локальный адрес
						if (is_it_local(dst_ip) == 1)
							last = nf_list_add(&cur, src_ip, 0, 0, 0, ntohl (pData->r[i].octets ));
						else
							last = nf_list_add(&cur, src_ip, 0, ntohl (pData->r[i].octets ), 0, 0);
					}
            	}

            	//входящий трафик
            	if ( is_it_vpn(dst_ip, vpnms_config.vpnms_netmask, vpnms_config.vpnms_network) == 1 )
            	{
                    //Добавляем поток в базу, для ежечасного парсинга
                    if ( 0 == strcasecmp(vpnms_config.vpnms_hourly_stat, "yes") )
                    {
                    	local_flow = 0;
                    	if(is_it_local(src_ip)) local_flow = 1;

                    	query = malloc(512);
              	        sprintf(query,
              	        		"INSERT INTO `flows` ("
              	        		"`TimeStamp` ,"
              	        		"`Owner` ,"
              	        		"`SrcIp` ,"
              	        		"`SrcPort` ,"
              	        		"`DstIp` ,"
              	        		"`DstPort`, "
              	        		"`Bytes`, "
              	        		"`Local`"
              	        		") "
              	        		"VALUES ("
              	        		" %lu, '%s', '%s', %u, '%s', %u, %u, %u"
              	        		")",
              	        		(unsigned long)time(NULL), username_by_ip(dst_ip), src_ip, ntohs (pData->r[i].s_port), dst_ip,
              	        		ntohs (pData->r[i].d_port), ntohl (pData->r[i].octets ), local_flow);
              	        exec_query(query);
                    }
          	        //----

            		cur = last;
					//если уже есть такой IP в списке
					if ( nf_list_search(&cur, dst_ip) != NULL )
					{
						//если источник локальный адрес
						if (is_it_local(src_ip) == 1)
							cur->local_input = cur->local_input + ntohl (pData->r[i].octets );
						else
							cur->input = cur->input + ntohl (pData->r[i].octets );
					}
					else
					//если нет - добавляем в список
					{
						//если источник локальный адрес
						if (is_it_local(src_ip) == 1)
							last = nf_list_add(&cur, dst_ip, 0, 0, ntohl (pData->r[i].octets ), 0);
						else
							last = nf_list_add(&cur, dst_ip, ntohl (pData->r[i].octets ), 0, 0, 0);
					}

            	}
            }
        }
        pthread_mutex_unlock(&mutex);
        usleep(50);
    }
}
