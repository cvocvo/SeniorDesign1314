#!/bin/sh
studentUsername=$1
source ./defender_includes.conf
source ./usbdevices_includes.conf

if [ ! -d "$studentdir" ]; then
	exit 0
fi

#If the VM exists power it off
if [ -d "$clonedir" ]; then
	vim-cmd vmsvc/power.off $clonedir/$clonevmname.vmx
	vim-cmd vmsvc/device.disconnusbdev $clonedir/$clonevmname.vmx "$usbdev1"
	vim-cmd vmsvc/device.disconnusbdev $clonedir/$clonevmname.vmx "$usbdev2"
	vim-cmd vmsvc/device.disconnusbdev $clonedir/$clonevmname.vmx "$usbdev3"
	vim-cmd vmsvc/device.disconnusbdev $clonedir/$clonevmname.vmx "$usbdev4"
	vim-cmd vmsvc/device.disconnusbdev $clonedir/$clonevmname.vmx "$usbdev5"
fi