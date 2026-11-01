#!/bin/sh
set -e

# port d'ecoute d'Apache configurable via APP_PORT (80 par defaut)
if [ "${APP_PORT:-80}" != "80" ]; then
    sed -i "s/^Listen 80$/Listen ${APP_PORT}/" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${APP_PORT}>/" /etc/apache2/sites-available/000-default.conf
fi

# dossier des sessions PHP (le jeu repose entierement sur $_SESSION)
mkdir -p /tmp/sessions
chown www-data:www-data /tmp/sessions
echo "session.save_path = /tmp/sessions" > /usr/local/etc/php/conf.d/sessions.ini

echo "Memory Game demarre sur le port ${APP_PORT:-80}"

exec "$@"
