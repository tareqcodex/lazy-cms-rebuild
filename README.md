# Lazy CMS Rebuild v3.1.4

A powerful, modular, and easy-to-use CMS package for Laravel applications.

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

3. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

4. **Seed Default Data:**
   ```bash
   php artisan lazy-cms:seed
   ```

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

### 1. Actions & Filters
Standard usage for modifying core logic or injecting content.

### 2. Removing Hooks
```php
remove_lazy_action('tag_name', 'callback', $priority);
remove_lazy_filter('tag_name', 'callback', $priority);
```

---

## 🌐 Headless CMS & REST API

Lazy CMS provides a built-in REST API to power modern frontend frameworks.

### 📍 API Endpoints
- `GET /api/v1/posts`: List all published posts.
- `GET /api/v1/posts/{slug}`: Get single post details.
- `GET /api/v1/settings`: Get public site settings.
- `GET /api/v1/menus`: Get navigation menus.

### 🪄 Filtering API Data
You can use hooks in your theme's `functions.php` to modify API responses:

```php
// Add custom field to API output
add_lazy_filter('lazy_api_post_data', function($data, $post) {
    $data['custom_key'] = 'Some dynamic value';
    return $data;
});
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
| `php artisan lazy-cms:install` | Prepares the package environment. |
| `php artisan lazy-cms:seed` | Seeds default data. |
| `php artisan lazy-cms:theme-init` | Initializes the default theme. |

---

Developed by **Tareq Codex**