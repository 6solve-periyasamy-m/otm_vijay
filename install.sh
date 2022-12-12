#!/bin/bash
# Script for automatically setting up OctopusTravelMatrix server instances

##########################################
# Initial Pulldown and Setup
##########################################

directory="$1"

if [[ $* == *--prod* ]]; then
  prod=1
else
  prod=0
fi

if [[ $* == *--db-auto* ]]; then
  autodb=1
else
  autodb=0
fi

if [[ $* == *--apt* ]]; then
  sudo apt update
  sudo apt install -y lsb-release ca-certificates apt-transport-https software-properties-common
  sudo add-apt-repository -y ppa:ondrej/php
  sudo apt update
  sudo apt install -y unzip nginx php8.1 php8.1-intl php8.1-fpm php8.1-pdo php8.1-xml php8.1-bcmath php8.1-gd php8.1-curl php8.1-zip  php8.1-mbstring php8.1-mysql mysql-server certbot
  # Install NPM and Node
  curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash - && sudo apt-get install -y nodejs
  sudo npm install -g npm@9.1.2
  # Install composer
  if ! command -v composer &> /dev/null; then
    cd ~ || exit
    curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
    sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
  fi
  # Install Puppeteer
  sudo apt install -y gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libappindicator1 libnss3 lsb-release xdg-utils wget
fi

git clone git@github.com:Octopus-Travel-Matrix/octopustravelmatrix.git "$directory"
cd "${directory}" || exit
composer install

webroot="$(pwd)/public"

##########################################
# Environment (.env) Setup
##########################################

# App Settings

read -e -r -p "App Name [OctopusTravelMatrix]: " input
input=${input:-OctopusTravelMatrix}
echo APP_NAME="${input}" >> .env

if [[ $prod == 1 ]]; then
  echo APP_ENV=production >> .env
  echo APP_DEBUG=false >> .env
else
  echo APP_ENV=local >> .env
  echo APP_DEBUG=true >> .env
fi

read -e -r -p "App Url [srv.octopustravelmatrix.com]: " input
url=${input:-srv.octopustravelmatrix.com}
echo APP_URL=https://"${url}" >> .env

echo APP_KEY= >> .env

# Log Settings

read -e -r -p "Log Channel [stack]: " input
input=${input:-stack}
echo LOG_CHANNEL="${input}" >> .env

read -e -r -p "Log Level [debug]: " input
input=${input:-debug}
# shellcheck disable=SC2129
echo LOG_LEVEL="${input}" >> .env

# Driver Setup (Maybe configurable in future)
{
  echo "";
  echo "BROADCAST_DRIVER=log";
  echo "CACHE_DRIVER=database";
  echo "FILESYSTEM_DRIVER=local";
  echo "QUEUE_CONNECTION=sync";
  echo "SESSION_DRIVER=file";
  echo "SESSION_LIFETIME=120";
} >> .env

# Database Setup

if [[ $autodb == 1 ]]; then
  read -e -r -p "DB Name [otm_dev]: " db
  db=${db:-otm_dev}
  pw=$(date +%s | sha256sum | base64 | head -c 32)

  sudo mysql -e "CREATE USER '${db}'@'localhost' IDENTIFIED BY '${pw}'"
  sudo mysql -e "CREATE DATABASE ${db}"
  sudo mysql -e "GRANT ALL PRIVILEGES ON ${db}.* TO '${db}'@'localhost'"
  sudo mysql -e "FLUSH PRIVILEGES"
  {
    echo "";
    echo "DB_CONNECTION=mysql";
    echo "DB_HOST=127.0.0.1";
    echo "DB_PORT=3306";
    echo "DB_DATABASE=${db}";
    echo "DB_USERNAME=${db}";
    echo "DB_PASSWORD=${pw}";
  } >> .env
else
  echo DB_CONNECTION=mysql >> .env

  read -e -r -p "DB Host [127.0.0.1]: " input
  input=${input:-127.0.0.1}
  echo DB_HOST="${input}" >> .env

  read -e -r -p "DB Port [3306]: " input
  input=${input:-3306}
  echo DB_PORT="${input}" >> .env

  read -e -r -p "DB Database [otm_dev]: " input
  input=${input:-otm_dev}
  echo DB_DATABASE="${input}" >> .env

  read -e -r -p "DB User [root]: " input
  input=${input:-root}
  echo DB_USERNAME="${input}" >> .env

  read -e -r -s -p "DB Password: " input
  echo DB_PASSWORD="${input}" >> .env
