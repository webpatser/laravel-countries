# Changelog

All notable changes to this project will be documented in this file.

## [2.2.0] - 2026-07-14

### Fixed

- **Common names restored** (#149): the v2 data refresh had replaced common country names with official long forms for 188 countries (`US` returned "United States of America", `GR` returned "Hellenic Republic"). The `name` field now holds the common name again ("United States", "Greece"), matching v1 behavior.
- **Tests workflow in CI**: the Feature test suite directory was empty and untracked, causing Pest to exit with an error on every CI run. A Feature test suite now exists.

### Added

- **`full_name` field**: every country now carries its official name ("United States of America", "Hellenic Republic", "Kingdom of the Netherlands") alongside the common `name`. Available in the JSON data, the `Country` model, search, and `country_code_from_name()` lookups (both name variants resolve).
- **Migration stub**: adds a nullable `full_name` column. Existing `data_source = database` installs need to add this column (or re-run `countries:install`) to get the new field.

### Changed

- **`Country::$full_name` accessor renamed**: the model accessor that appended the flag emoji to the name is now `$country->name_with_flag`; `$country->full_name` returns the official name from the data. `toSelectOption()` labels keep using name + flag and are unaffected.
- README badge now lists Laravel 13 support (#150, thanks @MrYamous).

## [Unreleased] - 2024

### Major Update - Laravel 11/12 & PHP 8.2+ Compatibility

This project was long overdue for an update. We've completely modernized the codebase for Laravel 11/12 and PHP 8.2+ compatibility.

### ⚠️ Breaking Changes

**This version is NOT backward compatible with the old Laravel 5.5 version.**

- Minimum PHP version is now 8.2
- Laravel 11.x and 12.x support only
- Complete restructure of codebase architecture
- Updated namespace structure and class organization

### ✨ New Features

- **Modern Laravel Support**: Full compatibility with Laravel 11.x and 12.x
- **Enhanced Country Data**: Updated countries list with current ISO standards
- **Laravel Casts**: New custom cast classes for country data types
- **Validation Rules**: Built-in Laravel validation rules for country codes, currencies, and regions
- **HTTP Middleware**: Country-based localization and validation middleware
- **Model Traits**: Eloquent traits for country relationships
- **Collection Macros**: Extended Collection and String macros for country operations
- **Helper Functions**: Global helper functions for common country operations
- **Artisan Commands**: Streamlined installation and migration commands

### 🔄 Updated

- **Countries Database**: Refreshed with latest country information
- **Currency Data**: Updated currency codes and information
- **Regional Classifications**: Modern regional groupings
- **Flag Support**: Enhanced flag emoji support
- **Documentation**: Complete rewrite of documentation and examples

### 🏗️ Architecture Changes

- Moved from `Webpatser\Countries` to organized sub-namespaces
- Added proper PSR-4 autoloading structure
- Implemented modern Laravel service provider patterns
- Added comprehensive test coverage structure
- Modular design with separated concerns

### 📦 Dependencies

- PHP ^8.2
- Laravel ^11.0|^12.0
- illuminate/support ^11.0|^12.0
- illuminate/database ^11.0|^12.0

### 📖 Documentation

Complete documentation available at: https://documentation.downsized.nl/laravel-countries