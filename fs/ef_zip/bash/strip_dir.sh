#!/bin/bash

message=$(zip -d $1 __MACOSX/\*)

baseDir=$(pwd)
subPath=/logs/logfile
logfile=$baseDir$subPath

now=$(date +%Y%m%d%H%M%S)

echo $now "--" $message >> $logfile