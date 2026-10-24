# Blog CMS (beginner PHP)

A public blog with category pages, search, pagination, and an XML sitemap, plus an admin panel for writing, publishing, and deleting posts. Plain PHP 8.3, MySQL, PDO. No framework.

## Setup

1. Create the database and tables, then load the sample content:

       mysql -u root -p < sql/schema.sql
       mysql -u root -p < sql/seed.sql

2. Copy the config file and edit the database login:

       cp config.example.php config.php

3. Create the admin account (password must be at least 12 characters):

       php bin/create-admin.php you@example.com "a-long-password-here"

4. Start the built-in server from the `public` folder:

       cd public
       php -S localhost:8000

5. Visit http://localhost:8000 for the blog and http://localhost:8000/admin/login.php for the admin panel.

`base_url` in `config.php` must match the address you use, because links and the sitemap are built from it.

## Folder guide

- `public/` is the only web-visible folder. Each page is thin: it reads the request, calls classes, and renders a template.
- `src/` holds the classes. Each has one job. Only `Post.php`, `Category.php`, and `Auth.php` run SQL, and they do so with prepared statements.
- `templates/` holds shared HTML pieces (header, footer, post list, pagination, admin navigation).
- `sql/` holds the schema and sample data.
- `bin/create-admin.php` creates or updates the single admin login from the command line.

## Security basics

- Every query uses prepared statements.
- Passwords are stored with `password_hash` and checked with `password_verify`.
- All output passes through `htmlspecialchars` (the `e()` helper).
- Admin forms carry a CSRF token.
- Deletes and logout use POST forms, and deletes ask for confirmation.
