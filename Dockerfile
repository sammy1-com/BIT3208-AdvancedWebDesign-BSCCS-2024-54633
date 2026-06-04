FROM php:8.3-cli

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Copy application files
WORKDIR /app
COPY . .

# Expose the port
EXPOSE ${PORT:-8080}

# Serve the Week5 directory
CMD php -S 0.0.0.0:${PORT:-8080} -t Week5
