# Omeka S Module Template

![Screenshot of the module](https://raw.githubusercontent.com/ateeducacion/omeka-s-ModuleTemplate/refs/heads/main/.github/assets/screenshot.png)

This repository provides a minimal, modern template for building custom modules for Omeka S. It includes a Docker-based dev environment, packaging helpers, i18n utilities, and a clean starting structure.

## Quick Start (Docker)

- Requirements: Docker Desktop 4+, Make
- Start stack: `make up` then open `http://localhost:8080`
- Stop stack: `make down`

This template uses the image `erseco/alpine-omeka-s:develop`, which boots Omeka S with sensible defaults and tooling.

### Sample Data

- A ready-to-use CSV lives at `data/sample_data.csv`.
- On first boot, the container will automatically import it if present.
- Import manually any time: `make import-sample`

Users created automatically:
- `admin@example.com` (global_admin) password: `PLEASE_CHANGEME`
- `editor@example.com` (editor) password: `1234`

### Useful Make Targets

- `make up` / `make upd`: Run in foreground/background
- `make down` / `make clean`: Stop, optionally remove volumes
- `make logs`: Tail container logs
- `make shell`: Shell into the `omekas` container
- `make import-sample`: Import the CSV at `$OMEKA_CSV_IMPORT_FILE`
- `make enable-module`: Enable this module inside Omeka S
- `make test`: Run PHPUnit tests
- `make package VERSION=x.y.z`: Build a distributable ZIP

Run `make help` to see all targets.

## Project Structure

```text
ModuleTemplate/
├── config/
│   └── module.ini               # Module metadata
├── src/
│   └── Module.php               # Main module class (entry point)
├── view/                        # Templates (optional)
├── asset/                       # Static assets (JS, CSS, images)
├── language/                    # Translations (.po/.mo)
├── test/                        # Unit tests and bootstrap
├── data/sample_3d_data.csv      # Optional sample dataset for CSVImport
├── docker-compose.yml           # Dev stack using alpine-omeka-s:develop
├── Makefile                     # Dev helpers (docker, i18n, tests, packaging)
└── README.md                    # This file
```

## Customizing the Template

- Rename namespaces in `composer.json` and under `src/` to your module’s name.
- Update `config/module.ini` with your module’s metadata.
- Adjust `docker-compose.yml` to mount your module directory name in `/var/www/html/volume/modules/<YourModule>`.

## Requirements

- Omeka S 4.x or later
- PHP 7.4+ for development (module code can target higher, adjust `composer.json` accordingly)

## License

Published under the GNU GPLv3 license. See [LICENSE](LICENSE).

