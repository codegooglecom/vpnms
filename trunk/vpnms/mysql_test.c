#include "/usr/include/mysql/mysql.h"
#include <stdio.h>

int main()
{
	MYSQL_RES	*res;
	MYSQL 		mysql;
	MYSQL_ROW	row;
	char		*query;
	int			i;

	mysql_init(&mysql);

	if (!mysql_real_connect(&mysql, "10.0.15.5", "root", "",	"radius", 3306, NULL, 0))
	{
		printf ("Failed to connect to database: Error: %s\n", mysql_error(&mysql));
		exit(1);
	}

	query = malloc(512);
	sprintf(query, "SELECT * FROM `radcheck`");

    if ( mysql_real_query(&mysql, query, strlen(query)) )
    {
		printf ("Query failed: Error: %s\n", mysql_error(&mysql));
		exit(1);
	}

    free(query);

    res = mysql_store_result(&mysql);

    mysql_close(&mysql);

    while ((row = mysql_fetch_row(res)))
    {
    	printf("username: %s\n", row[1]);
    }

    mysql_free_result(res);

	return 0;
}
