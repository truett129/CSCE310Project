#!/bin/bash

# Get MySQL process IDs
pids=$(ps aux | grep [m]ysql | awk '{print $2}')

# Check if there are any MySQL processes running
if [ -z "$pids" ]; then
    echo "No MySQL processes found."
    exit 0
fi

# Confirm before proceeding
echo "The following MySQL process IDs will be killed: $pids"
read -p "Are you sure you want to continue? (y/n): " -n 1 -r
echo    # Move to a new line

if [[ $REPLY =~ ^[Yy]$ ]]
then
    # Kill the MySQL processes
    for pid in $pids; do
        sudo kill -9 $pid
        echo "Killed process $pid"
    done
fi

