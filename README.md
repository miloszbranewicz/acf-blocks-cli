# acf-blocks-cli

A CLI tool for quickly generating ACF (Advanced Custom Fields) blocks for WordPress.

This tool creates a block scaffold with all necessary files: `block.json`, `register.php`, PHP template, and optionally SCSS and `fields.json`.

---

## Requirements

- PHP >= 8.1 
- Composer  
- WordPress with ACF Pro (required for blocks to work)

---

## Installation

Install the package locally or globally via Composer:

```bash
composer require mbran/acf-blocks-cli
```

## Usage

Run the CLI using 
```bash
php vendor/bin/acf-blocks-cli make:block
```
