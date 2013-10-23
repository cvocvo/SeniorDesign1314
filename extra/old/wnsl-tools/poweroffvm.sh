#!/bin/ash

# Source script directory
source /wnsl_script_dir.conf

# Source WNSL Tools configuration
source $WNSL_SCRIPT_DIR/wnsl-config.conf

touch $WNSL_SCRIPT_DIR/wnsl.log

# Input verification.  Requires exactly one username and
#  one machine type (attack, client, etc).
if [ -z $1 ]; then
  echo "No user specified."
  exit 1
elif [ $# -gt 2 ]; then
  echo "Too many arguments specified."
  exit 1
elif [ $# -lt 2 ]; then
  echo "Not enough arguments."
  exit 1
fi

for i in $@
do
  if [ ! -z `echo ${i} | grep -e ^-u` ]; then
    USERNAME=${i};
  elif [ ! -z `echo ${i} | grep -e ^-t` ]; then
    VMTYPE=${i};
  fi
done
if [ -z $USERNAME ]; then
  echo "No username specified."
  exit 1
elif [ -z $VMTYPE ]; then
  echo "No VM type specified."
  exit 1
fi

USERNAME=${USERNAME##-u}
VMTYPE=${VMTYPE##-t}

# Input verification complete.

# Construct VM name and look up VMID.
if [ $VMTYPE == "a" ]; then
  VMNAME=$STU_VM_PREFIX$USERNAME$ATTACK_VM_SUFFIX
elif [ $VMTYPE == "c" ]; then
  VMNAME=$STU_VM_PREFIX$USERNAME$CLIENT_VM_SUFFIX
fi
VMINFO=`vim-cmd vmsvc/getallvms | grep $VMNAME`
if [ -z VMINFO ]; then
  echo "No VM found for user \"$USERNAME\" of type \"$VMTYPE\"."
  exit 1
fi
VMID=${VMINFO%% *}

# If power is not already off, call poweroff command.  Else, fail.
PWRSTATE=`vim-cmd vmsvc/power.getstate $VMID | grep "Powered off" | grep -o P`
if [ -z $PWRSTATE ]; then
  vim-cmd vmsvc/power.off $VMID
  echo "Action completed normally."
  echo "`$WNSL_SCRIPT_DIR/logdate.sh` POWEROFFVM: Powering off $VMNAME..." >> $WNSL_SCRIPT_DIR/wnsl.log
  exit 0
else
  echo "Could not power off: VM is already off."
  exit 1
fi

