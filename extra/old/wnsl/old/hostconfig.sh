CRONPID=`ps | grep crond | awk '{print $1}'`
#echo ${CRONPID}
kill ${CRONPID}
echo "* * * * * /vmfs/volumes/datastore1/wnsl-tools/autoboot.sh /vmfs/volumes/datastore1/wnsl-tools/autoboot.conf" >> /var/spool/cron/crontabs/root
crond
