#!/bin/ash

source /wnsl_script_dir.conf

source $WNSL_SCRIPT_DIR/wnsl-config.conf

AVAIL_DEV=`cat /dev/usbdevices | awk '$0~/T:/{lineno=NR; text=$0} NR==lineno+1 && $0~/V:  Available for Passthrough/ && $0 !~ /V:  Available for Passthrough, currently in use/ {print text}'`
ALL_DEVS=`cat /dev/usbdevices | grep -e ^T:`

for i in $(seq 1 1 `echo "$AVAIL_DEV" | wc -l`)
do
  THIS_USB_ADDR=""
  THIS_DEV=`echo "$AVAIL_DEV" | head -$i | tail -1`
  THIS_USB_ADDR=/`echo $THIS_DEV | sed -e 's/.*Port=//' -e 's/ Cnt=.*//' -e 's/ //' -e 's/^0//'`
  THIS_PRNT=`echo $THIS_DEV | sed -e 's/.*Prnt=//' -e 's/ Port=.*//' -e 's/ //'`
  if [ ! $THIS_PRNT == $EHCI_DEV_NUM ]; then
    THIS_USB_ADDR=/`echo "$ALL_DEVS" | grep -e "Dev#= *$THIS_PRNT" | sed -e 's/.*Port=//' -e 's/ Cnt=.*//' -e 's/ //' -e 's/^0//'`$THIS_USB_ADDR
  fi
  THIS_USB_ADDR=`echo $THIS_DEV | sed -e 's/.*Bus=//' -e 's/Lev=.*//' -e 's/ //' -e 's/^0//'`$THIS_USB_ADDR
  THIS_USB_ADDR=`echo $THIS_USB_ADDR | sed -e 's/ //'`
  echo $THIS_USB_ADDR
done

