# Travel gRPC Proto Library - Hướng Dẫn Tiếng Việt

Thư viện chia sẻ định nghĩa Protocol Buffer và code PHP được tạo tự động cho các dịch vụ Travel.

## 🎯 Mục Đích

Thư viện này cho phép **chia sẻ định nghĩa gRPC** giữa nhiều service Laravel PHP. Thay vì mỗi service tự định nghĩa proto riêng, tất cả đều dùng chung một thư viện này.

## 📁 Cấu Trúc

```
travel-gRPC/
├── composer.json           # File định nghĩa thư viện
├── protos/                 # ⭐ Nơi chứa file .proto gốc (BẠN SỬA Ở ĐÂY)
│   └── tour/
│       └── v1/
│           └── tour.proto
├── src/                    # ⚠️ Code PHP ĐÃ BIÊN DỊCH (KHÔNG SỬA TAY)
│   └── Travel/
│       └── Proto/
│           └── Tour/
│               └── V1/
│                   ├── TourServiceClient.php
│                   ├── GetTourByIdRequest.php
│                   └── ...
└── scripts/
    └── generate.sh         # Script để chạy lệnh compile
```

## 🚀 Cài Đặt Nhanh

### 1. Cài đặt protoc (Protocol Buffers Compiler)

```bash
# Ubuntu/Debian
sudo apt-get install -y protobuf-compiler

# Kiểm tra
protoc --version
```

### 2. Cài đặt dependencies

```bash
cd /home/vinh/CODE/travel/travel-gRPC
composer install
```

### 3. Tạo code PHP từ file proto

```bash
# Cách 1: Dùng composer
composer generate

# Cách 2: Dùng Makefile
make generate

# Cách 3: Chạy trực tiếp
bash scripts/generate.sh
```

## 📝 Cách Sử Dụng

### Bước 1: Thêm vào Laravel Project

Trong file `composer.json` của Laravel service:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../travel-gRPC",
      "options": {
        "symlink": true
      }
    }
  ],
  "require": {
    "travel/grpc-proto": "@dev"
  }
}
```

Sau đó chạy:

```bash
composer require travel/grpc-proto:@dev
```

### Bước 2: Sử Dụng Trong Code

#### Server Side (Service Triển Khai)

```php
<?php

namespace App\Grpc\Services;

use Travel\Proto\Tour\V1\TourServiceInterface;
use Travel\Proto\Tour\V1\GetTourByIdRequest;
use Travel\Proto\Tour\V1\TourResponse;

class TourService implements TourServiceInterface
{
    public function GetTourById(GetTourByIdRequest $request): TourResponse
    {
        // Lấy tour từ database
        $tour = Tour::find($request->getTourId());

        // Tạo response
        $response = new TourResponse();
        $response->setId($tour->id);
        $response->setName($tour->name);
        $response->setPrice($tour->price);

        return $response;
    }
}
```

#### Client Side (Service Gọi Đến Service Khác)

```php
<?php

namespace App\Services;

use Travel\Proto\Tour\V1\TourServiceClient;
use Travel\Proto\Tour\V1\GetTourByIdRequest;
use Grpc\ChannelCredentials;

class TourClient
{
    private TourServiceClient $client;

    public function __construct()
    {
        $this->client = new TourServiceClient(
            'tour-service:50051',
            ['credentials' => ChannelCredentials::createInsecure()]
        );
    }

