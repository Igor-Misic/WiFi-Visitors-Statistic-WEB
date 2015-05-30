#!/bin/sh
#created by Igor Misic (igy1000mb@gmail.com) 30.01.2015.
#cron setup: * * * * * /scripts/update_wifi_users_status.sh

URL="http://hacklabos.org/wifi/insert_mac_address_api.php?"
KEY="" #must be same as on WEB page
DATE=$(date +"%s")

USERS=""
for user in $(iw dev wlan0 station dump |  grep Station | cut -d ' ' -f 2) ; do
   
	USERS=$USERS"$user,"
done
USERS=${USERS%","}
USERS=$USERS""

VALIDATOR=$(echo -n $DATE | openssl dgst -sha1 -hmac $KEY)
VALIDATOR=${VALIDATOR#"(stdin)= "}
URL=$URL"validator="$VALIDATOR"&date="$DATE"&macArray="$USERS
wget -q -O /dev/null $URL
echo $URL