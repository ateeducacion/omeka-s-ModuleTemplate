# Makefile to facilitate the use of Docker and testing

# Define SED_INPLACE based on the operating system
ifeq ($(shell uname), Darwin)
  SED_INPLACE = sed -i ''
else
  SED_INPLACE = sed -i
endif

# Detect the operating system
ifeq ($(OS),Windows_NT)
    # We are on Windows
    ifdef MSYSTEM
        # MSYSTEM is defined, we are in MinGW or MSYS
        SYSTEM_OS := unix
    else ifdef CYGWIN
        # CYGWIN is defined, we are in Cygwin
        SYSTEM_OS := unix
    else
        # Not in MinGW or Cygwin
        SYSTEM_OS := windows

    endif
else
    # Not Windows, assuming Unix
    SYSTEM_OS := unix
endif

# Check if Docker is running
check-docker:
ifeq ($(SYSTEM_OS),windows)
	@echo "Detected system: Windows (cmd, powershell)"
	@docker version > NUL 2>&1 || (echo. & echo Error: Docker is not running. Please make sure Docker is installed and running. & echo. & exit 1)
else
	@echo "Detected system: Unix (Linux/macOS/Cygwin/MinGW)"	
	@docker version > /dev/null 2>&1 || (echo "" && echo "Error: Docker is not running. Please make sure Docker is installed and running." && echo "" && exit 1)
endif

# Start Docker containers in interactive mode
up: check-docker
	docker compose up --remove-orphans

# Start Docker containers in background mode (daemon)
upd: check-docker
	docker compose up --detach --remove-orphans

# Stop and remove Docker containers
down: check-docker
	docker compose down

# Pull the latest images from the registry
pull: check-docker
	docker compose -f docker-compose.yml pull

# Build or rebuild Docker containers
build: check-docker
	docker compose build

# Run the linter to check PHP code style
lint: deps-update
	"vendor/bin/phpcs" . --standard=PSR2 --ignore=vendor/,assets/,node_modules/,tests/js/,tests/ --colors --extensions=php

# Automatically fix PHP code style issues
fix: deps-update
	"vendor/bin/phpcbf" . --standard=PSR2 --ignore=vendor/,assets/,node_modules/,tests/js/,tests/ --colors --extensions=php

# Open a shell inside the omekas container
shell: check-docker
	docker compose exec omekas sh

# Clean up and stop Docker containers, removing volumes and orphan containers
clean: check-docker
	docker compose down -v --remove-orphans

# Generate the ModuleTemplate-X.X.X.zip package
package:
	@if [ -z "$(VERSION)" ]; then \
		echo "Error: VERSION not specified. Use 'make package VERSION=1.2.3'"; \
		exit 1; \
	fi
	@echo "Updating version to $(VERSION) in module.ini..."
	$(SED_INPLACE) 's/^\([[:space:]]*version[[:space:]]*=[[:space:]]*\).*$$/\1"$(VERSION)"/' config/module.ini
	@echo "Creating ZIP archive: ModuleTemplate-$(VERSION).zip..."
	composer archive --format=zip --file="ModuleTemplate-$(VERSION)"
	@echo "Restoring version to 0.0.0 in module.ini..."
	$(SED_INPLACE) 's/^\([[:space:]]*version[[:space:]]*=[[:space:]]*\).*$$/\1"0.0.0"/' config/module.ini

# Generate .pot template from translate() and // @translate
generate-pot:
	@mkdir -p language
	@echo "Extracting strings marked with // @translate..."
	@php vendor/zerocrates/extract-tagged-strings/extract-tagged-strings.php > language/tagged.pot || true
	@echo "Attempting to extract translate() calls with xgettext (if available)..."
	@if command -v xgettext >/dev/null 2>&1; then \
	  find . -path ./vendor -prune -o \( -name '*.php' -o -name '*.phtml' \) -print > language/.files.list; \
	  xgettext \
	      --language=PHP \
	      --from-code=utf-8 \
	      --keyword=translate \
	      --keyword=translatePlural:1,2 \
	      --files-from=language/.files.list \
	      --output=language/xgettext.pot || true; \
	  rm -f language/.files.list; \
	else \
	  echo "xgettext not found, skipping translate() extraction"; \
	fi
	@echo "Building language/template.pot..."
	@if command -v msgcat >/dev/null 2>&1; then \
	  if [ -f language/xgettext.pot ] && [ -s language/xgettext.pot ]; then \
	    msgcat language/xgettext.pot language/tagged.pot --use-first -o language/template.pot; \
	  else \
	    cp language/tagged.pot language/template.pot; \
	  fi; \
	else \
	  echo "msgcat not found, copying tagged.pot as template.pot"; \
	  cp language/tagged.pot language/template.pot; \
	fi
	@rm -f language/xgettext.pot language/tagged.pot
	@echo "Generated language/template.pot"



