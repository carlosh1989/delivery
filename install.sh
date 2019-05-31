
sudo rm laravelrepoautoinstaller -r
git clone https://github.com/barinascode/laravelrepoautoinstaller.git
sudo chmod 775 laravelrepoautoinstaller -R
./laravelrepoautoinstaller/install.sh
sudo rm laravelrepoautoinstaller -r
php artisan db:seed --class=UsersFeaturesSeeder
