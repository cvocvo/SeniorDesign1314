#!/bin/ash

# Source script directory
source /wnsl_script_dir.conf

# Source WNSL Tools configuration
source $WNSL_SCRIPT_DIR/wnsl-config.conf

touch $WNSL_SCRIPT_DIR/wnsl.log

# Script takes a space-separated list of usernames
#  and creates one attack and one client machine
#  for each, using the image name defined in
#  wnsl-config.conf.

GLOBAL_VMINFO="`vim-cmd vmsvc/getallvms`"

TYPED_BASE_VM_NAME_ATTACK=$BASE_VM_NAME$ATTACK_VM_SUFFIX
BASE_VMINFO_ATTACK=`echo "$GLOBAL_VMINFO" | grep $TYPED_BASE_VM_NAME_ATTACK`
if [ -z "$BASE_VMINFO_ATTACK" ]; then
  echo "Attack base image not found."
  exit 1
fi
BASE_DSTORE_ATTACK=`echo ${BASE_VMINFO_ATTACK} | awk '{print $3}' | sed 's/\[//g;s/\]//g'`
BASE_VMPATH_ATTACK=/vmfs/volumes/$BASE_DSTORE_ATTACK/$TYPED_BASE_VM_NAME_ATTACK

TYPED_BASE_VM_NAME_CLIENT=$BASE_VM_NAME$CLIENT_VM_SUFFIX
BASE_VMINFO_CLIENT=`echo "$GLOBAL_VMINFO" | grep $TYPED_BASE_VM_NAME_CLIENT`
if [ -z "$BASE_VMINFO_CLIENT" ]; then
  echo "Client base image not found."
  exit 1
fi
BASE_DSTORE_CLIENT=`echo ${BASE_VMINFO_CLIENT} | awk '{print $3}' | sed 's/\[//g;s/\]//g'`
BASE_VMPATH_CLIENT=/vmfs/volumes/$BASE_DSTORE_CLIENT/$TYPED_BASE_VM_NAME_CLIENT

if [ -e $WNSL_SCRIPT_DIR/init-in-progress ]; then
  echo "An init operation is already in progress."
  exit 1
fi 
touch $WNSL_SCRIPT_DIR/init-in-progress

echo "`$WNSL_SCRIPT_DIR/logdate.sh` INITMACHINES: Beginning creation of machines via command: $@" >> $WNSL_SCRIPT_DIR/wnsl.log

