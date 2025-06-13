FROM php:8.2-apache

# Salin semua file ke folder /var/www/html di dalam container
COPY . /var/www/html/

# Aktifkan mod_rewrite (jika butuh)
RUN a2enmod rewrite

# Berikan permission agar Apache bisa akses file
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