# Update all .po files from .pot template
update-po:
	@echo "Updating translation files..."
	@find language -name "*.po" | while read po; do \
		echo "Updating $$po..."; \
		msgmerge --update --backup=off "$$po" language/template.pot; \
	done


# Check for untranslated strings
check-untranslated:
	@echo "Checking untranslated strings..."
	@find language -name "*.po" | while read po; do \
		echo "\n$$po:"; \
		msgattrib --untranslated "$$po" | if grep -q msgid; then \
			echo "Warning: Untranslated strings found!"; exit 1; \
		else \
			echo "All strings translated!"; \
		fi \
	done

# Compile all .po files in the language directory into .mo
compile-mo:
	@echo "Compiling .po files into .mo..."
	@find language -name '*.po' | while read po; do \
		mo=$${po%.po}.mo; \
		msgfmt "$$po" -o "$$mo"; \
		echo "Compiled $$po -> $$mo"; \
	done

# Full i18n workflow: pot -> po -> mo
i18n: generate-pot update-po check-untranslated compile-mo

# Run unit tests
.PHONY: test
test: deps-update
	@echo "Running unit tests..."
	"vendor/bin/phpunit" -c test/phpunit.xml

# Display help with available commands
help:
	@echo ""
	@echo "Usage: make <command>"
	@echo ""
	@echo "Docker management:"
	@echo "  up                - Start Docker containers in interactive mode"
	@echo "  upd               - Start Docker containers in background mode (detached)"
	@echo "  down              - Stop and remove Docker containers"
	@echo "  logs              - Tail container logs"
	@echo "  ps                - Show container status"
	@echo "  build             - Build or rebuild Docker containers"
	@echo "  pull              - Pull the latest images from the registry"
	@echo "  clean             - Stop containers and remove volumes and orphans"
	@echo "  fresh             - Clean volumes and start again (fresh DB)"
	@echo "  shell             - Open a shell inside the omekas container"
	@echo ""
	@echo "Code quality:"
	@echo "  lint              - Run PHP linter (PHP_CodeSniffer)"
	@echo "  fix               - Automatically fix PHP code style issues"
	@echo ""
	@echo "Testing:"
	@echo "  test              - Run unit tests with PHPUnit"
	@echo ""
	@echo "Packaging:"
	@echo "  package           - Generate a .zip package of the module with version tag"
	@echo ""
	@echo "Data & module:"
	@echo "  import-sample     - Import sample CSV inside the container"
	@echo "  enable-module     - Enable this module inside Omeka S"
	@echo ""
	@echo "Dependencies:"
	@echo "  deps-update       - Run composer update --with-dependencies (checks composer availability)"
	@echo ""
	@echo "Translations (i18n):"
	@echo "  generate-pot      - Extract translatable strings to template.pot"
	@echo "  update-po         - Update .po files from template.pot"
	@echo "  check-untranslated- Check for untranslated strings in .po files"
	@echo "  compile-mo        - Compile .mo files from .po files"
	@echo "  i18n              - Run full translation workflow (generate, update, check, compile)"
	@echo ""
	@echo "Other:"
	@echo "  help              - Show this help message"
	@echo ""

# Set help as the default goal if no target is specified
.DEFAULT_GOAL := help

# Dependency management
.PHONY: check-composer
check-composer:
	@command -v composer >/dev/null 2>&1 || (echo "Error: composer not found in PATH. Please install Composer and try again." && exit 1)

.PHONY: deps-update
deps-update: check-composer
	@# If vendor is missing, install/update regardless of outdated status
	@if [ ! -d vendor ]; then \
	  echo "vendor/ not found. Running composer update to install dependencies..."; \
	  composer update --with-dependencies --no-interaction; \
	  exit 0; \
	fi
	@echo "Checking for outdated direct dependencies..."
	@OUTDATED=$$(composer outdated --direct --no-interaction 2>/dev/null || true); \
	if [ -z "$$OUTDATED" ] || echo "$$OUTDATED" | grep -qi "No outdated packages"; then \
	  echo "No direct dependencies outdated. Skipping composer update."; \
	else \
	  echo "Outdated direct dependencies found:"; \
	  echo "$$OUTDATED"; \
	  echo "Running composer update --with-dependencies..."; \
	  composer update --with-dependencies --no-interaction; \
	fi

.PHONY: deps-outdated
deps-outdated: check-composer
	composer outdated --direct --no-interaction

# Convenience targets
logs:
	docker compose logs -f --tail=200

ps:
	docker compose ps

fresh: clean upd

import-sample:
	@echo "Importing sample CSV inside the container..."
	docker compose exec omekas sh -lc 'php import_cli.php "$$OMEKA_CSV_IMPORT_FILE"'

enable-module:
	@echo "Enabling ModuleTemplate module inside Omeka S..."
	docker compose exec omekas sh -lc 'omeka-s-cli module:install ModuleTemplate || true'