fi

if [[ $prod == 1 ]]; then
  read -e -r -p "Mail Mailer [smtp]: " input
  input=${input:-smtp}
  echo MAIL_MAILER="${input}" >> .env
  
  read -e -r -p "Mail Host: " input
  echo MAIL_HOST="${input}" >> .env
  
  read -e -r -p "Mail Port: " input
  echo MAIL_PORT="${input}" >> .env
  
  read -e -r -p "Mail Username: " input
  echo MAIL_USERNAME="${input}" >> .env
  
  read -e -r -p "Mail Password: " input
  echo MAIL_PASSWORD="${input}" >> .env
  
  read -e -r -p "Mail From Address: " input
  echo MAIL_FROM_ADDRESS="${input}" >> .env
  
  read -e -r -p "Mail From Address: " input
  echo MAIL_FROM_NAME="${input}" >> .env

  read -e -r -p "Mail Encryption [null]: " input
  input=${input:-null}
  echo MAIL_ENCRYPTION="${input}" >> .env

  read -e -r -p "Mail BCC Address: " input
  echo BCC_ADDRESS="${input}" >> .env
else
  {
    echo "";
    echo "MAIL_MAILER=minimal";
    echo "MAIL_HOST=";
    echo "MAIL_PORT=";
    echo "MAIL_USERNAME=";
    echo "MAIL_PASSWORD=";
    echo "MAIL_FROM_ADDRESS=";
    echo "MAIL_FROM_NAME=";
    echo "MAIL_ENCRYPTION=";
    echo "BCC_ADDRESS=";
  } >> .env
fi

read -e -r -p "Stripe Key: " input
echo STRIPE_KEY="${input}" >> .env

read -e -r -p "Stripe Secret: " input
echo STRIPE_SECRET="${input}" >> .env

read -e -r -p "Stripe Webhook Secret: " input
echo STRIPE_WEBHOOK_SECRET="${input}" >> .env

read -e -r -p "Cashier Currency [gbp]: " input
input=${input:-gbp}
echo CASHIER_CURRENCY="${input}" >> .env

read -e -r -p "User Limit [4]: " input
input=${input:-4}
echo CASHIER_CURRENCY="${input}" >> .env

if [[ $prod == 0 ]]; then
  echo "ALLOW_ANONYMIZATION=true" >> .env
fi

##########################################
# Adjustments to file permissions
##########################################

if [[ $* != *--no-perms* ]]; then
  echo "Adjusting File Permissions"
  sudo chgrp www-data storage/ -R
  sudo chgrp www-data bootstrap/cache/ -R
  sudo find . -type d -exec chmod 775 {} \;
  sudo find . -type f -exec chmod 664 {} \;
fi

##########################################
# Run Node Install (After file permissions)
##########################################

npm install

if [[ $prod == 1 ]]; then
  npm run production
else
  npm run dev
fi

##########################################
# Artisan Configuration
##########################################

php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link

##########################################
# Webserver Configuration
##########################################

read -e -r -p "Webserver File: " input

{
  echo "## Pregenerated Server Configuration for ${url} (${webroot})";
  echo "server {";
  echo "  root ${webroot};";
  echo "  server_name ${url};";
  echo "  index index.html index.htm index.php;";
  echo "  charset utf-8;";
  echo "  add_header X-Frame-Options \"SAMEORIGIN\";";
  echo "  add_header X-XSS-Protection \"1; mode=block\";";
  echo "  add_header X-Content-Type-Options \"nosniff\";";
  echo "  location / {";
  echo "    try_files \$uri \$uri/ /index.php?\$query_string;";
  echo "  }";
  echo "  location ~ \.php$ {";
  echo "    include snippets/fastcgi-php.conf;";
  echo "    # With php-fpm (or other unix sockets):";
  echo "    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;";
  echo "    #fastcgi_index index.php;";
  echo "    fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;";
  echo "    include fastcgi_params;";
  echo "  }";
  echo "  location ~ /\.(?!well-known).* {";
  echo "    deny all;";
  echo "  }";
  echo "  location ~ /\.ht {";
  echo "    deny all;";
  echo "  }";
  echo "}";
} >> "${input}"

sudo cp "${input}" /etc/nginx/sites-available/
sudo ln -s /etc/nginx/sites-available/"${input}" /etc/nginx/sites-enabled/"${url}"
sudo systemctl restart nginx
#sudo certbot certonly -d "${url}"
