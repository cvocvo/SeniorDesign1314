# Operational constants.  Should not need to be changed.
STU_VM_PREFIX="stvm_"

# Input verification.  Requires exactly one username.
# Any other arguments can be given, but those other than
# the ones implemented will be ignored.
if [ -z $1 ]; then
  echo "No arguments specified."
  exit 1
else
  let USER_FLAG=0;
  for i in $@
  do
    if [ `echo ${i} | grep ^-u` ]; then
      let USER_FLAG++
      USERNAME=${i}
    fi
  done
  if [ ${USER_FLAG} -lt 1 ]; then
    echo "No username specified."
    exit 1
  elif [ ${USER_FLAG} -gt 1 ]; then
    echo "More than one username specified."
    exit 1
  fi
fi
# Input verification complete.

# Extract machine ID
USERNAME=${USERNAME##-u}
VMNAME=$STU_VM_PREFIX$USERNAME
VMINFO=`vim-cmd vmsvc/getallvms | grep $VMNAME`
if [ -z VMINFO ]; then
  echo "No VM found for user $USERNAME."
  exit 1
fi
VMID=${VMINFO%% *}

# Knowing ID, we can now check power state.
# If machine is on, we cannot fiddle with its files.  Fail if on.
PWRSTATE=`vim-cmd vmsvc/power.getstate 800 | grep "Powered off" | grep -o P`
if [ -z $PWRSTATE ]; then
  echo "Cannot configure VM: machine is not off."
  exit 1
fi

# Locate machine files.  Make sure base configuration exists.
VMDSTORE=`echo ${VMINFO} | awk '{print $3}' | sed 's/\[//g;s/\]//g'`
VMPATH=/vmfs/volumes/$VMDSTORE/$VMNAME
if [ ! -e $VMPATH/$VMNAME.vmx-base ]; then
  echo "Base configuration backup ($VMNAME.vmx-base) not found."
  exit 1
fi

# Tally requested radios
let RADIOCOUNT_W=0
let RADIOCOUNT_B=0
let RADIOCOUNT_R=0
let RADIOCOUNT_U=0
for i in $@
do
  if [ ! -z `echo ${i} | grep ^-r` ]; then
    if [ ${i##-r} == "w" ]; then
      let RADIOCOUNT_W++
    elif [ ${i##-r} == "b" ]; then
      let RADIOCOUNT_B++
    elif [ ${i##-r} == "r" ]; then
      let RADIOCOUNT_R++
    elif [ ${i##-r} == "u" ]; then
      let RADIOCOUNT_U++
    fi
  fi
done

# Get available USB devices
USBDEVICES=`cat /dev/usbdevices | awk '$0~/T:/{lineno=NR; text=$0} \
  NR==lineno+1 && $0~/V:  Available for Passthrough/ && $0 !~ \
  /V:  Available for Passthrough, currently in use/{printf "%d/%d\n", \
  substr(text,10,2),substr(text,36,2)}'`

# Patch VM config file with USB device descriptor string
# Prerequisites: Device may not already exist in VMX,
#  and must exist in the list of available devices ($USBDEVICES).
if [ $RADIOCOUNT_W -gt 0 ]; then
  REQUESTED_W=$RADIOCOUNT_W
  POOL_W="`grep ^rType:w radiopool.conf | awk '{sub(/rUSBDependent:/,"",$2); print $2}'`"
echo $POOL_W
#  while [ $REQUESTED_W -gt 0 ]
#  do
#    if [ -z "`grep ^rType:w radiopool.conf | awk '{sub(/rUSBDependent:/,"",$2); print $2}'`" ]; then
#      echo "radio not found"
#    fi
#    echo bleh
#    let REQUESTED_W-- 
#  done
  #for RADIO_W in $(seq 1 1 `grep -c ^rType:w radiopool.conf`)
  #do
  #  echo "$RADIO_W Wifi"
  #done
fi





echo `grep ^rType:w ./radiopool.conf`


echo $USBDEVICES



echo "Num W: $RADIOCOUNT_W, R: $RADIOCOUNT_R, B: $RADIOCOUNT_B, U: $RADIOCOUNT_U"

#rm $VMPATH/$VMNAME.vmx
#cp $VMPATH/$VMNAME.vmx-base $VMPATH/$VMNAME.vmx



echo "Running script..."
