#!/bin/ash

# Source WNSL script directory
source /wnsl_script_dir.conf

# Source WNSL Configuration file
source $WNSL_SCRIPT_DIR/wnsl-config.conf

touch $WNSL_SCRIPT_DIR/wnsl.log

# Input verification.  Requires three arguments: username,
#  machine type (attack, client, etc), and a flag that
#  defines the clone action to perform.
#  -b: Backup current image to backup slot
#  -i: Initialize current image to stock image
#  -r: Restore to current image from backup slot
#  [-cVMNAME: Clone to current image from machine VMNAME]
if [ -z $1 ]; then
  echo "No arguments specified."
  exit 1
elif [ $# -gt 3 ]; then
  echo "Too many arguments specified."
  exit 1
elif [ $# -lt 3 ]; then
  echo "Not enough arguments."
  exit 1
elif [ -z `echo $1 | grep -e ^-[utbirc]` ]; then
  echo "Invalid argument specified."
  exit 1
elif [ -z `echo $2 | grep -e ^-[utbirc]` ]; then
  echo "Invalid argument specified."
  exit 1
elif [ -z `echo $3 | grep -e ^-[utbirc]` ]; then
  echo "Invalid argument specified."
  exit 1
else
  for i in $@
  do
    if [ `echo ${i} | grep ^-u` ]; then
      USERNAME=${i}
    elif [ `echo ${i} | grep ^-t` ]; then
      VMTYPE=${i}
    else
      CLONECMD=${i}
    fi
  done
  if [ -z $USERNAME ]; then
    echo "No username specified."
    exit 1
  elif [ -z $VMTYPE ]; then
    echo "No VM type specified."
    exit 1
  elif [ -z $CLONECMD ]; then
    echo "No action flag specified."
    exit 1
  fi
fi
# Finished input verification.

# Get pertinient information about VM
USERNAME=${USERNAME##-u}
VMTYPE=${VMTYPE##-t}
if [ $VMTYPE == "a" ]; then
  VMNAME=$STU_VM_PREFIX$USERNAME$ATTACK_VM_SUFFIX
elif [ $VMTYPE == "c" ]; then
  VMNAME=$STU_VM_PREFIX$USERNAME$CLIENT_VM_SUFFIX
fi
VMINFO=`vim-cmd vmsvc/getallvms | grep "$VMNAME"`
if [ -z "$VMINFO" ]; then
  echo "No VM found for user \"$USERNAME\" of type \"$VMTYPE\"."
  exit 1
fi
VMID=${VMINFO%% *}
VMDSTORE=`echo ${VMINFO} | awk '{print $3}' | sed 's/\[//g;s/\]//g'`
VMPATH=/vmfs/volumes/$VMDSTORE/$VMNAME

# Ensure VM is off before potentially destroying its disk.
PWRSTATE="`vim-cmd vmsvc/power.getstate $VMID | grep "Powered off"`"
if [ -z "$PWRSTATE" ]; then
  echo "Cannot clone VM: machine is not off."
  exit 1
fi

if [ $VMTYPE == "a" ]; then
  TYPED_BASE_VM_NAME=$BASE_VM_NAME$ATTACK_VM_SUFFIX
elif [ $VMTYPE == "c" ]; then
  TYPED_BASE_VM_NAME=$BASE_VM_NAME$CLIENT_VM_SUFFIX
fi
if [ `echo $CLONECMD | grep -e ^-b` ]; then
  vmkfstools -U $VMPATH/$VMNAME-backup.vmdk
  vmkfstools -i $VMPATH/$VMNAME.vmdk $VMPATH/$VMNAME-backup.vmdk -d thin &
  echo "Backing up image.  This process takes about 10 minutes."
  echo "`$WNSL_SCRIPT_DIR/logdate.sh` CLONEVM: Starting backup of $VMNAME to $VMNAME-backup" >> $WNSL_SCRIPT_DIR/wnsl.log
elif [ `echo $CLONECMD | grep -e ^-i` ]; then
  # Get information about base image
  BASE_VMINFO=`vim-cmd vmsvc/getallvms | grep $TYPED_BASE_VM_NAME`
#  fi
  if [ -z "$BASE_VMINFO" ]; then
    echo "Base image not found."
    exit 1
  fi
  BASE_VMDSTORE=`echo ${BASE_VMINFO} | awk '{print $3}' | sed 's/\[//g;s/\]//g'`
  BASE_VMPATH=/vmfs/volumes/$BASE_VMDSTORE/$TYPED_BASE_VM_NAME
  vmkfstools -U $VMPATH/$VMNAME.vmdk
  vmkfstools -i $BASE_VMPATH/$BASE_VM_NAME.vmdk $VMPATH/$VMNAME.vmdk &
  echo "Initializing disk.  This process takes about 10 minutes."
  echo "`$WNSL_SCRIPT_DIR/logdate.sh` CLONEVM: Starting initialize of $VMNAME from $BASE_VM_NAME" >> $WNSL_SCRIPT_DIR/wnsl.log
elif [ `echo $CLONECMD | grep -e ^-r` ]; then
  vmkfstools -U $VMPATH/$VMNAME.vmdk
  vmkfstools -i $VMPATH/$VMNAME-backup.vmdk $VMPATH/$VMNAME.vmdk &
  echo "Restoring image from backup.  This process takes about 10 minutes."
  echo "`$WNSL_SCRIPT_DIR/logdate.sh` CLONEVM: Starting up restore of $VMNAME-backup to $VMNAME" >> $WNSL_SCRIPT_DIR/wnsl.log
elif [ `echo $CLONECMD | grep -e ^-c` ]; then
  echo "Clone is not yet implemented."
fi

