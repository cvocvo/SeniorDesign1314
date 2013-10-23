#!/bin/ash

# Source WNSL Tools directory
source /wnsl_script_dir.conf

# Source WNSL Config
source $WNSL_SCRIPT_DIR/wnsl-config.conf

ALL_VMS=`vim-cmd vmsvc/getallvms | grep $STU_VM_PREFIX`
ALL_VMS_COUNT=`echo "$ALL_VMS" | grep -c $STU_VM_PREFIX`

OUTPUT_STRING=""
for THIS_VM_POS in $(seq 1 1 $ALL_VMS_COUNT)
do
  THIS_VMINFO=`echo "$ALL_VMS" | head -$THIS_VM_POS | tail -1`
  THIS_VMID=`echo $THIS_VMINFO | awk '{print $1}'`
  THIS_VMNAME=`echo $THIS_VMINFO | awk '{print $2}'`
  THIS_USERNAME=${THIS_VMNAME##$STU_VM_PREFIX}
  if [ ! -z "`vim-cmd vmsvc/power.getstate $THIS_VMID | grep "Powered on"`"  ]; then
    OUTPUT_STRING="$OUTPUT_STRING $THIS_USERNAME:on"
  else
    OUTPUT_STRING="$OUTPUT_STRING $THIS_USERNAME:off"
  fi
done

echo $OUTPUT_STRING
