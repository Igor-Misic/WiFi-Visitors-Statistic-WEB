#!/bin/sh
#created by Igor Misic (igy1000mb@gmail.com) 02.12.2018.
#modified by Vale & Brna (vale.brna@gmail.com) 26.12.2018
SENS=$(/home/pi/scripts/AdafruitDHT.py 11 4)
TEMPERATURE=$(echo $SENS|cut -d "*" -f 1|cut -d "=" -f 2)
URL="http://bigbrother.hacklabos.org/temperature_api.php?"
KEY="HzpVtjzStaq3mQlWKd9r"
DATE=$(date +"%s")

VALIDATOR=$(echo -n $DATE | openssl dgst -sha1 -hmac $KEY)
VALIDATOR=${VALIDATOR#"(stdin)= "}
URL=$URL"validator="$VALIDATOR"&date="$DATE"&temperature="$TEMPERATURE
sleep 1
wget -q -O /dev/null $URL
echo $URL
