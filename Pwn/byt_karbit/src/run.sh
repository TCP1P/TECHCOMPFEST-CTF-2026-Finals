#!/bin/sh
cd /app/

python3 pow.py ask 31337
if [ $? -ne 0 ]; then
  exit 1
fi

qemu-system-x86_64 \
  -m 64M \
  -cpu qemu64 \
  -kernel $PWD/bzImage \
  -drive file=$PWD/rootfs.ext4,format=raw,if=virtio \
  -virtfs local,path=$PWD/shared,mount_tag=hostshare,security_model=none,readonly=on \
  -snapshot \
  -nographic \
  -monitor /dev/null \
  -no-reboot \
  -smp 2 \
  -netdev user,id=n0 \
  -device virtio-net-pci,netdev=n0 \
  -append "console=ttyS0 root=/dev/vda rw rootfstype=ext4 init=/init kaslr loglevel=0 oops=panic panic=-1"
