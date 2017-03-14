#!/usr/bin/env bash

# The script can configure an environment. By default is local.
OPERATION=${1}
CONTAINER=${2:-web_1}
PROJECT_NAME=$(basename `pwd` | sed 's/-//g' | tr '[:upper:]' '[:lower:]')
#PROJECT_NAME=`echo $PROJECT_NAME`  $PROJECT_NAME  $PROJECT_NAME
USAGE="Usage: ./docker-lamp/local-docker.sh start|stop|restart|status|goto [web|mysql|...]"


if [ -z $OPERATION ]
then
    echo $USAGE
    exit 1
fi


if [[ $PROJECT_NAME =~ "script" ]]; then
    echo "You have to execute that script from the project's root folder"
    exit 1
fi


cd docker-lamp


if [[ $OPERATION == "start" ]]; 
    then
    docker-compose --project-name $PROJECT_NAME up -d 
elif [[ $OPERATION == "stop" ]]; 
    then
    docker-compose --project-name $PROJECT_NAME down
elif [[ $OPERATION == "restart" ]]; 
    then
    docker-compose --project-name $PROJECT_NAME down
    docker-compose --project-name $PROJECT_NAME up -d 
elif [[ $OPERATION == "status" ]]; 
    then
    docker-compose --project-name $PROJECT_NAME ps
elif [[ $OPERATION == "goto" ]]; 
    then
    echo "Accesing to '${PROJECT_NAME}_${CONTAINER}'"
    docker exec -it  --user=me ${PROJECT_NAME}_${CONTAINER} /bin/bash
elif [[ $OPERATION == "gotoroot" ]];
    then
    echo "Accesing to '${PROJECT_NAME}_${CONTAINER}'"
    docker exec -it  --user=me ${PROJECT_NAME}_${CONTAINER} /bin/bash
else 
    echo "Unknown operation '$OPERATION'"
    echo $USAGE
fi
