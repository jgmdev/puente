#!/bin/sh

runserver()
{
    local port=8383

    until php -S localhost:$port 2> /dev/null; do
        port=$(($port+1))
    done
}

echo "Open your web browser and point it to the url below."
echo "===================================================="

runserver
