# setup
 - execute script `$ ./setup.sh`  
 - add to /etc/hosts 
 - Connect with app container ($ docker exec -it CONTAINER_NAME bash)
    - `$ cd /data/shop/development/current`
    - `$ vendor/bin/install`
    - `$ chmod 777 data/ -R`