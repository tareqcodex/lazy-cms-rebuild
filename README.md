# Lazy CMS Rebuild v3.0.3

A powerful, modular, and easy-to-use CMS package for Laravel applications.

## 🚀 Installation

To install the package in a fresh Laravel project, follow these steps:

1. **Require the package via Composer:**
   ```bash
   composer require tareqcodex/lazy-cms-rebuild
   ```

2. **Run the installation command:**
   This command will publish assets, migrations, and prepare the CMS environment.
   ```bash
   php artisan lazy-cms:install
   ```

3. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

4. **Seed Default Data:**
   To get the default admin, roles, and menus, run:
   ```bash
   php artisan lazy-cms:seed
   ```

---

## 🔄 Updating to v3.0.0+

If you are upgrading from v2.x to v3.x, please note that we have consolidated all migrations for a cleaner structure. **This is a breaking change.**

1. **Update the version in `composer.json`:**
   Set the version to `^3.0`.

2. **Update the package:**
   ```bash
   composer update tareqcodex/lazy-cms-rebuild
   ```

3. **Refresh Database (Required for v3.0.0+):**
   ```bash
   php artisan migrate:fresh
   php artisan lazy-cms:seed
   ```

---

## 🛠 Features

- **Consolidated Migrations:** Only 21 clean migration files (reduced from 46).
- **Dynamic Post Types (CPT):** Create and manage custom post types from the dashboard.
- **Media Manager:** Advanced media management with dimension tracking.
- **Role-Based Access Control (RBAC):** Manage roles and permissions easily.
- **Activity Logs:** Track user actions with IP and geographic data.
- **Security:** Built-in login throttling and IP blocking.
- **Custom Taxonomies:** Hierarchical categories and tags for any post type.
- **SEO Ready:** Built-in SEO meta fields for all posts.

---

## 🔑 Default Credentials

After running `php artisan lazy-cms:seed`, you can login at `/admin-login` with:
- **Email:** `admin@admin.com`
- **Password:** `password`

---

## 📜 Commands Summary

| Command | Description |
| :--- | :--- |
| `php artisan lazy-cms:install` | Publishes assets and prepares the package. |
| `php artisan lazy-cms:seed` | Seeds default admin, roles, and menus. |
| `php artisan lazy-cms:theme-init` | Initializes the default theme. |

---

Developed by **Tareq Codex**