vpnmsd: config.o vpnms_functions.o vpnmsd.o
	gcc -o vpnmsd config.o vpnms_functions.o vpnmsd_nf_thread.o vpnmsd.o -lmysqlclient -lz -lpthread

vpnms_functions.o: vpnms_functions.c
	gcc -c vpnms_functions.c
	
vpnmsd.o: vpnmsd.c
	gcc -c vpnmsd.c	

config.o: config.c config.h
	gcc -c config.c

vpnmsd_nf_thread.o: vpnmsd_nf_thread1.c
	gcc -c vpnmsd_nf_threadss.c

clean:
	rm -f *.o
	rm -f vpnmsd
