CC=gcc
#CCFLAGS=-Wall

vpnmsd: monthlyreset.o hourlystat.o vpnmsd.o vpnmsd_nf_thread.o config.o vpnms_functions.o onconnect.o ondisconnect.o

onconnect: config.o vpnms_functions.o onconnect.o
	${CC} ${CCFLAGS} -c $< -lmysqlclient -lz -lpthread

vpnmsd.o: vpnmsd.c
	${CC} ${CCFLAGS} -c $<

vpnmsd_nf_thread.o: vpnmsd_nf_thread.c
	${CC} ${CCFLAGS} -c $<

config.o: config.c
	${CC} ${CCFLAGS} -c $<

vpnms_functions.o: vpnms_functions.c
	${CC} ${CCFLAGS} -c $<

onconnect.o: onconnect.c
	${CC} ${CCFLAGS} -c $<

ondisconnect.o: ondisconnect.c
	${CC} ${CCFLAGS} -c $<

monthlyreset.o: monthlyreset.c
	${CC} ${CCFLAGS} -c $<

hourlystat.o: hourlystat.c
	${CC} ${CCFLAGS} -c $<

clean:
	rm -f *.o
	rm -f vpnmsd
