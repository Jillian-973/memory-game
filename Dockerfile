# Image PHP + Apache officielle
FROM php:8.3-apache

LABEL org.opencontainers.image.title="memory-game" \
      org.opencontainers.image.description="Jeu de memoire en PHP"

# start.php sert de page d'accueil (pas d'index.php dans le projet)
RUN printf 'ServerName localhost\nDirectoryIndex start.php index.php index.html\n' \
        > /etc/apache2/conf-available/memory-game.conf \
    && a2enconf memory-game

WORKDIR /var/www/html

COPY --chown=www-data:www-data *.php style.css ./

# entrypoint (on retire les \r au cas ou le fichier a ete edite sous Windows)
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

ENV APP_PORT=80
EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s --retries=3 \
    CMD curl -fs "http://localhost:${APP_PORT}/start.php" -o /dev/null || exit 1

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
