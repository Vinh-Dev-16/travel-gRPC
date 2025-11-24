.PHONY: help install generate clean test

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Install composer dependencies
	composer install

generate: ## Generate PHP code from proto files
	@echo "Generating PHP code from proto files..."
	bash scripts/generate.sh

clean: ## Clean generated files
	@echo "Cleaning generated files..."
	find src -type f ! -name '.gitkeep' -delete
	find src -type d -empty -delete
	@echo "Done!"

test: ## Run tests
	composer test

check-protoc: ## Check if protoc is installed
	@which protoc > /dev/null || (echo "❌ protoc not found. Please install Protocol Buffers compiler." && exit 1)
	@echo "✅ protoc found: $$(protoc --version)"

check-grpc: ## Check if grpc_php_plugin is installed
	@which grpc_php_plugin > /dev/null || (echo "⚠️  grpc_php_plugin not found. Service clients won't be generated." && exit 1)
	@echo "✅ grpc_php_plugin found"

setup: check-protoc install generate ## Complete setup (check deps, install, generate)
	@echo ""
	@echo "✅ Setup complete!"
	@echo ""
	@echo "Next steps:"
	@echo "  1. Review generated code in src/"
	@echo "  2. Add this library to your Laravel project"
	@echo "  3. See SETUP.md for detailed instructions"

watch: ## Watch proto files and regenerate on changes (requires inotify-tools)
	@which inotifywait > /dev/null || (echo "Please install inotify-tools: sudo apt-get install inotify-tools" && exit 1)
	@echo "Watching proto files for changes..."
	@while true; do \
		inotifywait -r -e modify,create,delete protos/ && \
		echo "Change detected, regenerating..." && \
		make generate; \
	done
