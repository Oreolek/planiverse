# Planiverse

Planiverse is a minimalist, no-JS front-end for Mastodon (or Pleroma, or anything supporting the Mastodon API).

The Web was once a document delivery platform. It's now an application platform, which is great if you want everything to be an application. But what if you don't? What if you use an older computer, or like using a text-only browser, or simply prefer to turn JavaScript off? This is the Mastodon front-end for you.

## Target Browsers

* [Lynx](https://lynx.invisible-island.net/)

* [surf](http://suckless.org/coding_style/) (with JS switched off)

## Features

Planiverse is under active development and is fairly pre-alpha. That said, it has enough features to use it on a daily basis, which I do. I'm adding features as I need them, but I would love to accept contributions and feature requests from other Fediverse-dwellers.

Currently implemented features are:

* public and user timelines

* pagination

* posting, favouriting, reblogging, and replying to statuses

* viewing status threads

* status visibility

* following accounts

* viewing notifications

Features still to be implemented include:

* searching accounts

* deleting statuses

* muting/blocking accounts

## Screenshots

![Planiverse in surf](/screenshots/surf.png?raw=true "Planiverse in surf")

![Planiverse in Lynx](/screenshots/lynx.png?raw=true "Planiverse in Lynx")

## Demo

I run Planiverse off my home server at [jank.town/planiverse](https://jank.town/planiverse). Check out the [Pleroma front-end](https://jank.town) for a more fully-featured and detailed description of my instance.

As the name implies, jank.town is slow and has no guaranteed uptime because it's hosted on my arse-end home server.

## Getting Started

### Platform

Planiverse is a PHP application using the Laravel 5.5 framework. It requires a database to store OAuth client credentials, but supports any database back-end that Laravel does so you can get your choice of SQLite, PostgreSQL, MariaDB, etc.

### Minimum Requirements

* PHP 7.0.0
* A database back-end of your choice (SQLite, PostgreSQL, etc.)
* Composer
* Any hardware at all. Frankly I'd be surprised if there's anything this won't run on. I'm running it quite happily on my 550 MHz Cobalt Qube.

### Installation

1. Clone this repository.

        git clone https://github.com/FuzzJunket/planiverse.git
        cd planiverse

2. Use Composer to install dependencies.

        composer install

3. Copy .env.example to .env and edit it to configure your application. Be sure to set the following values:

    * APP_ENV: "local" for development, "production" for a live system.

    * APP_KEY: A random 32-digit string.

    * APP_DEBUG: "true" for development, "false" for a live system.

    * APP_URL and MASTODON_REDIRECT: Update these values with the domain and path where you're hosting Planiverse. This may or may not be the same domain as your Mastodon instance.

    * DB_CONNECTION and DB_DATABASE: These use SQLite by default, but you may want something more heavy-duty for bigger installations.

    * MASTODON_DOMAIN: The URL of your Mastodon instance.

4. If you're using SQLite, create the database file.

        touch database/database.sqlite

5. Run the database migrations to configure your DB.

        php artisan migrate

6. When running in development/testing mode you can run `php artisan serve`. For production environments you will want to configure your Web server to serve the application. See below for some example virtual host configurations.

### Sample Server Configurations

#### Nginx

Planiverse will happily run on the same domain as your Mastodon instance if you want to serve it from a sub-folder:

    server {
        listen       443;
        server_name  example.com;

        ssl_certificate /etc/letsencrypt/live/example.com/fullchain.pem;
        ssl_certificate_key /etc/letsencrypt/live/example.com/privkey.pem;

        ssl_session_cache    shared:SSL:50m;
        ssl_session_timeout  5m;

        ssl_protocols  TLSv1 TLSv1.1 TLSv1.2;
        ssl_ciphers  ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-DSS-AES128-GCM-SHA256:kEDH+AESGCM:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-DSS-AES256-SHA:DHE-RSA-AES256-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:AES128-SHA256:AES256-SHA256:AES128-SHA:AES256-SHA:AES:CAMELLIA:DES-CBC3-SHA:!aNULL:!eNULL:!EXPORT:!DES:!RC4:!MD5:!PSK:!aECDH:!EDH-DSS-DES-CBC3-SHA:!EDH-RSA-DES-CBC3-SHA:!KRB5-DES-CBC3-SHA;
        ssl_prefer_server_ciphers  on;

        # Force every request after an initial SSL connection to occur over HTTPS
        add_header  Strict-Transport-Security max-age=15768000;

        gzip_vary on;
        gzip_proxied any;
        gzip_comp_level 6;
        gzip_buffers 16 8k;
        gzip_http_version 1.1;
        gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript application/activity+json application/atom+xml;

        # the nginx default is 1m, not enough for large media uploads
        client_max_body_size 16m;

        # Configure anything under /planiverse to point to the Planiverse front-end.
        location ^~ /planiverse {
            alias /var/www/planiverse/public;
            index index.php;
            try_files $uri $uri/ @planiverse;

            location ~* \.php {
                fastcgi_split_path_info ^(.+\.php)(/.*)$;
                fastcgi_pass 127.0.0.1:9000;
                include /etc/nginx/fastcgi_params;
                fastcgi_param SCRIPT_FILENAME /var/www/planiverse/public/index.php;
            }
        }

        location @planiverse {
            rewrite /planiverse/(.*)$ /planiverse/index.php?/$1 last;
        }

        # Configure the root path to point to Pleroma.
        location / {
            add_header X-XSS-Protection "1; mode=block";
            add_header X-Permitted-Cross-Domain-Policies none;
            add_header X-Frame-Options DENY;
            add_header X-Content-Type-Options nosniff;
            add_header Referrer-Policy same-origin;
            add_header X-Download-Options noopen;

            proxy_http_version 1.1;
            proxy_set_header Upgrade $http_upgrade;
            proxy_set_header Connection "upgrade";
            proxy_set_header Host $http_host;

            proxy_pass  http://localhost:4000/;
        }

        # We don't need .ht files with nginx.
        location ~ /\.ht {
            deny all;
        }
    }

Or, for a simpler configuration or to point to a remote Mastodon instance, you can host Planiverse on its own domain. Just configure it as a regular PHP application.