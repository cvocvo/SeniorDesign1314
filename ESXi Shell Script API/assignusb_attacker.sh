#!/bin/sh
studentUsername=$1
assignDevice=$2
source ./attacker_includes.conf
source ./usbdevices_includes.conf

if [ ! -d "$studentdir" ]; then
	exit 0
fi

if [ "$assignDevice" -eq "1" ]
then
    vim-cmd vmsvc/device.connusbdev $clonedir/$clonevmname.vmx "$usbdev1 autoclean1"
elif [ "$assignDevice" -eq "2" ]
then
    vim-cmd vmsvc/device.connusbdev $clonedir/$clonevmname.vmx "$usbdev2 autoclean1"
elif [ "$assignDevice" -eq "3" ]
then
    vim-cmd vmsvc/device.connusbdev $clonedir/$clonevmname.vmx "$usbdev3 autoclean1"
elif [ "$assignDevice" -eq "4" ]
then
    vim-cmd vmsvc/device.connusbdev $clonedir/$clonevmname.vmx "$usbdev4 autoclean1"
elif [ "$assignDevice" -eq "5" ]
then
    vim-cmd vmsvc/device.connusbdev $clonedir/$clonevmname.vmx "$usbdev5 autoclean1"
else
    echo "invalid USB device parameter specified"
fi