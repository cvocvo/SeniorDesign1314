#!/bin/sh
studentUsername=$1
source ./attacker_includes.conf

if [ ! -d "$studentdir" ]; then
	exit 0
fi

#If the VM exists power it on
if [ -d "$clonedir" ]; then
	vim-cmd vmsvc/power.on $clonedir/$clonevmname.vmx
fi