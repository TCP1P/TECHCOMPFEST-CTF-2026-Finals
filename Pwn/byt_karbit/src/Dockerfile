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

COPY ./run.sh ./start.sh ./image.zip ./
COPY ./flag.txt ./
COPY pow.py requirements.txt ./

# setup linux image
RUN unzip ./image.zip && rm -rf ./image.zip
RUN mv flag.txt ./shared/ && chmod 700 ./shared/*

# pow
RUN python3 -m pip install --no-cache-dir -r requirements.txt --break-system-packages

ENV PORT_LISTENER=6767
EXPOSE 6767
ENTRYPOINT ["bash", "./start.sh"]
