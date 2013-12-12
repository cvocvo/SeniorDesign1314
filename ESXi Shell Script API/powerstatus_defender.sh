#!/bin/sh
studentUsername=$1
source ./defender_includes.conf

if [ ! -d "$studentdir" ]; then
	exit 0
fi

#If the VM exists power it on
if [ -d "$clonedir" ]; then
	vim-cmd vmsvc/power.getstat $clonedir/$clonevmname.vmx
fi