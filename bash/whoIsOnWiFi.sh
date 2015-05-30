#!/bin/sh
iw dev wlan0 station dump |  grep Station | cut -d ' ' -f 2