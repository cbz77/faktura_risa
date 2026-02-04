# Použijeme PHP s Apachem
FROM php:8.2-apache

# Instalace závislostí pro práci s ikonv (pro kódování češtiny)
RUN apt-get update && apt-get install -y \
    libicu-dev \
    && docker-php-ext-install iconv

# Nastavení pracovního adresáře
WORKDIR /var/www/html

# Zapnutí mod_rewrite pro Apache (volitelné)
RUN a2enmod rewrite

# Expozice portu 80
EXPOSE 80