#!/bin/sh
set -eu
: "${RSCTF_FLAG:?RSCTF_FLAG is required}"
printf '%s\n' "$RSCTF_FLAG" > /home/ctf/chall/src/flag.txt
unset RSCTF_FLAG
exec socat tcp-l:8010,reuseaddr,fork exec:"python3 chall.py"
