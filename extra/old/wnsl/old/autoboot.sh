#autoboot requires a linefeed-separated list of machines to monitor.
if [ -z $1 ]; then
  echo "No machine list specified."
else
  for MACHINEID in `cat $1`
  do
	THISMACHID=`vim-cmd vmsvc/getallvms | grep "${MACHINEID}" | awk '{print $1}'`
    POWERSTATE=`vim-cmd vmsvc/power.getstate ${THISMACHID} | sed -n 2p`
    if [ "${POWERSTATE}" == "Powered off" ]; then
      rm /vmfs/volumes/datastore1/${MACHINEID}/${MACHINEID}.vmdk
      rm /vmfs/volumes/datastore1/${MACHINEID}/${MACHINEID}-flat.vmdk
      vmkfstools -i /vmfs/volumes/datastore2/CloneImages/${MACHINEID}.vmdk /vmfs/volumes/datastore1/${MACHINEID}/${MACHINEID}.vmdk
      vim-cmd vmsvc/power.on ${THISMACHID}
    fi
  done
fi
