# Saloon Booker App API

Backend API for the Saloon Booker mobile app demo. This project provides authentication, salon discovery, booking, favorites, and profile management endpoints used by the mobile client.

## What Is Inside This Repo

- Laravel 12 API backend with token-based auth (`Laravel Sanctum`)
- OTP login flow (`request OTP` -> `verify OTP` -> receive API token)
- Domain models for salons, services, providers, appointments, reviews, and favorites
- Seeders for demo data so the mobile app can be tested quickly
- Optional admin tooling dependencies (`Filament`, media library plugins)

## Core API Areas

All main app endpoints are defined in `routes/api.php`.

- `POST /api/auth/otp/request` - Request OTP for a phone number
- `POST /api/auth/otp/verify` - Verify OTP and login/register user
- `POST /api/auth/logout` - Logout current user (requires token)
- `GET /api/home` - Home screen data
- `GET /api/explore` - Explore/discovery feed
- `GET /api/search` - Search salons/services
- `GET /api/salons/{id}` - Salon detail page
- `POST /api/appointments` - Create appointment booking
- `GET /api/bookings` - List user bookings
- `GET/POST/DELETE /api/favorites` - Manage favorite salons
- `GET/PATCH /api/profile` - Read/update user profile
- `GET /api/locations/*` - Place lookup and reverse lookup helpers

> Most app endpoints are protected with `auth:sanctum`.

## Project Structure (High Level)

- `app/Http/Controllers/Api` - Mobile app API controllers
- `app/Models` - Eloquent models (User, Salon, Appointment, etc.)
- `database/migrations` - Database schema
- `database/seeders` - Demo/test data seeders
- `routes/api.php` - Public + authenticated API routes

## Requirements

- PHP `^8.2`
- Composer
- Database (MySQL/PostgreSQL/SQLite supported by Laravel)
- Node.js + npm (only needed for frontend assets/dev scripts)

## Local Setup

1. Clone the repository.
2. Install dependencies:

   ```bash
   composer install
   ```

3. Create environment file:

   ```bash
   cp .env.example .env
   ```

4. Generate app key:

   ```bash
   php artisan key:generate
   ```

5. Configure database in `.env`.
6. Run migrations and seed demo data:

   ```bash
   php artisan migrate --seed
   ```

7. Start the API server:

   ```bash
   php artisan serve
   ```

The API will be available at `http://127.0.0.1:8000`.

## Useful Commands

```bash
# Run tests
php artisan test

# Format code (Laravel Pint)
./vendor/bin/pint

# Run app + queue + logs + vite together (project script)
composer run dev
```

## Environment Notes

- Set `GOOGLE_MAPS_API_KEY` in `.env` if location endpoints rely on Google Maps services.
- Ensure Sanctum is configured correctly when testing from a mobile app or API client.

## License

This project uses the [MIT License](https://opensource.org/licenses/MIT).
