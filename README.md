# Sistem Pembayaran Gaji Pegawai BPRS Lampung

Project skeleton for a payroll system using Laravel 10, Blade, and TiDB (MySQL compatible).

## Tech stack
- Backend: Laravel 10
- Frontend: Blade + Bootstrap 5
- Database: TiDB (MySQL compatible)
- Auth: Laravel Sanctum / Breeze (not scaffolded here)

## Setup
1. Copy `.env.example` to `.env` and set your TiDB credentials and `APP_KEY` (`php artisan key:generate`).
2. Install dependencies: `composer install`.
3. Run migrations: `php artisan migrate`.
4. (Optional) Seed test data.
5. Serve: `php artisan serve` and open `http://localhost:8000`.

Note: TiDB may require `DB_SSL_CA` path to the CA certificate.

## What is included
- Migrations and Eloquent models for employees, attendances, salaries, and salary_details.
- Controllers for Employee, Attendance, Salary and Dashboard.
- `SalaryCalculationService` with a basic calculation algorithm. This should be adapted to local policy and optimized for batch operations.
- Blade templates (layout and simple pages) for CRUD and salary views.

## Next steps (recommended)
- Implement authentication (Breeze/Sanctum) and role-based access control.
- Implement queue jobs for heavy batch salary calculations and export tasks.
- Add exports (PDF/Excel) using `barryvdh/laravel-dompdf` and `maatwebsite/excel`.
- Add unit tests and feature tests (one test scaffolded for salary calculation in `tests/Unit`).
- Configure backups and monitoring for reliability.

## Performance and Scaling notes
- Use eager loading to avoid N+1 queries when listing employees and salaries.
- Add indexes to `attendances` and `salaries` on `employee_id` and `periode` (already included in migrations).
- For batch salary processing of 500 employees, run calculations in queue workers and process in chunks.

## Contact
This skeleton was generated to match the requested structure. Adapt business rules, validations and security hardening before production.
