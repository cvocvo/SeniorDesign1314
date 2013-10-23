#!/bin/ash

# Source script directory 
source /wnsl_script_dir.conf 

# Source WNSL Tools configuration
source $WNSL_SCRIPT_DIR/wnsl-config.conf

touch $WNSL_SCRIPT_DIR/wnsl.log

# Input verification.  Requires exactly one username and
# exactly one machine type (client, attack)
# Any other arguments can be given, but those other than
# the ones implemented will be ignored.
if [ -z $1 ]; then
  echo "No arguments specified."
  exit 1
else
  let USER_FLAG=0;
  let TYPE_FLAG=0;
  for i in $@
  do
    if [ `echo ${i} | grep ^-u` ]; then
      let USER_FLAG++
      USERNAME=${i}
    elif [ `echo ${i} | grep ^-t` ]; then
      let TYPE_FLAG++
      VMTYPE=${i}
    fi
  done
  if [ ${USER_FLAG} -lt 1 ]; then
    echo "No username specified."
    exit 1
  elif [ ${USER_FLAG} -gt 1 ]; then
    echo "More than one username specified."
    exit 1
  elif [ ${TYPE_FLAG} -lt 1 ]; then
    echo "No machine type specified."
    exit 1
  elif [ ${TYPE_FLAG} -gt 1 ]; then
    echo "More than one machine type specified."
    exit 1
  fi
fi
# Input verification complete.

