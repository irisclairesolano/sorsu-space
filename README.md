# SORSU Space MVP

Student-only academic support platform for SORSU (not an LMS).

## Backend (Laravel 11)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan queue:work
```

## Frontend (Next.js)

```bash
cd frontend
npm install
npm run dev
```

## Notes

- Only `@sorsu.edu.ph` emails are allowed (configure `ALLOWED_EMAIL_DOMAIN`).
- Email verification required before accessing core routes.
- AI generation runs via queue and persists JSON-only payloads.
