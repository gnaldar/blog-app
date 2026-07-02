FROM php:8.2-apache

# ── System dependencies ────────────────────────────────────────────────────────
RUN apt-get update \
 && apt-get install -y --no-install-recommends libsqlite3-dev \
 && docker-php-ext-install pdo_sqlite \
 && rm -rf /var/lib/apt/lists/*

# ── MPM fix (build-time) ───────────────────────────────────────────────────────
# php:8.2-apache ships with mpm_event enabled.  mod_php requires mpm_prefork.
# Direct symlink removal is definitive; a2dismod inside a build layer is not.
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf \
          /etc/apache2/mods-enabled/mpm_event.load \
          /etc/apache2/mods-enabled/mpm_worker.conf \
          /etc/apache2/mods-enabled/mpm_worker.load \
 && a2enmod mpm_prefork rewrite \
 && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# ── Apache virtual-host ────────────────────────────────────────────────────────
# Sets DocumentRoot to /public and uses ${PORT} for native variable expansion.
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# ── Application ───────────────────────────────────────────────────────────────
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
 && chmod +x /var/www/html/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
