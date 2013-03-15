#!/bin/ash

# Source base script dir
source /wnsl_script_dir.conf

USBDEVICES="`$WNSL_SCRIPT_DIR/getavailableusbdevices.sh`"
  
POOL_W="`grep ^rType:w $WNSL_SCRIPT_DIR/radiopool.conf | awk '{sub(/rUSBDependent:/,"",$2); print $2}'`"
NUM_DEVICES_W=`grep -c ^rType:w $WNSL_SCRIPT_DIR/radiopool.conf`
POOL_B="`grep ^rType:b $WNSL_SCRIPT_DIR/radiopool.conf | awk '{sub(/rUSBDependent:/,"",$2); print $2}'`"
NUM_DEVICES_B=`grep -c ^rType:b $WNSL_SCRIPT_DIR/radiopool.conf`
POOL_R="`grep ^rType:r $WNSL_SCRIPT_DIR/radiopool.conf | awk '{sub(/rUSBDependent:/,"",$2); print $2}'`"
NUM_DEVICES_R=`grep -c ^rType:r $WNSL_SCRIPT_DIR/radiopool.conf`
POOL_U="`grep ^rType:u $WNSL_SCRIPT_DIR/radiopool.conf | awk '{sub(/rUSBDependent:/,"",$2); print $2}'`"
NUM_DEVICES_U=`grep -c ^rType:u $WNSL_SCRIPT_DIR/radiopool.conf`

let NUM_DEVICES=$NUM_DEVICES_W+$NUM_DEVICES_B+$NUM_DEVICES_R+$NUM_DEVICES_U

let NUM_AVAIL_W=0
let NUM_AVAIL_B=0
let NUM_AVAIL_R=0
let NUM_AVAIL_U=0

for THIS_DEVICE_NUM in $(seq 1 1 $NUM_DEVICES)
do
  THIS_DEVICE=`echo $USBDEVICES | cut -d \  -f $THIS_DEVICE_NUM`
  
  FOUND_W=`echo $POOL_W | grep -o "$THIS_DEVICE"`;
  FOUND_B=`echo $POOL_B | grep -o "$THIS_DEVICE"`;
  FOUND_R=`echo $POOL_R | grep -o "$THIS_DEVICE"`;
  FOUND_U=`echo $POOL_U | grep -o "$THIS_DEVICE"`;
  if [ ! -z $FOUND_W ]; then
    let NUM_AVAIL_W++
  elif [ ! -z $FOUND_B ]; then
    let NUM_AVAIL_B++
  elif [ ! -z $FOUND_R ]; then
    let NUM_AVAIL_R++
  elif [ ! -z $FOUND_U ]; then
    let NUM_AVAIL_U++
  fi
done  

echo W:$NUM_AVAIL_W/$NUM_DEVICES_W \
B:$NUM_AVAIL_B/$NUM_DEVICES_B R:$NUM_AVAIL_R/$NUM_DEVICES_R \
U:$NUM_AVAIL_U/$NUM_DEVICES_U

