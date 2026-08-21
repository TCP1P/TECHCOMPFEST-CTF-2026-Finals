#!/bin/sh
echo $RSCTF_FLAG > /home/ctf/chall/src/flag.txt
socat tcp-l:8010,reuseaddr,fork exec:"python3 chall.py"