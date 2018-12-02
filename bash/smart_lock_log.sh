#!/bin/sh
#created by Igor Misic (igy1000mb@gmail.com) 02.12.2018.

URL="http://hacklabos.org/wifi/smart_lock_log_api.php?"
KEY="" #must be same in SQL base, example: KEY="avasd83jfdajjw"
DATE=$(date +"%s")

USER="test" #this need to be user who used smart lock to unlock the door

VALIDATOR=$(echo -n $DATE | openssl dgst -sha1 -hmac $KEY)
VALIDATOR=${VALIDATOR#"(stdin)= "}
URL=$URL"validator="$VALIDATOR"&date="$DATE"&user="$USER
wget -q -O /dev/null $URL
echo $URL