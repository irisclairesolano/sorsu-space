# SORSU Space API (Laravel 11)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

## Queue

```bash
php artisan queue:work
```

## Configuration

- `ALLOWED_EMAIL_DOMAIN` controls the allowed student email suffix (default `sorsu.edu.ph`).
- `OPENAI_API_KEY` must be set for AI generation jobs.

## Storage

Local dev uses the default `local` disk. Configure `FILESYSTEM_DISK` and S3-compatible settings for production.
