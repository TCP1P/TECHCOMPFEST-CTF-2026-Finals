#!/bin/sh
set -eu
: "${RSCTF_FLAG:?RSCTF_FLAG is required}"
printf '%s\n' "$RSCTF_FLAG" > /flag.txt
unset RSCTF_FLAG
exec apache2-foreground
