# Quick Setup Guide

## 🚀 Quick Start (5 minutes)

### 1. Install Dependencies

```bash
cd /home/vinh/CODE/travel/travel-gRPC
composer install
```

### 2. Install protoc (if not already installed)

**Ubuntu/Debian:**

```bash
sudo apt-get update
sudo apt-get install -y protobuf-compiler
protoc --version  # Should show libprotoc 3.x.x or higher
```

**macOS:**

```bash
brew install protobuf
protoc --version
```

### 3. Generate PHP Code from Proto Files

```bash
composer generate
```

This will compile `protos/tour/v1/tour.proto` and generate PHP classes in `src/`.

### 4. Use in Your Laravel Project

**Option A: Local Development (Path Repository)**

In your Laravel project's `composer.json`:

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

Then run:

```bash
composer require travel/grpc-proto:@dev
```

**Option B: Git Repository (Production)**

Push this library to a Git repository, then:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/your-org/travel-grpc-proto.git"
    }
  ],
  "require": {
    "travel/grpc-proto": "^1.0"
  }
}
```

### 5. Install gRPC Extension in Laravel Projects

Your Laravel services need the gRPC PHP extension:

```bash
# Install gRPC extension
pecl install grpc

# Add to php.ini
echo "extension=grpc.so" | sudo tee -a /etc/php/8.1/cli/php.ini
echo "extension=grpc.so" | sudo tee -a /etc/php/8.1/fpm/php.ini

# Verify
php -m | grep grpc
```

### 6. Use in Your Code

**Client Side (calling another service):**

```php
use Travel\Proto\Tour\V1\TourServiceClient;
use Travel\Proto\Tour\V1\GetTourByIdRequest;

$client = new TourServiceClient('tour-service:50051', [
    'credentials' => \Grpc\ChannelCredentials::createInsecure()
]);

$request = new GetTourByIdRequest();
$request->setTourId('123');

[$response, $status] = $client->GetTourById($request)->wait();
echo $response->getName();
```

**Server Side (implementing the service):**

See `examples/TourGrpcServer.php` for a complete implementation.

## 📁 What You Have Now

```
travel-gRPC/
├── composer.json              ✅ Library definition
├── protos/tour/v1/tour.proto  ✅ Your Tour service definition
├── src/                       ✅ Generated PHP code (after running composer generate)
├── scripts/generate.sh        ✅ Compilation script
├── examples/                  ✅ Usage examples
├── README.md                  ✅ Full documentation
└── CONTRIBUTING.md            ✅ Development guidelines
```

## 🔄 Development Workflow

### Adding a New Service (e.g., Booking)

1. **Create proto file:**

   ```bash
   mkdir -p protos/booking/v1
   nano protos/booking/v1/booking.proto
   ```

2. **Define your service:**

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

3. **Generate PHP code:**

   ```bash
   composer generate
   ```

4. **Commit:**
   ```bash
   git add protos/ src/
   git commit -m "feat(booking): add booking service proto"
   ```

### Updating Existing Proto

1. Edit the `.proto` file
2. Run `composer generate`
3. Update your service implementations
4. Test
5. Commit

## 🐛 Troubleshooting

### "protoc: command not found"

Install protobuf compiler (see step 2 above)

### "grpc_php_plugin: command not found"

The plugin is optional. Without it, you'll get message classes but not service clients.
To install: https://grpc.io/docs/languages/php/quickstart/

### Generated files not found

Make sure you ran `composer generate` after creating/modifying proto files

### Autoload errors

Run `composer dump-autoload` in your Laravel project

## 📚 Next Steps

1. ✅ Review the generated code in `src/`
2. ✅ Check examples in `examples/`
3. ✅ Read the full [README.md](README.md)
4. ✅ Set up your Laravel services to use this library
5. ✅ Add more service definitions as needed

## 🆘 Need Help?

- Check [README.md](README.md) for detailed documentation
- Check [CONTRIBUTING.md](CONTRIBUTING.md) for best practices
- Review examples in `examples/` directory