let DSTORE_SET_FLAG=0
for THIS_USER in $@
do
  if [ ! -z `echo $THIS_USER | grep -e ^datastore` ]; then
    THIS_DSTORE=${THIS_USER##datastore}
    let DSTORE_SET_FLAG=1
    if [ ! -d /vmfs/volumes/datastore$THIS_DSTORE ]; then
      echo "Selected datastore does not exist."
      exit 1
    fi
  else
    if [ $DSTORE_SET_FLAG -eq 0 ]; then
      echo "First argument must be datastore number."
      exit 1
    fi
    
    echo "Creating attack machine for user \"$THIS_USER\"..."
    echo "`$WNSL_SCRIPT_DIR/logdate.sh` INITMACHINES: Started creation of $STU_VM_PREFIX$THIS_USER$ATTACK_VM_SUFFIX on datastore$THIS_DSTORE" >> $WNSL_SCRIPT_DIR/wnsl.log
    THIS_DIR_ATTACK=/vmfs/volumes/datastore$THIS_DSTORE/$STU_VM_PREFIX$THIS_USER$ATTACK_VM_SUFFIX 
    THIS_VMNAME_ATTACK=$STU_VM_PREFIX$THIS_USER$ATTACK_VM_SUFFIX
    mkdir $THIS_DIR_ATTACK 
    cp $BASE_VMPATH_ATTACK/$TYPED_BASE_VM_NAME_ATTACK.vmx $THIS_DIR_ATTACK/$THIS_VMNAME_ATTACK.vmx
    sed -i "s/$TYPED_BASE_VM_NAME_ATTACK/$THIS_VMNAME_ATTACK/g" $THIS_DIR_ATTACK/$THIS_VMNAME_ATTACK.vmx
    vmkfstools -i $BASE_VMPATH_ATTACK/$TYPED_BASE_VM_NAME_ATTACK.vmdk $THIS_DIR_ATTACK/$THIS_VMNAME_ATTACK.vmdk
    vim-cmd solo/registervm $THIS_DIR_ATTACK/$THIS_VMNAME_ATTACK.vmx $THIS_VMNAME_ATTACK
    sed -i -e 's/uuid\.bios = \"[a-z0-9 -]*\"/uuid.bios = ""/' $THIS_DIR_ATTACK/$THIS_VMNAME_ATTACK.vmx
    cp $THIS_DIR_ATTACK/$THIS_VMNAME_ATTACK.vmx $THIS_DIR_ATTACK/$THIS_VMNAME_ATTACK.vmx-base
    echo "`$WNSL_SCRIPT_DIR/logdate.sh` INITMACHINES: Creation of $STU_VM_PREFIX$THIS_USER$ATTACK_VM_SUFFIX on datastore$THIS_DSTORE complete." >> $WNSL_SCRIPT_DIR/wnsl.log
    
    echo "Creating client machine for user \"$THIS_USER\"..."
    echo "`$WNSL_SCRIPT_DIR/logdate.sh` INITMACHINES: Started creation of $STU_VM_PREFIX$THIS_USER$CLIENT_VM_SUFFIX on datastore$THIS_DSTORE" >> $WNSL_SCRIPT_DIR/wnsl.log
    THIS_DIR_CLIENT=/vmfs/volumes/datastore$THIS_DSTORE/$STU_VM_PREFIX$THIS_USER$CLIENT_VM_SUFFIX 
    THIS_VMNAME_CLIENT=$STU_VM_PREFIX$THIS_USER$CLIENT_VM_SUFFIX
    mkdir $THIS_DIR_CLIENT 
    cp $BASE_VMPATH_CLIENT/$TYPED_BASE_VM_NAME_CLIENT.vmx $THIS_DIR_CLIENT/$THIS_VMNAME_CLIENT.vmx
    sed -i "s/$TYPED_BASE_VM_NAME_CLIENT/$THIS_VMNAME_CLIENT/g" $THIS_DIR_CLIENT/$THIS_VMNAME_CLIENT.vmx
    vmkfstools -i $BASE_VMPATH_CLIENT/$TYPED_BASE_VM_NAME_CLIENT.vmdk $THIS_DIR_CLIENT/$THIS_VMNAME_CLIENT.vmdk
    vim-cmd solo/registervm $THIS_DIR_CLIENT/$THIS_VMNAME_CLIENT.vmx $THIS_VMNAME_CLIENT
    sed -i -e 's/uuid\.bios = \"[a-z0-9 -]*\"/uuid.bios = ""/' $THIS_DIR_CLIENT/$THIS_VMNAME_CLIENT.vmx
    cp $THIS_DIR_CLIENT/$THIS_VMNAME_CLIENT.vmx $THIS_DIR_CLIENT/$THIS_VMNAME_CLIENT.vmx-base
    echo "`$WNSL_SCRIPT_DIR/logdate.sh` INITMACHINES: Creation of $STU_VM_PREFIX$THIS_USER$CLIENT_VM_SUFFIX on datastore$THIS_DSTORE complete." >> $WNSL_SCRIPT_DIR/wnsl.log
    
  fi
done

echo "All machines created."
echo "`$WNSL_SCRIPT_DIR/logdate.sh` INITMACHINES: All machines created." >> $WNSL_SCRIPT_DIR/wnsl.log
rm $WNSL_SCRIPT_DIR/init-in-progress
touch $WNSL_SCRIPT_DIR/INIT_DONE
