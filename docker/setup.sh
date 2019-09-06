#!/usr/bin/env bash

volumes=( testsuite-ardb-data testsuite-project-data testsuite-elastic-data testsuite-db-data testsuite-rabbitmq-data )
stores=( DE )

for volumename in "${volumes[@]}"
do
    docker volume create --name=${volumename}
done

docker-compose up -d

rmqcontainer=$(docker ps --filter name=testsuite_rabbitmq* -aq)
appcontainer=$(docker ps --filter name=testsuite_app* -aq)

sleep 12s

docker exec -i ${rmqcontainer} rabbitmqctl add_user admin mate20mg
docker exec -i ${rmqcontainer} rabbitmqctl set_user_tags admin administrator

for store in "${stores[@]}"
do
    docker exec -i ${rmqcontainer} rabbitmqctl add_vhost /${store}_development_zed
    docker exec -i ${rmqcontainer} rabbitmqctl add_user ${store}_development mate20mg
    docker exec -i ${rmqcontainer} rabbitmqctl set_user_tags ${store}_development administrator
    docker exec -i ${rmqcontainer} rabbitmqctl set_permissions -p /${store}_development_zed ${store}_development ".*" ".*" ".*"
    docker exec -i ${rmqcontainer} rabbitmqctl set_permissions -p /${store}_development_zed admin ".*" ".*" ".*"
done

docker exec -i ${appcontainer} composer install -n