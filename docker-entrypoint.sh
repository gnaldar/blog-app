#!/bin/sh
set -e

# ── MPM fix (runtime) ─────────────────────────────────────────────────────────
# Defensive repeat: ensures mpm_event/worker can never be loaded at container
# start, even if the host serves a cached image layer from an older build.
rm -f /etc/apache2/mods-enabled/mpm_event.conf \
      /etc/apache2/mods-enabled/mpm_event.load \
      /etc/apache2/mods-enabled/mpm_worker.conf \
      /etc/apache2/mods-enabled/mpm_worker.load

# ── PORT handling ─────────────────────────────────────────────────────────────
# Render injects $PORT dynamically.  Local Docker defaults to 80.
PORT="${PORT:-80}"

# Expose PORT to Apache's config parser so ${PORT} in vhost.conf is expanded.
echo "export PORT=${PORT}" >> /etc/apache2/envvars

# Update the Listen directive to match.
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf

# ── Permissions ───────────────────────────────────────────────────────────────
chown -R www-data:www-data /var/www/html/database

exec "$@"