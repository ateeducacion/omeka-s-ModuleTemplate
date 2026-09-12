---
name: module-scaffold
description: "Adapt the module template namespace, configuration, lifecycle, tests, or packaging."
---

# Module template adaptation

Inspect `Module.php`, `config/module.ini`, `config/module.config.php`, `composer.json`, `src/`,
`test/`, and the package recipe together. This repository is a reusable skeleton.

- Keep folder, PHP namespace, autoload mappings, service registrations and test bootstrap consistent
  when adapting the template. Update module metadata and release ZIP top-level naming as one change.
- Use existing Omeka lifecycle, settings and config-form patterns. Merge shared settings such as
  whitelists instead of replacing other modules' entries; uninstall only settings owned here.
- Keep demonstration configuration small and recognizable. Do not add speculative services,
  database tables, or dependencies merely to showcase an architecture.
- Verify actual event identifiers and payloads against the supported Omeka version. Navigation
  visibility does not replace controller/API authorization; validate state-changing inputs.

Run PHPCS and PHPUnit through the documented commands. For packaging changes, inspect an isolated ZIP:
correct module root, config files present, and no agent tooling, tests or development dependencies.
Do not run the version-mutating package recipe in a dirty checkout.
