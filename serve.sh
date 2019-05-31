echo "Please indicate the host and port to serve  e.j : localhost:8080 OR domain.com OR domain.com:port"
read host
echo "Please indicate the path to serve"
read path
php -S $host -t $path
