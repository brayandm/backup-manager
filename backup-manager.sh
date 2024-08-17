#!/bin/bash

check_version() {
    version=$1
    VERSIONS_URL="https://raw.githubusercontent.com/brayandm/backup-manager/main/versions.txt"

    VERSIONS_FILE=$(mktemp)
    curl --silent --fail -o "$VERSIONS_FILE" "$VERSIONS_URL"

    if [[ $? -ne 0 ]]; then
        echo "Error: Failed to download the versions.txt file."
        rm -f "$VERSIONS_FILE"
        exit 1
    fi

    if grep -Fxq "$version" "$VERSIONS_FILE"; then
        echo "Version $version is valid."
        rm -f "$VERSIONS_FILE"
        return 0
    else
        echo "Error: Version $version does not exist."
        rm -f "$VERSIONS_FILE"
        return 1
    fi
}

install() {
    version=$1
    if [ -z "$version" ]; then
        echo "No version specified. Please provide a version."
        exit 1
    fi

    if ! check_version "$version"; then
        exit 1
    fi

    echo "Installing Backup Manager version $version..."
    
    mkdir -p ~/.local/backup-manager-cli/scripts/
    cd ~/.local/backup-manager-cli/scripts/

    curl --silent -o install https://raw.githubusercontent.com/brayandm/backup-manager/$version/install.sh
    curl --silent -o uninstall https://raw.githubusercontent.com/brayandm/backup-manager/$version/uninstall.sh
    curl --silent -o start https://raw.githubusercontent.com/brayandm/backup-manager/$version/start.sh
    curl --silent -o stop https://raw.githubusercontent.com/brayandm/backup-manager/$version/stop.sh
    curl --silent -o open https://raw.githubusercontent.com/brayandm/backup-manager/$version/open.sh

    chmod +x install
    chmod +x uninstall
    chmod +x start
    chmod +x stop
    chmod +x open

    VERSION=$version ./install

    echo "Backup Manager version $version installed successfully."
}

uninstall() {
    if [ ! -f ~/.local/backup-manager/VERSION ]; then
        echo "Backup Manager is not installed. Please execute \"backup-manager install <version>\" first."
        exit 1
    fi

    echo "Uninstalling Backup Manager..."

    mkdir -p ~/.local/backup-manager-cli/scripts/
    cd ~/.local/backup-manager-cli/scripts/
    version=$(cat ~/.local/backup-manager/VERSION)
    ./uninstall

    echo "Backup Manager uninstalled successfully."
}

start() {
    if [ ! -f ~/.local/backup-manager/VERSION ]; then
        echo "Backup Manager is not installed. Please execute \"backup-manager install <version>\" first."
        exit 1
    fi

    echo "Starting Backup Manager..."
    
    mkdir -p ~/.local/backup-manager-cli/scripts/
    cd ~/.local/backup-manager-cli/scripts/
    version=$(cat ~/.local/backup-manager/VERSION)
    ./start

    echo "Backup Manager started."
}

stop() {
    if [ ! -f ~/.local/backup-manager/VERSION ]; then
        echo "Backup Manager is not installed. Please execute \"backup-manager install <version>\" first."
        exit 1
    fi

    echo "Stopping Backup Manager..."
    
    mkdir -p ~/.local/backup-manager-cli/scripts/
    cd ~/.local/backup-manager-cli/scripts/
    version=$(cat ~/.local/backup-manager/VERSION)
    ./stop

    echo "Backup Manager stopped."
}

open() {
    if [ ! -f ~/.local/backup-manager/VERSION ]; then
        echo "Backup Manager is not installed. Please execute \"backup-manager install <version>\" first."
        exit 1
    fi
    
    echo "Opening Backup Manager..."
    
    mkdir -p ~/.local/backup-manager-cli/scripts/
    cd ~/.local/backup-manager-cli/scripts/
    version=$(cat ~/.local/backup-manager/VERSION)
    ./open

    echo "Backup Manager opened."
}

update() {
    if [ ! -f ~/.local/backup-manager/VERSION ]; then
        echo "Backup Manager is not installed. Please execute \"backup-manager install <version>\" first."
        exit 1
    fi

    version=$1
    if [ -z "$version" ]; then
        echo "No version specified. Please provide a version."
        exit 1
    fi

    if ! check_version "$version"; then
        exit 1
    fi
    
    echo "Updating Backup Manager to version $version..."
    
    mkdir -p ~/.local/backup-manager-cli/scripts/
    cd ~/.local/backup-manager-cli/scripts/
    curl --silent -o update https://raw.githubusercontent.com/brayandm/backup-manager/$version/update.sh
    chmod +x update
    VERSION=$version ./update

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
