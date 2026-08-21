#!/bin/sh
set -eu
: "${GZCTF_FLAG:?GZCTF_FLAG is required}"
printf '%s\n' "$GZCTF_FLAG" > /home/ctf/chall/src/flag.txt
unset GZCTF_FLAG
exec socat tcp-l:8010,reuseaddr,fork exec:"python3 chall.py"
