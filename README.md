# Notebook: secure login + MySQL website

## Run it locally
1. Install XAMPP (or any PHP 8+ and MySQL setup) and start Apache and MySQL.
2. Copy this folder into `htdocs/notebook`.
3. Open phpMyAdmin, go to Import, and run `schema.sql`.
4. Edit DB credentials in `config.php` if yours differ from root / no password.
5. Visit http://localhost/notebook/

## Or with PHP's built-in server
    mysql -u root -p < schema.sql
    php -S localhost:8000

## Security features
- Passwords hashed with `password_hash` / `password_verify`
- Prepared statements everywhere (SQL injection protection)
- Output escaped with `htmlspecialchars` (XSS protection)
- CSRF tokens on every form
- Session ID regenerated on login; HttpOnly cookies
- Notes are scoped by `user_id`, so users can't read or delete each other's data

## Responsive
Mobile-first CSS with a collapsing menu under 600px, fluid type, and dark mode.
