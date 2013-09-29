#getradiostatus requires a properly-formatted list of radios
if [ -z $1 ]; then
  echo "No radio list specified."
else
  for THISVM in `vim-cmd vmsvc/getallvms | grep '^[0-9]' | awk '{print $1 "%" $2}'`
  do
    VMIDENT=`echo ${THISVM} | sed 's/\(^[0-9]*\).*/\1/'`
    VMNAME=`echo ${THISVM} | sed 's/^[0-9].*%\(.*\)/\1/'`
    VMPOWERSTATE=`vim-cmd vmsvc/power.getstate ${VMIDENT} | sed -n '2p'` 
    if [ "${VMPOWERSTATE}" == "Powered on" ]; then
      cat ../${VMNAME}/${VMNAME}.vmx | grep pciPassthru0.systemId | awk '{print $3}'
    fi
  done
fi  
