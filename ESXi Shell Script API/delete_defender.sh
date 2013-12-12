#!/bin/sh
studentUsername=$1
source ./defender_includes.conf

if [ ! -d "$studentdir" ]; then
	exit 0
fi

#If the VM exists, we should turn it off, unregister it, and delete the associated files
if [ -d "$clonedir" ]; then
	vim-cmd vmsvc/power.off $clonedir/$clonevmname.vmx
	vim-cmd vmsvc/unregister $clonedir/$clonevmname.vmx
	vmkfstools --deletevirtualdisk $clonedir/$clonevmname.vmdk
	rm -rf $clonedir
fi