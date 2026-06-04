FROM php:8.2-cli

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Copy application files
COPY . /app/

# Set working directory
WORKDIR /app

# Expose port
EXPOSE 8080

# Initialize database and start built-in server
CMD php init-db.php && php -S 0.0.0.0:8080

