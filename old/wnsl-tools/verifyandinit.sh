#!/bin/ash

# Source script directory
source /wnsl_script_dir.conf

# Source WNSL Tools configuration
source $WNSL_SCRIPT_DIR/wnsl-config.conf

if [ -e $WNSL_SCRIPT_DIR/init-in-progress ]; then
  echo "An init operation is already in progress."
  exit 1;
fi

GLOBAL_VMINFO="`vim-cmd vmsvc/getallvms`"

TYPED_BASE_VM_NAME_ATTACK=$BASE_VM_NAME$ATTACK_VM_SUFFIX
BASE_VMINFO_ATTACK=`echo "$GLOBAL_VMINFO" | grep $TYPED_BASE_VM_NAME_ATTACK`
if [ -z "$BASE_VMINFO_ATTACK" ]; then
  echo "Attack base image not found."
  exit 1;
fi
BASE_DSTORE_ATTACK=`echo ${BASE_VMINFO_ATTACK} | awk '{print $3}' | sed 's/\[//g;s/\]//g'`
BASE_VMPATH_ATTACK=/vmfs/volumes/$BASE_DSTORE_ATTACK/$TYPED_BASE_VM_NAME_ATTACK

TYPED_BASE_VM_NAME_CLIENT=$BASE_VM_NAME$CLIENT_VM_SUFFIX
BASE_VMINFO_CLIENT=`echo "$GLOBAL_VMINFO" | grep $TYPED_BASE_VM_NAME_CLIENT`
if [ -z "$BASE_VMINFO_CLIENT" ]; then
  echo "Client base image not found."
  exit 1;
fi
BASE_DSTORE_CLIENT=`echo ${BASE_VMINFO_CLIENT} | awk '{print $3}' | sed 's/\[//g;s/\]//g'`
BASE_VMPATH_CLIENT=/vmfs/volumes/$BASE_DSTORE_CLIENT/$TYPED_BASE_VM_NAME_CLIENT

# Script takes a space-separated list of usernames
#  and creates one attack and one client machine
#  for each, using the image name defined in
#  wnsl-config.conf.
# "Creating virtual machines..."
let DSTORE_SET_FLAG=0
for THIS_USER in $@
do
  if [ ! -z `echo $THIS_USER | grep -e ^datastore` ]; then
    THIS_DSTORE=${THIS_USER##datastore}
    let DSTORE_SET_FLAG=1
    if [ -z `echo $THIS_DSTORE | grep -e [0123456789].*` ]; then
      echo "Nonnumeric value entered for datastore."
      exit 1
    elif [ ! -d /vmfs/volumes/datastore$THIS_DSTORE ]; then
      echo "Selected datastore does not exist."
      exit 1
    fi
  else
    if [ $DSTORE_SET_FLAG -eq 0 ]; then
      echo "First argument must be datastore."
      exit 1
    fi

    let DU_ATTACK=`du $BASE_VMPATH_ATTACK/$TYPED_BASE_VM_NAME_ATTACK-flat.vmdk | awk '{print $1}'`+2048
    let DU_CLIENT=`du $BASE_VMPATH_CLIENT/$TYPED_BASE_VM_NAME_CLIENT-flat.vmdk | awk '{print $1}'`+2048

    let TOTAL_DU_PER_USER=$DU_ATTACK+$DU_ATTACK+$DU_CLIENT+$DU_CLIENT
    let TOTAL_USERS_TO_CREATE=$#-1

    DSTORE_FREESPACE=`vim-cmd hostsvc/datastore/summary datastore$THIS_DSTORE | grep freeSpace | sed 's/[A-Za-z=, ]//g'`
    DSTORE_FREESPACE=`expr $DSTORE_FREESPACE / 1024`
    
    TOTAL_DU_REQUIRED=`expr $TOTAL_DU_PER_USER \* $TOTAL_USERS_TO_CREATE`
    if [ $TOTAL_DU_REQUIRED -gt $DSTORE_FREESPACE ]; then
      echo "Cannot create VMs: Not enough free space (required: $TOTAL_DU_REQUIRED free: $DSTORE_FREESPACE)"
      exit 1
    fi
    
    echo "Creating virtual machines..."
   	nohup /vmfs/volumes/datastore1/wnsl-tools/initmachines.sh $@ &
  fi
done

