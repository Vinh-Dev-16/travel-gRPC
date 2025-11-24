# Travel gRPC Proto Library

Shared gRPC protocol buffer definitions and generated PHP code for Travel services.

## 📁 Structure

```
travel-gRPC/
├── composer.json           # Library definition
├── protos/                 # Proto source files (edit these)
│   └── tour/
│       └── v1/
│           └── tour.proto
├── src/                    # Generated PHP code (DO NOT edit manually)
│   └── Travel/
│       └── Proto/
│           └── Tour/
│               └── V1/
│                   ├── TourServiceClient.php
│                   ├── GetTourByIdRequest.php
│                   ├── ListToursRequest.php
│                   ├── TourResponse.php
│                   └── ListToursResponse.php
└── scripts/
    └── generate.sh         # Compilation script
```

## 🚀 Installation

### Prerequisites

1. **Protocol Buffers Compiler (protoc)**

   ```bash
   # Ubuntu/Debian
   sudo apt-get install -y protobuf-compiler

   # macOS
   brew install protobuf

   # Or download from: https://github.com/protocolbuffers/protobuf/releases
   ```

2. **gRPC PHP Plugin** (optional, for service generation)
   ```bash
   # Follow instructions at: https://grpc.io/docs/languages/php/quickstart/
   ```

### Install the Library

Add to your Laravel project's `composer.json`:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../travel-gRPC"
    }
  ],
  "require": {
    "travel/grpc-proto": "*"
  }
}
```

Then run:

```bash
composer install
```

## 🔧 Usage

### 1. Define Proto Files

Edit or add `.proto` files in the `protos/` directory:

```protobuf
// protos/tour/v1/tour.proto
syntax = "proto3";

package tour.v1;

option php_namespace = "Travel\\Proto\\Tour\\V1";
```

### 2. Generate PHP Code

Run the generation script:

```bash
# From the library root
composer generate

# Or directly
bash scripts/generate.sh
```

This will compile all `.proto` files and generate PHP classes in the `src/` directory.

### 3. Use in Your Laravel Services

#### Server Side (Service Implementation)

```php
<?php

namespace App\Grpc\Services;

use Travel\Proto\Tour\V1\TourServiceInterface;
use Travel\Proto\Tour\V1\GetTourByIdRequest;
use Travel\Proto\Tour\V1\TourResponse;

class TourService implements TourServiceInterface
{
    public function GetTourById(
        GetTourByIdRequest $request
    ): TourResponse {
        $tour = Tour::find($request->getTourId());

        $response = new TourResponse();
        $response->setId($tour->id);
        $response->setName($tour->name);
        $response->setDescription($tour->description);
        $response->setPrice($tour->price);

        return $response;
    }
}
```

#### Client Side (Calling Another Service)

```php
<?php

namespace App\Services;

use Travel\Proto\Tour\V1\TourServiceClient;
use Travel\Proto\Tour\V1\GetTourByIdRequest;
use Grpc\ChannelCredentials;

class TourClientService
{
    private TourServiceClient $client;

    public function __construct()
    {
        $this->client = new TourServiceClient(
            'tour-service:50051',
            ['credentials' => ChannelCredentials::createInsecure()]
        );
    }

    public function getTourById(string $tourId): array
    {
        $request = new GetTourByIdRequest();
        $request->setTourId($tourId);
        $request->setLanguage('en');

        [$response, $status] = $this->client->GetTourById($request)->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            throw new \Exception("gRPC Error: " . $status->details);
        }

        return [
            'id' => $response->getId(),
            'name' => $response->getName(),
            'description' => $response->getDescription(),
            'price' => $response->getPrice(),
        ];
    }
}
```

## 📝 Development Workflow

### Adding New Proto Definitions

1. Create a new `.proto` file in `protos/`:

   ```bash
   mkdir -p protos/booking/v1
   touch protos/booking/v1/booking.proto
   ```

2. Define your service and messages

3. Generate PHP code:

   ```bash
   composer generate
   ```

4. Commit both the `.proto` file and generated code:
   ```bash
   git add protos/ src/
   git commit -m "Add booking service proto"
   ```

### Updating Existing Protos

1. Edit the `.proto` file in `protos/`

2. Regenerate PHP code:

   ```bash
   composer generate
   ```

3. Update your service implementations to match the new schema

4. Commit changes

## ⚠️ Important Notes

- **DO NOT** manually edit files in the `src/` directory - they are auto-generated
- Always regenerate after modifying `.proto` files
- Use semantic versioning for breaking changes
- Keep proto packages versioned (e.g., `tour.v1`, `tour.v2`)

## 🔄 Versioning

When making breaking changes:

1. Create a new version directory:

   ```bash
   mkdir -p protos/tour/v2
   cp protos/tour/v1/tour.proto protos/tour/v2/tour.proto
   ```

2. Update the package and namespace in the new proto:

   ```protobuf
   package tour.v2;
   option php_namespace = "Travel\\Proto\\Tour\\V2";
   ```

3. Make your changes in v2

4. Both v1 and v2 will coexist, allowing gradual migration

## 📦 Publishing

To use this library across multiple projects:

1. **Private Git Repository** (Recommended):

   ```json
   {
     "repositories": [
       {
         "type": "vcs",
         "url": "git@github.com:your-org/travel-grpc-proto.git"
       }
     ]
   }
   ```

2. **Private Packagist** or **Satis**

3. **Path Repository** (Development):
   ```json
   {
     "repositories": [
       {
         "type": "path",
         "url": "../travel-gRPC"
       }
     ]
   }
   ```

## 🧪 Testing

```bash
composer test
```

## 📄 License

MIT
