FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg-dev \
    vim --no-install-recommends && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Create Xdebug configuration file
RUN echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Arguments to create a user with specific UID and username
ARG USER_ID
ARG USER_NAME

# Create a user, add /data directory for persisting files, and change ownership
RUN adduser --uid $USER_ID --disabled-password --gecos "" $USER_NAME \
    && mkdir -p /data \
    && chown -R $USER_NAME:$USER_NAME /var/www/html /data

# Set the working directory
WORKDIR /var/www/html/yii

# Start terminal in ./yii
RUN echo 'cd /var/www/html/yii' >> /home/$USER_NAME/.bashrc

# Switch to the newly created user
USER $USER_NAME

# Expose port
EXPOSE 8502

# Start Yii's built-in server
CMD ["php", "yii", "serve", "0.0.0.0", "--port=8502", "--docroot=/var/www/html/yii/web"]