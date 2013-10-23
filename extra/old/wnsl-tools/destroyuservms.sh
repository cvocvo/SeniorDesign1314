#!/bin/ash

# Source script directory
source /wnsl_script_dir.conf

# Source WNSL Tools configuration
source $WNSL_SCRIPT_DIR/wnsl-config.conf

# Input verification.  Requires exactly one username.
#  All other arguments are ignored.
if [ -z $1 ]; then
  echo "No arguments specified."
  exit 1
else
  if [ `echo $1 | grep ^-u` ]; then
    THIS_USER=${1##-u}
  else
    echo "No username specified."
    exit 1
  fi
fi

GLOBAL_VMINFO=`vim-cmd vmsvc/getallvms`

VMNAME_ATTACK="$STU_VM_PREFIX$THIS_USER$ATTACK_VM_SUFFIX"
VMINFO_ATTACK=`echo "$GLOBAL_VMINFO" | grep -F "${VMNAME_ATTACK}"`
VMID_ATTACK=${VMINFO_ATTACK%% *}
VMDSTORE_ATTACK=`echo ${VMINFO_ATTACK} | awk '{print $3}' | sed 's/\[//g;s/\]//g'`
VMPATH_ATTACK=/vmfs/volumes/$VMDSTORE_ATTACK/$VMNAME_ATTACK

VMNAME_CLIENT="$STU_VM_PREFIX$THIS_USER$CLIENT_VM_SUFFIX"
VMINFO_CLIENT=`echo "$GLOBAL_VMINFO" | grep -F "${VMNAME_CLIENT}"`
VMID_CLIENT=${VMINFO_CLIENT%% *}
VMDSTORE_ATTACK=`echo ${VMINFO_CLIENT} | awk '{print $3}' | sed 's/\[//g;s/\]//g'`
VMPATH_ATTACK=/vmfs/volumes/$VMDSTORE_CLIENT/$VMNAME_CLIENT

vim-cmd vmsvc/destroy $VMID_ATTACK
vim-cmd vmsvc/destroy $VMID_CLIENT

if [ -d $VMPATH_ATTACK ]
  rm -r $VMPATH_ATTACK
fi
if [ -d $VMPATH_CLIENT ]
  rm -r $VMPATH_CLIENT
fi
