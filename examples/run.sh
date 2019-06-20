#!/bin/sh

runserver() {
    local port=8382
    local port_open=0

    until [ $port_open -eq 1 ]; do
        port=$(($port+1))

        port_open=$(netstat -an | grep $port | grep LISTEN)

        if [ "$port_open" = "" ]; then
            port_open=1
        fi
    done

    php -S localhost:$port
}

echo "Open your web browser and point it to the url below."
echo "===================================================="

runserver
