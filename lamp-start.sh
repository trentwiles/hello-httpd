#!/bin/bash

.mysql/run-mysqld.sh &
.apache2/run-apache2.sh &

sleep 10s

if nc -z localhost 3000;
then 
echo "Server started" 
else 
>&2 echo "Server failed to start. Refreshing..."
refresh
fi
