# Image PHP + Apache officielle
FROM php:8.3-apache

LABEL org.opencontainers.image.title="memory-game" \
      org.opencontainers.image.description="Jeu de memoire en PHP"

# configuration Apache : racine web sur public/, start.php en page d'accueil
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# code de l'application (lecture seule pour Apache)
COPY src/ src/
COPY templates/ templates/
COPY public/ public/

# entrypoint (on retire les \r au cas ou le fichier a ete edite sous Windows)
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

ENV APP_PORT=80
EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s --retries=3 \
    CMD curl -fs "http://localhost:${APP_PORT}/" -o /dev/null || exit 1

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
