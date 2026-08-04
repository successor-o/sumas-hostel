# SUMAS Hostel Allocation & Management System — Laravel Backend

A full Laravel 12 backend for the State University of Medical and Applied Sciences (SUMAS)
hostel portal: public marketing site, student authentication/dashboard, and an admin console —
all backed by a real database (Eloquent models, migrations, seeders) instead of mock JS data.

> **Setup status:** dependencies are already installed (`vendor/` is present via `composer install`),
> `.env` has been created with an application key, and the SQLite database has been migrated and
> seeded. You can jump straight to **Run it** below (`php artisan serve`). To rebuild from scratch:
> `composer install && cp .env.example .env && php artisan key:generate && touch database/database.sqlite && php artisan migrate --seed`.

---

## 1. Requirements

- PHP 8.2+ with the `sqlite3`, `mbstring`, `xml`, `curl`, `bcmath` extensions
- Composer 2
- Node.js is **not** required — all frontend assets (Bootstrap, Chart.js, Font Awesome) are
  loaded from CDN, and the app's own CSS/JS lives in `public/assets/`.

## 2. Install

```bash
cd sumas-laravel
composer install
cp .env.example .env      # already present, but safe to re-copy
php artisan key:generate
```

## 3. Database (SQLite by default — zero config)

```bash
touch database/database.sqlite
php artisan migrate --seed
```

This creates all tables and seeds:
- 2 admin accounts
- 6 hostel blocks (Male A/B/C, Female A/B, Postgraduate Annex) matching the real SUMAS campus
- ~240 rooms across those blocks
- 7 "named" demo students (Chidera Nwosu, Emeka Okafor, etc.) plus enough auto-generated
  students to realistically fill each hostel to its target occupancy, so every dashboard,
  chart, and report reflects real relational data — not hardcoded numbers.

**If you'd rather use MySQL:** comment out the `DB_CONNECTION=sqlite` lines in `.env` and
uncomment the MySQL block, then create the database yourself before migrating.

## 4. Storage symlink (for uploaded hostel images / avatars)

