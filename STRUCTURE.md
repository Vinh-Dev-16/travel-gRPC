# Travel gRPC Proto Library - Structure Overview

## 📦 Complete Library Structure

```
travel-gRPC/
│
├── 📄 composer.json              # Package definition & dependencies
├── 📄 LICENSE                    # MIT License
├── 📄 README.md                  # Main documentation
├── 📄 SETUP.md                   # Quick setup guide
├── 📄 CONTRIBUTING.md            # Development guidelines
├── 📄 CHANGELOG.md               # Version history
├── 📄 Makefile                   # Convenient commands
├── 📄 phpunit.xml                # Testing configuration
├── 📄 .gitignore                 # Git ignore rules
│
├── 📁 protos/                    # ⭐ Proto source files (YOU EDIT THESE)
│   └── tour/
│       └── v1/
│           └── tour.proto        # Tour service definition
│
├── 📁 src/                       # ⚠️ Generated PHP code (DO NOT EDIT)
│   └── Travel/
│       └── Proto/
│           └── Tour/
│               └── V1/
│                   ├── TourServiceClient.php
│                   ├── TourServiceInterface.php
│                   ├── GetTourByIdRequest.php
│                   ├── ListToursRequest.php
│                   ├── TourResponse.php
│                   └── ListToursResponse.php
│
├── 📁 scripts/
│   └── generate.sh               # Proto compilation script
│
├── 📁 examples/
│   ├── TourGrpcClient.php        # Client usage example
│   └── TourGrpcServer.php        # Server implementation example
│
└── 📁 tests/                     # Unit tests
    └── .gitkeep
```

## 🔄 Workflow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                     DEVELOPMENT WORKFLOW                     │
└─────────────────────────────────────────────────────────────┘

1. DEFINE PROTO
   ┌──────────────────┐
   │  Edit .proto     │
   │  in protos/      │
   └────────┬─────────┘
            │
            ▼
2. GENERATE CODE
   ┌──────────────────┐
   │  Run:            │
   │  composer        │
   │  generate        │
   └────────┬─────────┘
            │
            ▼
3. GENERATED FILES
   ┌──────────────────┐
   │  PHP classes     │
   │  in src/         │
   └────────┬─────────┘
            │
            ▼
4. USE IN PROJECTS
   ┌──────────────────┐
   │  Install via     │
   │  Composer        │
   └────────┬─────────┘
            │
            ▼
5. IMPLEMENT
   ┌──────────────────────────────────┐
   │  Service A        Service B      │
   │  (Server)         (Client)       │
   │                                  │
   │  Implements       Calls          │
   │  Interface        Service A      │
   └──────────────────────────────────┘
```

## 🎯 Key Concepts

### 1. Proto Files (Source of Truth)

- Located in `protos/`
- Written in Protocol Buffers language
- Define services, messages, and data structures
- **YOU EDIT THESE**

### 2. Generated Code

- Located in `src/`
- Auto-generated from proto files
- **NEVER EDIT MANUALLY**
- Regenerate when proto files change

### 3. Namespacing Convention

```
Proto Package:    tour.v1
PHP Namespace:    Travel\Proto\Tour\V1
Directory:        src/Travel/Proto/Tour/V1/
```

### 4. Service Types

#### Server (Service Implementation)

```php
class TourService implements TourServiceInterface {
    public function GetTourById($request) {
        // Your business logic here
        return new TourResponse();
    }
}
```

#### Client (Service Consumer)

```php
$client = new TourServiceClient('host:port', $options);
$response = $client->GetTourById($request);
```

## 📋 Common Commands

```bash
# Setup
make setup              # Complete setup
make install            # Install dependencies only
make check-protoc       # Check if protoc is installed

# Development
make generate           # Generate PHP from proto
make clean              # Clean generated files
make watch              # Auto-regenerate on changes

# Testing
make test               # Run tests

# Help
make help               # Show all commands
```

## 🔌 Integration with Laravel Services

### Service A (Tour Service - Server)

```
Laravel Service A
├── app/
│   ├── Grpc/
│   │   └── Services/
│   │       └── TourService.php    ← Implements interface
│   └── Models/
│       └── Tour.php
└── composer.json
    └── require: "travel/grpc-proto"
```

### Service B (Booking Service - Client)

```
Laravel Service B
├── app/
│   └── Services/
│       └── TourClient.php         ← Uses client
└── composer.json
    └── require: "travel/grpc-proto"
```

## 🌟 Benefits

1. **Single Source of Truth**: One proto definition for all services
2. **Type Safety**: Strongly typed messages and services
3. **Version Control**: Easy to version and maintain
4. **Code Reuse**: Share code across multiple services
5. **Consistency**: Same data structures everywhere
6. **Documentation**: Proto files serve as documentation

## 🚀 Next Steps

1. ✅ **Install dependencies**: `make install`
2. ✅ **Generate code**: `make generate`
3. ✅ **Review examples**: Check `examples/` directory
4. ✅ **Integrate**: Add to your Laravel projects
5. ✅ **Develop**: Add more services as needed

## 📚 Documentation Files

- **[README.md](README.md)**: Complete documentation
- **[SETUP.md](SETUP.md)**: Quick setup guide
- **[CONTRIBUTING.md](CONTRIBUTING.md)**: Development guidelines
- **This file**: Structure overview

## 🆘 Support

For questions or issues:

1. Check the documentation
2. Review examples
3. Contact the Travel Platform team
