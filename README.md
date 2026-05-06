# Lazy CMS Rebuild v3.6.0

A powerful, modular, and easy-to-use CMS package for Laravel applications with built-in multi-language support and a WordPress-like hook system.

## 🚀 Installation

To install the package in a fresh Laravel project, follow these steps:

1. **Require the package via Composer:**
   ```bash
   composer require tareqcodex/lazy-cms-rebuild
   ```

2. **Run the installation command:**
   ```bash
   php artisan lazy-cms:install
   ```
   *This command handles migrations, asset publishing, storage linking, and default user creation.*

---

## 🔄 Updating

When you update the package via composer, run the following command to sync migrations and refresh assets:

```bash
php artisan lazy-cms:update
```
*This command automates: `migrate`, `vendor:publish (force)`, and `optimize:clear`.*

---

## 🌐 Multi-Language Support

Lazy CMS supports dynamic localization. You can enable or disable multi-language support from the Admin Settings.

- **Clean URLs:** When multi-language is disabled, URLs are clean (e.g., `/my-post`).
- **ISO Prefixes:** When enabled, URLs include the language code (e.g., `/en/my-post`, `/bn/আমার-পোস্ট`).
- **Dynamic Admin UI:** The language selection metabox automatically hides when multi-language is disabled to keep the UI clean.

---

## 🛠 Features

- **Consolidated Migrations:** Clean and optimized database structure.
- **Dynamic Post Types (CPT):** Create custom post types from the dashboard.
- **Advanced Hook System:** WordPress-like Action and Filter hooks.
- **Headless Mode:** Full REST API support for React, Vue, and Mobile apps.
- **Theme Overrides:** High priority for local `resources/views/themes` files.
- **Role-Based Access Control:** Manage user permissions effortlessly.

---

## ⚓ Hook System

### Actions & Filters
Standard usage for modifying core logic or injecting content.

### Removing Hooks
```php
remove_lazy_action('tag_name', 'callback', $priority);
remove_lazy_filter('tag_name', 'callback', $priority);
```

---

## 🎨 Theme Development

### 📂 Theme Structure
Your themes should be located in `resources/views/themes/{theme-name}/`.
- `index.blade.php`: Primary template.
- `functions.php`: Theme-specific hooks.

### 🪄 Dynamic Admin Fields
Inject fields into settings pages:
```php
add_lazy_filter('lazy_general_settings_fields', function($fields) {
    $fields['my_field'] = ['type' => 'text', 'label' => 'My Label'];
    return $fields;
});
```

---

## 📜 Commands Summary

| Command | Description |
| :--- | :--- |
| `php artisan lazy-cms:install` | Full installation (Migrations, Assets, User). |
| `php artisan lazy-cms:update` | Post-update sync (Migrations, Assets, Cache). |
| `php artisan lazy-cms:seed` | Seeds default demo data. |
| `php artisan make:lazy-page` | Scaffolds a new dashboard page. |

---

Developed by **Tareq Codex**