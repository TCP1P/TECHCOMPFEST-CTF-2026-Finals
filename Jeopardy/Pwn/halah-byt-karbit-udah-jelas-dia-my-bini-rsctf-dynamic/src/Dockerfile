FROM ubuntu:latest
ENV DEBIAN_FRONTEND=noninteractive

# Install core dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    wget \
    unzip \
    python3 \
    python3-pip \
    qemu-system-x86 \
    qemu-utils \
    python3 \
    python3-pip \
    python3-dev \
    curl \
    vim \
    socat \
    nano \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
WORKDIR /app

COPY ./run.sh ./start.sh ./
ADD --checksum=sha256:ef470e5a27e96f53e7dcd6605b7c4ab2d0925f6816e82b4502ccd54bdd3a7ac3 https://raw.githubusercontent.com/TCP1P/TECHCOMPFEST-CTF-2026-Finals/c797fe47582444f5ae1af21a06e98d13c3eb34f7/Pwn/byt_karbit/src/image.zip ./
COPY ./flag.txt ./
COPY pow.py requirements.txt ./

# setup linux image
RUN unzip ./image.zip && rm -rf ./image.zip
RUN mv flag.txt ./shared/ && chmod 700 ./shared/*

# pow
RUN python3 -m pip install --no-cache-dir -r requirements.txt --break-system-packages

ENV PORT_LISTENER=6767
EXPOSE 6767
# Original final-stage ENTRYPOINT is wrapped by RSCTF at runtime.

# RSCTF dynamic-flag compatibility layer. The build-time scan records
# exact marker-bearing files; runtime replacement is therefore bounded
# to those files instead of walking the image on every team start.
USER root
COPY rsctf-entrypoint.sh /usr/local/bin/rsctf-entrypoint
RUN set -eu; \
    chmod 0555 /usr/local/bin/rsctf-entrypoint; \
    : > /etc/rsctf-flag-targets; \
    for root in /app /src /srv /home /opt /var/www /challenge /chall /ctf /root /usr/src /usr/local/bin /workspace /flag /flag.txt; do \
      if [ -e "$root" ]; then \
        find "$root" -xdev -type f ! -path /usr/local/bin/rsctf-entrypoint -exec grep -lF -e RSCTF_DYNAMIC_FLAG_9f868dd71959ede756861af1b1a506b3ee81590297f0e98a64b -e RSCTF_DYNAMIC_FLAG_3acf474b334619aa0d49285b74b97c2aad93d29819f2c1 {} + >> /etc/rsctf-flag-targets 2>/dev/null || true; \
      fi; \
    done; \
    sort -u /etc/rsctf-flag-targets > /etc/rsctf-flag-targets.sorted; \
    mv /etc/rsctf-flag-targets.sorted /etc/rsctf-flag-targets; \
    while IFS= read -r target; do [ -f "$target" ] && chmod a+w "$target"; done < /etc/rsctf-flag-targets
ENTRYPOINT ["/usr/local/bin/rsctf-entrypoint", "bash", "./start.sh"]
