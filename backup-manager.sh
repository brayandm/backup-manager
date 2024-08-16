#!/bin/bash

install() {
    version=$1
    if [ -z "$version" ]; then
        echo "No version specified. Please provide a version."
        exit 1
    fi
    echo "Installing Backup Manager version $version..."
    
    mkdir -p ~/scripts/backup-manager/
    cd ~/scripts/backup-manager/
    curl -o install https://raw.githubusercontent.com/brayandm/backup-manager/$version/install.sh
    chmod +x install
    ./install

    echo "Backup Manager version $version installed successfully."
}

uninstall() {
    if [ ! -f /opt/backup-manager/version ]; then
        echo "Backup Manager is not installed. Please execute backup-manager install <version> first."
        exit 1
    fi

    echo "Uninstalling Backup Manager..."

    mkdir -p ~/scripts/backup-manager/
    cd ~/scripts/backup-manager/
    $version=$(cat /opt/backup-manager/VERSION)
    curl -o uninstall https://raw.githubusercontent.com/brayandm/backup-manager/$version/uninstall.sh
    chmod +x uninstall
    ./uninstall

    echo "Backup Manager uninstalled successfully."
}

start() {
    if [ ! -f /opt/backup-manager/version ]; then
        echo "Backup Manager is not installed. Please execute backup-manager install <version> first."
        exit 1
    fi

    echo "Starting Backup Manager..."
    
    mkdir -p ~/scripts/backup-manager/
    cd ~/scripts/backup-manager/
    $version=$(cat /opt/backup-manager/VERSION)
    curl -o start https://raw.githubusercontent.com/brayandm/backup-manager/$version/start.sh
    chmod +x start
    ./start

    echo "Backup Manager started."
}

stop() {
    if [ ! -f /opt/backup-manager/version ]; then
        echo "Backup Manager is not installed. Please execute backup-manager install <version> first."
        exit 1
    fi

    echo "Stopping Backup Manager..."
    
    mkdir -p ~/scripts/backup-manager/
    cd ~/scripts/backup-manager/
    $version=$(cat /opt/backup-manager/VERSION)
    curl -o stop https://raw.githubusercontent.com/brayandm/backup-manager/$version/stop.sh
    chmod +x stop
    ./stop

    echo "Backup Manager stopped."
}

open() {
    if [ ! -f /opt/backup-manager/version ]; then
        echo "Backup Manager is not installed. Please execute backup-manager install <version> first."
        exit 1
    fi
    
    echo "Opening Backup Manager..."
    
    mkdir -p ~/scripts/backup-manager/
    cd ~/scripts/backup-manager/
    $version=$(cat /opt/backup-manager/VERSION)
    curl -o open https://raw.githubusercontent.com/brayandm/backup-manager/$version/open.sh
    chmod +x open
    ./open

    echo "Backup Manager opened."
}

update() {
    if [ ! -f /opt/backup-manager/version ]; then
        echo "Backup Manager is not installed. Please execute backup-manager install <version> first."
        exit 1
    fi

    version=$1
    if [ -z "$version" ]; then
        echo "No version specified. Please provide a version."
        exit 1
    fi
    echo "Updating Backup Manager to version $version..."
    
    mkdir -p ~/scripts/backup-manager/
    cd ~/scripts/backup-manager/
    curl -o update https://raw.githubusercontent.com/brayandm/backup-manager/$version/update.sh
    chmod +x update
    ./update

    echo "Backup Manager updated to version $version."
}

case "$1" in
    install)
        install "$2"
        ;;
    uninstall)
        uninstall
        ;;
    start)
        start
        ;;
    stop)
        stop
        ;;
    open)
        open
        ;;
    update)
        update "$2"
        ;;
    *)
        echo "Usage: $0 {install <version>|uninstall|start|stop|open|update <version>}"
        exit 1
        ;;
esac

exit 0
