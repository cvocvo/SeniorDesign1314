#!/bin/sh
studentUsername=$1
vncPortNum=$2
source ./attacker_includes.conf

if [ ! -d "$studentdir" ]; then
	mkdir $studentdir
fi

#If the folder exists, we should clear out any existing VM before making a new one
if [ -d "$clonedir" ]; then
	vim-cmd vmsvc/power.off $clonedir/$clonevmname.vmx
	vim-cmd vmsvc/unregister $clonedir/$clonevmname.vmx
	vmkfstools --deletevirtualdisk $clonedir/$clonevmname.vmdk
	rm -rf $clonedir
fi

if [ ! -d "$clonedir" ]; then
	mkdir $clonedir
fi

# First make the disk
#vmkfstools -c 15G -a lsilogic $1/$1.vmdk
vmkfstools -i $dir/$vmname.vmdk -d thin $clonedir/$clonevmname.vmdk

echo .encoding = \"UTF-8\" > $clonedir/$clonevmname.vmx
echo config.version = \"8\" >> $clonedir/$clonevmname.vmx
echo virtualHW.version = \"8\" >> $clonedir/$clonevmname.vmx
echo pciBridge0.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo pciBridge4.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo pciBridge4.virtualDev = \"pcieRootPort\" >> $clonedir/$clonevmname.vmx
echo pciBridge4.functions = \"8\" >> $clonedir/$clonevmname.vmx
echo pciBridge5.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo pciBridge5.virtualDev = \"pcieRootPort\" >> $clonedir/$clonevmname.vmx
echo pciBridge5.functions = \"8\" >> $clonedir/$clonevmname.vmx
echo pciBridge6.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo pciBridge6.virtualDev = \"pcieRootPort\" >> $clonedir/$clonevmname.vmx
echo pciBridge6.functions = \"8\" >> $clonedir/$clonevmname.vmx
echo pciBridge7.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo pciBridge7.virtualDev = \"pcieRootPort\" >> $clonedir/$clonevmname.vmx
echo pciBridge7.functions = \"8\" >> $clonedir/$clonevmname.vmx
echo vmci0.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo hpet0.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo nvram = \"$clonevmname.nvram\" >> $clonedir/$clonevmname.vmx
echo virtualHW.productCompatibility = \"hosted\" >> $clonedir/$clonevmname.vmx
echo powerType.powerOff = \"soft\" >> $clonedir/$clonevmname.vmx
echo powerType.powerOn = \"hard\" >> $clonedir/$clonevmname.vmx
echo powerType.suspend = \"hard\" >> $clonedir/$clonevmname.vmx
echo powerType.reset = \"soft\" >> $clonedir/$clonevmname.vmx
echo displayName = \"$clonevmname\" >> $clonedir/$clonevmname.vmx
echo extendedConfigFile = \"$clonevmname.vmxf\" >> $clonedir/$clonevmname.vmx
echo floppy0.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo scsi0.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo scsi0.sharedBus = \"none\" >> $clonedir/$clonevmname.vmx
echo scsi0.virtualDev = \"lsilogic\" >> $clonedir/$clonevmname.vmx
echo memsize = \"768\" >> $clonedir/$clonevmname.vmx
echo scsi0:0.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo scsi0:0.fileName = \"$clonevmname.vmdk\" >> $clonedir/$clonevmname.vmx
echo scsi0:0.deviceType = \"scsi-hardDisk\" >> $clonedir/$clonevmname.vmx
echo ide1:0.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo ide1:0.fileName = \"/vmfs/devices/cdrom/mpx.vmhba32:C0:T0:L0\" >> $clonedir/$clonevmname.vmx
echo ide1:0.deviceType = \"atapi-cdrom\" >> $clonedir/$clonevmname.vmx
echo ide1:0.startConnected = \"FALSE\" >> $clonedir/$clonevmname.vmx
echo floppy0.startConnected = \"FALSE\" >> $clonedir/$clonevmname.vmx
echo floppy0.fileName = \"\" >> $clonedir/$clonevmname.vmx
echo floppy0.clientDevice = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo ethernet0.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo ethernet0.virtualDev = \"e1000\" >> $clonedir/$clonevmname.vmx
echo ethernet0.networkName = \"VM Network\" >> $clonedir/$clonevmname.vmx
echo ethernet0.addressType = \"generated\" >> $clonedir/$clonevmname.vmx
echo chipset.onlineStandby = \"FALSE\" >> $clonedir/$clonevmname.vmx
echo guestOS = \"debian6-64\" >> $clonedir/$clonevmname.vmx
echo scsi0.pciSlotNumber = \"16\" >> $clonedir/$clonevmname.vmx
echo ethernet0.pciSlotNumber = \"32\" >> $clonedir/$clonevmname.vmx
echo vmci0.pciSlotNumber = \"33\" >> $clonedir/$clonevmname.vmx
echo tools.syncTime = \"FALSE\" >> $clonedir/$clonevmname.vmx
echo cleanShutdown = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo replay.supported = \"FALSE\" >> $clonedir/$clonevmname.vmx
echo evcCompatibilityMode = \"FALSE\" >> $clonedir/$clonevmname.vmx
echo softPowerOff = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo usb.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo ehci.present = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo ehci.pciSlotNumber = \"0\" >> $clonedir/$clonevmname.vmx
echo RemoteDisplay.vnc.enabled = \"TRUE\" >> $clonedir/$clonevmname.vmx
echo RemoteDisplay.vnc.password = \"$studentUsername\" >> $clonedir/$clonevmname.vmx
echo RemoteDisplay.vnc.port = \"$vncPortNum\" >> $clonedir/$clonevmname.vmx

# Now register our new VM
vnum=`vim-cmd solo/registervm $clonedir/$clonevmname.vmx`
vim-cmd vmsvc/power.on $vnum