    public function getTour(string $tourId): array
    {
        $request = new GetTourByIdRequest();
        $request->setTourId($tourId);

        [$response, $status] = $this->client->GetTourById($request)->wait();

        return [
            'id' => $response->getId(),
            'name' => $response->getName(),
            'price' => $response->getPrice(),
        ];
    }
}
```

## 🔄 Quy Trình Phát Triển

### Thêm Service Mới

1. **Tạo file proto mới**:

   ```bash
   mkdir -p protos/booking/v1
   nano protos/booking/v1/booking.proto
   ```

2. **Định nghĩa service**:

   ```protobuf
   syntax = "proto3";

   package booking.v1;

   option php_namespace = "Travel\\Proto\\Booking\\V1";

   service BookingService {
       rpc CreateBooking (CreateBookingRequest) returns (BookingResponse);
   }

   message CreateBookingRequest {
       string tour_id = 1;
       string user_id = 2;
   }

   message BookingResponse {
       string booking_id = 1;
       string status = 2;
   }
   ```

3. **Tạo code PHP**:

   ```bash
   composer generate
   ```

4. **Commit**:
   ```bash
   git add protos/ src/
   git commit -m "feat(booking): thêm booking service"
   ```

### Sửa Service Hiện Tại

1. Sửa file `.proto` trong `protos/`
2. Chạy `composer generate`
3. Cập nhật code implementation trong Laravel services
4. Test
5. Commit

## ⚠️ Lưu Ý Quan Trọng

1. **KHÔNG BAO GIỜ** sửa file trong thư mục `src/` - chúng được tạo tự động
2. **LUÔN LUÔN** chạy `composer generate` sau khi sửa file `.proto`
3. **SỬ DỤNG** versioning cho breaking changes (v1, v2, v3...)
4. **COMMIT** cả file `.proto` và code đã generate

## 📦 Các Lệnh Hữu Ích

```bash
# Setup
make setup              # Cài đặt hoàn chỉnh
make install            # Chỉ cài dependencies
make check-protoc       # Kiểm tra protoc

# Development
make generate           # Tạo PHP code từ proto
make clean              # Xóa file đã generate
make watch              # Tự động generate khi file proto thay đổi

# Testing
make test               # Chạy tests

# Help
make help               # Hiển thị tất cả lệnh
```

## 🌟 Lợi Ích

1. **Một Nguồn Chân Lý**: Một định nghĩa proto cho tất cả services
2. **Type Safety**: Strongly typed messages và services
3. **Quản Lý Version**: Dễ dàng version và maintain
4. **Tái Sử Dụng Code**: Chia sẻ code giữa nhiều services
5. **Nhất Quán**: Cùng cấu trúc dữ liệu ở mọi nơi

## 📚 Tài Liệu

- **[README.md](README.md)**: Tài liệu đầy đủ (English)
- **[SETUP.md](SETUP.md)**: Hướng dẫn setup nhanh
- **[STRUCTURE.md](STRUCTURE.md)**: Giải thích cấu trúc
- **[CONTRIBUTING.md](CONTRIBUTING.md)**: Hướng dẫn đóng góp
- **examples/**: Ví dụ sử dụng

## 🎓 Ví Dụ Thực Tế

### Kịch Bản: Service A gọi Service B

**Service A (Booking Service)** cần lấy thông tin tour từ **Service B (Tour Service)**

1. **Service B** implement `TourServiceInterface`:

   ```php
   // Service B: app/Grpc/Services/TourService.php
   class TourService implements TourServiceInterface {
       public function GetTourById($request) {
           return new TourResponse();
       }
   }
   ```

2. **Service A** sử dụng `TourServiceClient`:

   ```php
   // Service A: app/Services/TourClient.php
   $client = new TourServiceClient('service-b:50051');
   $response = $client->GetTourById($request);
   ```

3. **Cả hai service** đều require thư viện này:
   ```json
   {
     "require": {
       "travel/grpc-proto": "@dev"
     }
   }
   ```

## 🆘 Hỗ Trợ

Nếu gặp vấn đề:

1. Đọc tài liệu trong thư mục này
2. Xem ví dụ trong `examples/`
3. Liên hệ team Travel Platform

## 🔧 Troubleshooting

### Lỗi "protoc: command not found"

```bash
sudo apt-get install protobuf-compiler
```

### Lỗi "Class not found"

```bash
# Trong Laravel project
composer dump-autoload
```

### File generate không có

```bash
# Chạy lại generate
composer generate
```

---

**Tạo bởi**: Travel Platform Team  
**License**: MIT
