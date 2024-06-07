#!/bin/bash

REPO_URL="https://github.com/interactinteractive-php/full-php.git"
TARGET_DIR="HOME_DIR_LOCATION"

check_git_installed() {
    if ! command -v git &> /dev/null; then
        echo "Git is not installed. Please install Git and try again."
        exit 1
    fi
}

update_repository() {
    if [ -d "$TARGET_DIR/.git" ]; then
        echo "Directory exists. Pulling the latest changes..."
        cd "$TARGET_DIR"
        git pull origin main
    else
        echo "Directory does not exist. Cloning the repository..."
        git clone "$REPO_URL" "$TARGET_DIR"
    fi
}

check_git_installed
update_repository

echo "Operation completed."
