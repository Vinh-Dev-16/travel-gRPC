# Contributing to Travel gRPC Proto Library

## 🎯 Guidelines

### Adding New Services

1. **Create proto file** in appropriate directory:

   ```
   protos/{service_name}/v1/{service_name}.proto
   ```

2. **Follow naming conventions**:

   - Package: `{service_name}.v1`
   - PHP Namespace: `Travel\\Proto\\{ServiceName}\\V1`
   - Service names: PascalCase (e.g., `TourService`)
   - Message names: PascalCase (e.g., `GetTourByIdRequest`)
   - Field names: snake_case (e.g., `tour_id`)

3. **Generate and test**:
   ```bash
   composer generate
   ```

### Modifying Existing Services

#### Non-Breaking Changes (Safe)

- Adding new RPC methods
- Adding new optional fields to messages
- Adding new message types

#### Breaking Changes (Requires New Version)

- Removing or renaming RPC methods
- Removing or renaming fields
- Changing field types
- Changing field numbers

**For breaking changes, create a new version (v2, v3, etc.)**

### Proto Best Practices

1. **Always use proto3 syntax**

   ```protobuf
   syntax = "proto3";
   ```

2. **Include proper options**

   ```protobuf
   option php_namespace = "Travel\\Proto\\{Service}\\V1";
   option php_metadata_namespace = "Travel\\Proto\\{Service}\\V1\\Metadata";
   ```

3. **Use semantic field numbering**

   - 1-15: Most frequently used fields (1 byte encoding)
   - 16-2047: Less frequent fields (2 bytes encoding)
   - Never reuse field numbers

4. **Add comments**

   ```protobuf
   // Service for managing tour operations
   service TourService {
       // Retrieves a single tour by its ID
       rpc GetTourById (GetTourByIdRequest) returns (TourResponse);
   }
   ```

5. **Use appropriate types**
   - Timestamps: `int64` (Unix timestamp) or `google.protobuf.Timestamp`
   - Money: `double` or custom `Money` message
   - IDs: `string` (UUIDs) or `int64`

### Commit Messages

Follow conventional commits:

```
feat(tour): add GetToursByLocation RPC
fix(booking): correct field type for booking_date
docs: update README with new service examples
chore: regenerate proto files
```

### Pull Request Process

1. Update proto files
2. Run `composer generate`
3. Update documentation if needed
4. Create PR with clear description
5. Wait for review and approval

## 🔍 Code Review Checklist

- [ ] Proto syntax is valid
- [ ] Namespaces follow convention
- [ ] Field numbers are unique and not reused
- [ ] Changes are backward compatible OR new version created
- [ ] Generated code compiles without errors
- [ ] Documentation updated
- [ ] Examples provided for new services

## 📞 Questions?

Contact the Travel Platform team or open an issue.
