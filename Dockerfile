FROM php:8.3-cli

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Copy application files
WORKDIR /app
COPY . .

# Expose the port
EXPOSE ${PORT:-8080}

# Initialize database and serve with router
CMD php init-db.php && php -S 0.0.0.0:${PORT:-8080} router.php

