#!/bin/bash

REPO_URL="https://github.com/interactinteractive-php/full-php.git"
TARGET_DIR="/home/frontend/full_php"

check_git_installed() {
    if ! command -v git &> /dev/null; then
        echo "Git is not installed. Installing Git..."
        sudo yum install -y git
        if [ $? -ne 0 ]; then
            echo "Failed to install Git. Please install Git manually and try again."
            exit 1
        fi
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
