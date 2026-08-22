#!/bin/bash
TIMEOUT_DEVICE=$((60*5))
while true; do
	socat TCP-LISTEN:$PORT_LISTENER,reuseaddr,fork EXEC:"timeout ${TIMEOUT_DEVICE}s bash /app/run.sh",pty,stderr,ctty,setsid
done