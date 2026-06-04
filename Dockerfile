FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Copy application files
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Enable mod_rewrite for clean URLs
RUN a2enmod rewrite

# Expose port
EXPOSE 80

# Initialize database and start Apache
RUN echo '#!/bin/bash\nphp /var/www/html/init-db.php\napache2-foreground' > /entrypoint.sh && chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

