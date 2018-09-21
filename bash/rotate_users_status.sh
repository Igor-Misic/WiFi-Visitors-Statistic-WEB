#!/bin/sh
#created by Igor Misic (igy1000mb@gmail.com) 30.01.2015.
#cron setup: * * * * * /scripts/update_wifi_users_status.sh

URL="http://labos.cromish.com/insert_mac_address_api.php?"
KEY="" #must be same as one in SQL base, example: KEY="avasd83jfdajjw"
DATE=$(date +"%s")

VALIDATOR=$(echo -n $DATE | openssl dgst -sha1 -hmac $KEY)
VALIDATOR=${VALIDATOR#"(stdin)= "}
URL=$URL"validator="$VALIDATOR"&date="$DATE"&rotate=true"
wget -q -O /dev/null $URL
echo $URL