#!/bin/ash

# Set convenience shortcut to datastore
ln -s /vmfs/volumes/datastore1 /ds1

# Copy SSH fingerprint for WSECLAB-CONTROL
mkdir /.ssh
cp /vmfs/volumes/datastore1/wnsl-tools/.ssh/authorized_keys /.ssh/authorized_keys

# Copy script location pointer
cp /vmfs/volumes/datastore1/wnsl-tools/wnsl_script_dir.conf /wnsl_script_dir.conf

