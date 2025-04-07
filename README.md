# Omeka S Module Template

This repository provides a template to help you quickly get started building custom modules for [Omeka S](https://omeka.org/s/). It includes a basic file structure, example configuration, and guidance for extending Omeka functionality following its best practices.

## 📁 Structure

YourModule/
├── config/
│   └── module.ini               # Module metadata
├── src/
│   ├── Module.php               # Main module class (entry point)
│   └── [YourNamespace]/
│       └── [Classes].php        # Your custom PHP classes (e.g., Forms, Listeners, Helpers)
├── view/
│   └── your-module/             # Templates for your module (if needed)
├── asset/                       # Static assets (JS, CSS, images)
│   └── js/
├── test/                        # Unit or integration tests
└── README.md                    # Documentation


## Requirements

- Omeka S 4.x or later

## License

This module is published under the [GNU GPLv3](LICENSE) license.