# Extract machine ID
USERNAME=${USERNAME##-u}
VMTYPE=${VMTYPE##-t}
if [ $VMTYPE == "c" ]; then
  VMNAME="$STU_VM_PREFIX$USERNAME$CLIENT_VM_SUFFIX"
elif [ $VMTYPE == "a" ]; then
  VMNAME="$STU_VM_PREFIX$USERNAME$ATTACK_VM_SUFFIX"
fi

VMINFO=`vim-cmd vmsvc/getallvms | grep -F "${VMNAME}"`
if [ -z "$VMINFO" ]; then
  echo "No VM found for user \"$USERNAME\" of type \"$VMTYPE\"."
  exit 1
fi

VMID=${VMINFO%% *}

# Knowing ID, we can now check power state.
# If machine is on, we cannot fiddle with its files.  Fail if on.
PWRSTATE="`vim-cmd vmsvc/power.getstate $VMID | grep "Powered off"`"
if [ -z "$PWRSTATE" ]; then
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
USBDEVICES=`$WNSL_SCRIPT_DIR/getavailableusbdevices.sh`

# Patch VM config file with USB device descriptor string
# Prerequisites: Device may not already exist in VMX,
#  and must exist in the list of available devices ($USBDEVICES).
ASSIGNED_USB_COUNT=0
if [ -e $VMPATH/$VMNAME.vmx ]; then
  rm $VMPATH/$VMNAME.vmx
fi
cp $VMPATH/$VMNAME.vmx-base $VMPATH/$VMNAME.vmx
if [ $RADIOCOUNT_W -gt 0 ]; then
  REQUESTED_W=$RADIOCOUNT_W
  POOL_W="`grep ^rType:w $WNSL_SCRIPT_DIR/radiopool.conf | awk '{sub(/rUSBDependent:/,"",$2); print $2}'`"
  POOL_COUNT=`grep -c ^rType:w $WNSL_SCRIPT_DIR/radiopool.conf`
  for REQ_POS_W in $(seq 1 1 $POOL_COUNT)
  do
    if [ $REQUESTED_W -gt 0 ]; then
      THIS_RADIO=`echo $POOL_W | cut -d \  -f $REQ_POS_W`
      if [ -z `grep path:$THIS_RADIO $VMPATH/$VMNAME.vmx` ]; then
        if [ ! -z `echo "$USBDEVICES" | grep -o "$THIS_RADIO"` ]; then
          echo "usb.autoConnect.device$ASSIGNED_USB_COUNT = \"path:$THIS_RADIO\"" >> $VMPATH/$VMNAME.vmx
          let ASSIGNED_USB_COUNT++
          let REQUESTED_W--
        fi
      fi
    fi
    if [ $REQUESTED_W -eq 0 ]; then
      break;
    fi
  done
  if [ $REQUESTED_W -gt 0 ]; then
    echo "Cannot provision VM: Not enough devices available."
    rm $VMPATH/$VMNAME.vmx
    cp $VMPATH/$VMNAME.vmx-base $VMPATH/$VMNAME.vmx
    exit 1
  fi
fi
if [ $RADIOCOUNT_B -gt 0 ]; then
  REQUESTED_B=$RADIOCOUNT_B
  POOL_B="`grep ^rType:b $WNSL_SCRIPT_DIR/radiopool.conf | awk '{sub(/rUSBDependent:/,"",$2); print $2}'`"
  POOL_COUNT=`grep -c ^rType:b $WNSL_SCRIPT_DIR/radiopool.conf`
  for REQ_POS_B in $(seq 1 1 $POOL_COUNT)
  do
    if [ $REQUESTED_B -gt 0 ]; then
      THIS_RADIO=`echo $POOL_B | cut -d \  -f $REQ_POS_B`
      if [ -z `grep path:$THIS_RADIO $VMPATH/$VMNAME.vmx` ]; then
        if [ ! -z `echo "$USBDEVICES" | grep -o "$THIS_RADIO"` ]; then
          echo "usb.autoConnect.device$ASSIGNED_USB_COUNT = \"path:$THIS_RADIO\"" >> $VMPATH/$VMNAME.vmx
          let ASSIGNED_USB_COUNT++
          let REQUESTED_B--
        fi
      fi
    fi
    if [ $REQUESTED_B -eq 0 ]; then
      break;
    fi
  done
  if [ $REQUESTED_B -gt 0 ]; then
    echo "Cannot provision VM: Not enough devices available."
    rm $VMPATH/$VMNAME.vmx
    cp $VMPATH/$VMNAME.vmx-base $VMPATH/$VMNAME.vmx
    exit 1
  fi
fi
if [ $RADIOCOUNT_R -gt 0 ]; then
  REQUESTED_R=$RADIOCOUNT_R
  POOL_R="`grep ^rType:r $WNSL_SCRIPT_DIR/radiopool.conf | awk '{sub(/rUSBDependent:/,"",$2); print $2}'`"
  POOL_COUNT=`grep -c ^rType:r $WNSL_SCRIPT_DIR/radiopool.conf`
  for REQ_POS_B in $(seq 1 1 $POOL_COUNT)
  do
    if [ $REQUESTED_R -gt 0 ]; then
      THIS_RADIO=`echo $POOL_R | cut -d \  -f $REQ_POS_R`
      if [ -z `grep path:$THIS_RADIO $VMPATH/$VMNAME.vmx` ]; then
        if [ ! -z `echo "$USBDEVICES" | grep -o "$THIS_RADIO"` ]; then
          echo "usb.autoConnect.device$ASSIGNED_USB_COUNT = \"path:$THIS_RADIO\"" >> $VMPATH/$VMNAME.vmx
          let ASSIGNED_USB_COUNT++
          let REQUESTED_R--
        fi
      fi
    fi
    if [ $REQUESTED_R -eq 0 ]; then
      break;
    fi
  done
  if [ $REQUESTED_R -gt 0 ]; then
    echo "Cannot provision VM: Not enough devices available."
    rm $VMPATH/$VMNAME.vmx
    cp $VMPATH/$VMNAME.vmx-base $VMPATH/$VMNAME.vmx
    exit 1
  fi
fi
if [ $RADIOCOUNT_U -gt 0 ]; then
  REQUESTED_U=$RADIOCOUNT_U
  POOL_U="`grep ^rType:u $WNSL_SCRIPT_DIR/radiopool.conf | awk '{sub(/rUSBDependent:/,"",$2); print $2}'`"
  POOL_COUNT=`grep -c ^rType:u $WNSL_SCRIPT_DIR/radiopool.conf`
  for REQ_POS_U in $(seq 1 1 $POOL_COUNT)
  do
    if [ $REQUESTED_U -gt 0 ]; then
      THIS_RADIO=`echo $POOL_U | cut -d \  -f $REQ_POS_U`
      if [ -z `grep path:$THIS_RADIO $VMPATH/$VMNAME.vmx` ]; then
        if [ ! -z `echo "$USBDEVICES" | grep -o "$THIS_RADIO"` ]; then
          echo "usb.autoConnect.device$ASSIGNED_USB_COUNT = \"path:$THIS_RADIO\"" >> $VMPATH/$VMNAME.vmx
          let ASSIGNED_USB_COUNT++
          let REQUESTED_U--
        fi
      fi
    fi
    if [ $REQUESTED_U -eq 0 ]; then
      break;
    fi
  done
  if [ $REQUESTED_U -gt 0 ]; then
    echo "Cannot provision VM: Not enough devices available."
    rm $VMPATH/$VMNAME.vmx
    cp $VMPATH/$VMNAME.vmx-base $VMPATH/$VMNAME.vmx
    exit 1
  fi
fi 

# Boot VM and return
BOOT_STRING=`vim-cmd vmsvc/power.on $VMID`
if [ "$BOOT_STRING" == "Powering on VM:" ]; then
  echo "Action completed normally."
  echo "`$WNSL_SCRIPT_DIR/logdate.sh` PROVISIONANDBOOT: Booting $VMNAME..." >> $WNSL_SCRIPT_DIR/wnsl.log
else
  echo "VM boot failed!"
  exit 1
fi

exit 0
