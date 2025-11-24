#!/bin/bash

# Script to generate PHP code from proto files
# This script compiles all .proto files into PHP classes

set -e

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Get the directory where this script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

# Directories
PROTO_DIR="$PROJECT_ROOT/protos"
OUTPUT_DIR="$PROJECT_ROOT/src"

echo -e "${YELLOW}Starting proto compilation...${NC}"

# Check if protoc is installed
if ! command -v protoc &> /dev/null; then
    echo -e "${RED}Error: protoc is not installed${NC}"
    echo "Please install protoc (Protocol Buffers compiler)"
    echo "  - Ubuntu/Debian: sudo apt-get install protobuf-compiler"
    echo "  - macOS: brew install protobuf"
    echo "  - Or download from: https://github.com/protocolbuffers/protobuf/releases"
    exit 1
fi

# Check if grpc_php_plugin is available
if ! command -v grpc_php_plugin &> /dev/null; then
    echo -e "${YELLOW}Warning: grpc_php_plugin not found${NC}"
    echo "gRPC services will not be generated. Only message classes will be created."
    echo "To install grpc_php_plugin, follow: https://grpc.io/docs/languages/php/quickstart/"
    GRPC_PLUGIN=""
else
    GRPC_PLUGIN="--grpc_out=$OUTPUT_DIR --plugin=protoc-gen-grpc=$(which grpc_php_plugin)"
fi

# Clean the output directory (except .gitkeep if exists)
echo -e "${YELLOW}Cleaning output directory...${NC}"
if [ -d "$OUTPUT_DIR" ]; then
    find "$OUTPUT_DIR" -type f ! -name '.gitkeep' -delete
    find "$OUTPUT_DIR" -type d -empty -delete
fi

# Create output directory if it doesn't exist
mkdir -p "$OUTPUT_DIR"

# Find all .proto files and compile them
echo -e "${YELLOW}Compiling proto files...${NC}"

PROTO_FILES=$(find "$PROTO_DIR" -name "*.proto")

if [ -z "$PROTO_FILES" ]; then
    echo -e "${RED}No .proto files found in $PROTO_DIR${NC}"
    exit 1
fi

for proto_file in $PROTO_FILES; do
    echo -e "${GREEN}Compiling: $proto_file${NC}"
    
    # Compile the proto file
    protoc \
        --proto_path="$PROTO_DIR" \
        --php_out="$OUTPUT_DIR" \
        $GRPC_PLUGIN \
        "$proto_file"
done

echo -e "${GREEN}✓ Proto compilation completed successfully!${NC}"
echo -e "${GREEN}Generated files are in: $OUTPUT_DIR${NC}"

# List generated files
echo -e "\n${YELLOW}Generated files:${NC}"
find "$OUTPUT_DIR" -type f -name "*.php" | while read file; do
    echo "  - ${file#$PROJECT_ROOT/}"
done