```bash
php artisan storage:link
```
(A `public/storage` symlink is already present in this archive pointing at
`storage/app/public`; re-run the command above if it doesn't resolve on your OS.)

## 5. Run it

```bash
php artisan serve
```

Visit **http://localhost:8000**.

---

## 6. Demo credentials

| Role    | Login                  | Password   |
|---------|-------------------------|------------|
| Admin   | `admin@sumas.edu.ng`    | `password` |
| Admin   | `warden@sumas.edu.ng`   | `password` |
| Student | `SUMAS/22/1045` (Chidera Nwosu, already allocated a room) | `password` |
| Student | `SUMAS/23/1290` (Faith Adeyemi, application still pending) | `password` |

Any of the auto-generated placeholder students also work with password `password` — look them
up via `php artisan tinker` → `App\Models\User::inRandomOrder()->first()`.

---

## 7. What's actually wired up (not mocked)

- **Dual authentication guards** — students authenticate against the `users` table on the
  default `web` guard; admins authenticate against a separate `admins` table on an `admin`
  guard (`config/auth.php`). Sessions, CSRF, and password hashing all go through real Laravel
  auth — nothing is faked in JavaScript.
- **Registration** creates a real `users` row and redirects to login.
- **Hostel application** (`student/application`) writes a real `hostel_applications` row tied
  to the logged-in student and the current academic session (`config('sumas.session')`,
  configurable via `SUMAS_ACADEMIC_SESSION` in `.env`).
- **Admin approve/reject** updates that row's status and fires an in-app notification
  (`system_notifications` table) to the student.
- **Admin "New Allocation"** picks an approved application, dynamically loads that hostel's
  open rooms via an AJAX endpoint (`admin/allocation/available-rooms`), and on submit creates a
  real `allocations` row with an auto-assigned bed number, then flips the room's `status`
  (vacant/partial/full) and notifies the student.
- **Occupancy Monitoring, Reports, and all dashboard charts** are computed live from the
  `hostels`, `rooms`, and `allocations` tables (see `Hostel::occupancyPercent()`,
  `Admin\DashboardController`, `Admin\OccupancyController`, `Admin\ReportController`) — there is
  no hardcoded "2,150 students" marketing number anywhere in the authenticated app.
  Deleting/adding students, hostels, rooms, applications, or allocations immediately changes
  every number and chart across both dashboards.
- **Allocation slips** (student + admin) render real student/room/session data and are
  print-ready (`window.print()` + a `@media print` rule that hides the sidebar/topbar).
  There's no PDF library wired in — "print to PDF" from the browser's print dialog is the
  intended flow, matching the original "Generate/Print Slip" UI.
- **Profile photo upload, hostel image upload** — real file uploads to `storage/app/public`
  via the `public` disk, served through the `public/storage` symlink.
  **Password changes** verify the current password (`current_password` validation rule against
  the correct guard) before hashing and saving the new one.
  **Password reset** uses Laravel's built-in `Password` broker with two separate brokers/tables
  (`password_reset_tokens` for students, `admin_password_reset_tokens` for admins) — since no
  mail transport is configured (`MAIL_MAILER=log`), the reset email is written to
  `storage/logs/laravel.log` instead of actually being sent. Wire up a real mailer in `.env`
  (`MAIL_MAILER=smtp` + credentials) to send it for real; no code changes needed.
- **Contact form** persists to a `contact_messages` table (`ContactMessage` model) and is
  surfaced to the admin console via **Admin → Messages** (`Admin\MessageController`): search,
  filter by read/unread, mark messages read/unread, and delete them.
- **Settings actually persist** — student notification toggles save to a `settings` JSON column
  on the user; admin system settings (institution name, support email, academic session) save
  to a key/value `settings` table (`Setting` model) and override `config('sumas.*')` at boot,
  so every view (footer, login page, session tags) reflects the saved values immediately.

## 8. What's intentionally still a demo/no-op

A few UI placeholders remain purely for visual completeness:
- Two-factor authentication toggle on the admin Security tab is disabled (UI placeholder).
- "Account Deactivate" button on student settings is disabled (destructive action intentionally
  not wired without a proper confirmation/audit flow).

## 9. Project structure

```
app/
  Http/Controllers/
    Auth/             Student + Admin login/register/logout, shared password reset
    Student/          Dashboard, Profile, Application, Allocation, Notifications, Rules, Settings
    Admin/            Dashboard, Students, Hostels, Rooms, Applications, Allocation,
                       Occupancy, Reports, Notifications, Messages, Settings, Profile
  Models/             User (student), Admin, Hostel, Room, HostelApplication, Allocation,
                       SystemNotification, ContactMessage, Setting (key/value)
database/
  migrations/         users, admins, hostels, rooms, hostel_applications, allocations,
                       system_notifications, contact_messages, settings, password reset tables
  seeders/            AdminSeeder, HostelSeeder, RoomSeeder, UserSeeder,
                       HostelApplicationSeeder, AllocationSeeder, NotificationSeeder
  factories/          UserFactory (realistic Nigerian-style student data), AdminFactory
resources/views/
  layouts/            public.blade.php, auth.blade.php, dashboard.blade.php (shared shell)
  partials/           nav/footer (public), sidebar-student / sidebar-admin, topbar
  public/             home, about, facilities, hostels (dynamic), gallery, faq, contact
  auth/               login, register, admin-login, forgot-password, reset-password
  student/             dashboard, profile, application, allocation, notifications, rules, settings
  admin/              dashboard, students, hostels, rooms, applications, allocation,
                       occupancy, reports, notifications, messages, settings, profile
  vendor/pagination/  custom Bootstrap-styled paginator matching the design system
routes/web.php        all routes, grouped by guest/auth + guard
config/sumas.php      institution name, academic session, contact details (env-driven)
public/assets/        the original static CSS/JS/images, reused as-is
```

## 10. Tests

Feature tests cover both guards' auth flow (`AuthTest`), admin CRUD for students/hostels/rooms
and the full approve → allocate → vacate lifecycle (`AdminCrudTest`), the contact form + admin
Messages page (`MessageTest`), and student/admin settings persistence (`SettingsTest`). Run them with:

```bash
php artisan test
```

## 11. Design notes

The frontend markup, CSS, and design system are unchanged from the original static build —
this pass only replaced hardcoded/mock content with real Blade + Eloquent data and wired every
form to an actual controller action. If you delivered the static HTML version earlier, visually
this should look identical; the difference is everything now reads from and writes to a real
database.
