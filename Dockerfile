# Dùng Alpine cho nhẹ
FROM php:8.2-cli-alpine

# 1. Cài đặt script hỗ trợ cài extension siêu tốc (Magic Tool)
# Script này giúp tránh việc compile gRPC từ source mất 15 phút
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# 2. Cài đặt các công cụ hệ thống cần thiết cho Protoc
RUN apk update && apk add --no-cache \
    git \
    zip \
    unzip \
    protobuf \
    protobuf-dev \
    grpc \
    grpc-plugins

# 3. Cài extension PHP (Dùng script ở bước 1)
# Nó sẽ cài grpc và protobuf cực nhanh
RUN install-php-extensions grpc protobuf

# 4. Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Thiết lập thư mục
WORKDIR /app

CMD ["sh"]