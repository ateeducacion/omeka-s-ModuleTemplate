# Omeka S Module Template

This repository provides a template to help you quickly get started building custom modules for [Omeka S](https://omeka.org/s/). It includes a basic file structure, example configuration, and guidance for extending Omeka functionality following its best practices.


## 📁 Structure
```text
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
```

## Usage
  ## 1. Clone This Template
  - git clone https://github.com/your-org/omeka-s-module-template.git YourModule
  - cd YourModule
  ## 2. Rename Module
  - Replace all occurrences of YourModule with your actual module name.
  - Update the namespace in src/Module.php and other PHP files.
  - Edit config/module.ini to match your module’s name, version, and description.
    
## Requirements

- Omeka S 4.x or later

## License

This module is published under the [GNU GPLv3](LICENSE) license.

