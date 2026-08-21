#!/bin/sh
set -eu
: "${GZCTF_FLAG:?GZCTF_FLAG is required}"
printf '%s\n' "$GZCTF_FLAG" > /flag.txt
unset GZCTF_FLAG
exec apache2-foreground
