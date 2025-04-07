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
lint:
	"vendor/bin/phpcs" . --standard=PSR2 --ignore=vendor/,assets/,node_modules/,tests/js/,tests/ --colors --extensions=php

# Automatically fix PHP code style issues
fix:
	"vendor/bin/phpcbf" . --standard=PSR2 --ignore=vendor/,assets/,node_modules/,tests/js/,tests/ --colors --extensions=php

# Open a shell inside the omekas container
shell: check-docker
	docker compose exec omekas bash

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
	@echo "Extracting strings using xgettext..."
	find . -path ./vendor -prune -o \( -name '*.php' -o -name '*.phtml' \) -print \
	| xargs xgettext \
	    --language=PHP \
	    --from-code=utf-8 \
	    --keyword=translate \
	    --keyword=translatePlural:1,2 \
	    --output=language/xgettext.pot
	@echo "Extracting strings marked with // @translate..."
	"vendor/zerocrates/extract-tagged-strings/extract-tagged-strings.php" > language/tagged.pot
	@echo "Merging xgettext.pot and tagged.pot into template.pot..."
	msgcat language/xgettext.pot language/tagged.pot --use-first -o language/template.pot
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
test:
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
	@echo "  build             - Build or rebuild Docker containers"
	@echo "  pull              - Pull the latest images from the registry"
	@echo "  clean             - Stop containers and remove volumes and orphans"
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
