# BusinessX

BusinessX is a Laravel 12.68 application serving the existing BusinessX website mockup through Blade templates, named routes, and shared layouts.

## Requirements

- PHP 8.2 or newer with the extensions required by Laravel
- Composer
- SQLite (the default local connection) or another database configured in `.env`

## Run locally

```powershell
composer install
if (!(Test-Path .env)) { Copy-Item .env.example .env }
php artisan key:generate
if (!(Test-Path database/database.sqlite)) { New-Item -ItemType File database/database.sqlite | Out-Null }
php artisan migrate
php artisan serve
```

Open the URL shown by `artisan serve` (usually `http://127.0.0.1:8000`).

For XAMPP/Apache, configure the virtual host's document root to this project's `public` directory. Do not expose the Laravel project root as the web document root.

## Structure

- `routes/web.php` defines the named page routes.
- `resources/views/layouts/app.blade.php` is the shared HTML shell.
- `resources/views/layouts/partials/` contains the shared navigation and footer.
- `resources/views/pages/` contains the page templates.
- `routes/dashboard.php` defines the account dashboard route group, named `dashboard.*`.
- `app/Http/Controllers/DashboardController.php` serves dashboard screens.
- `resources/views/dashboard/` contains dashboard-only screens, rendered with `resources/views/layouts/dashboard.blade.php`.
- `resources/views/components/registration-profile-wizard.blade.php` contains the reusable profile wizard.
- `config/registration_profiles.php` supplies the four profile-type wizard definitions.
- The footer category browser and listing-page industry filters read their parent/subcategory hierarchy from `industry_categories`; footer links open the matching Business, Startup, or Investor listing with the chosen category selected. The homepage industry section lists every active parent category and counts distinct active business profiles mapped to each parent through `ind_pref_business`. Its location section lists cities from `bx_cities` for country ID 5, counts active Business profiles by matching office city/country, and links to the business listing with that city selected.
- The homepage “Insights, Articles & News” section displays the 12 latest published articles from `bx_articles`, with author names from `bx_author` when available and links to each article detail page.
- The homepage “Business For Sale Opportunities” slider displays the 12 newest active records from `profile_business`, with listing titles, industry, city, asking investment, and a fallback image when no public profile image is available.
- The homepage “Featured Investors” slider displays the 12 newest verified records from `profile_investor`, with investor/company details, city, summary, and a fallback image when no public profile image is available.
- The homepage “High Growth Potential Startups” slider displays the 12 newest active records from `profile_startups`, with startup title, industry when available, funding ask, city, and a fallback image.
- The homepage “World Class Mentors” slider displays the 12 newest active, non-deleted records from `profile_mentors`, with mentor, company, city, summary, and a fallback image; its network count reflects all active, non-deleted mentors.
- Business, Investor, Startup, and Mentor directory routes use dedicated listing controllers. Each listing shows active database profiles, supports keyword/location/industry filtering and query-preserving pagination, and provides profile-specific intent or amount filters where the schema supports them.
- Static footer pages (About Us, Disclaimer, Privacy Policy, Terms, and Contact) are served by `StaticPageController` and use the shared BusinessX theme layout.
- The homepage “Featured Investors” slider displays the 12 newest verified records from `profile_investor`, with investor/company details, city, summary, and a fallback image when no public profile image is available.
- The homepage “What Our Clients Say” section displays up to six active rows from `testimonials`, ordered by `sort_order` and newest ID. The testimonial table includes text, name, designation, 1–5 star rating, optional image path, active status, and display order.

Seed the five repeat-safe homepage testimonial examples with:

```powershell
php artisan db:seed --class=TestimonialSeeder
```

Seed five linked demo records for each Business, Mentor, Investor, and Startup profile type with:

```powershell
php artisan db:seed --class=ProfileRecordsSeeder
```

The profile seeder creates matching `user_account` and `user_profiles` rows, plus canonical `profiles` rows when that table exists. It uses stable demo emails and profile keys, so it can be safely rerun. Use this focused command rather than `php artisan db:seed` when only profile examples are needed; the default database seeder also runs the legacy sample-data seeders.
- `public/assets`, `public/css`, and `public/js` contain the original static files.

The original PHP mockup files remain at the project root as a reference during migration. The Laravel application renders the Blade versions through named routes.

Dashboard URLs use `/dashboard`, `/dashboard/profile`, `/dashboard/password`, `/dashboard/inbox`, `/dashboard/proposals/sent`, `/dashboard/proposals/received`, and `/dashboard/instant-response`. Their route names use the `dashboard.*` namespace (for example `dashboard.index` and `dashboard.profile`). The previous account URLs redirect to their new paths. Dashboard pages require a signed-in account.

## Current scope

Registration and login use the existing `user_account` table; new accounts get a hashed password, a selected first profile in its legacy profile table, and an ownership link in `user_profiles`. New registrations queue a signed email-verification link that expires after 60 minutes; sign-in is blocked until the address is verified. Existing accounts are marked verified by an additive migration to preserve access. Users can request another link from the login page. Forgot-password requests use Laravel's database token broker with `password_reset_tokens`; reset links expire after 60 minutes, are throttled, and can be used only once. Reset emails use the branded queued email template, and responses do not disclose whether an email address has an account. The queue uses the database connection; run `php artisan queue:work database` continuously (or use the deployment's process manager) to send queued mail. Configure SMTP credentials, a verified sender address, and a public `APP_URL`; local/test mail transports may capture messages rather than deliver them to users. Existing account screens remain demo UI; profile editing, password changes, social login, listings, messaging, and payments are not yet connected to persistent application data.

The configured authentication provider uses `App\Models\UserAccount`. Use the `/login` page to sign into an active account or choose one of the Business, Investor, Mentor, or Startup registration wizards. Registration requires the corresponding legacy profile table and `user_profiles` to be present in the configured MySQL database. Dashboard routes redirect guests to the login page, and logout clears the authenticated session.

The homepage and article listing newsletter forms post to `/newsletter/subscribe` and save email addresses in the existing `businessex_newsletter` table. Emails are normalized to lowercase, repeat signups reuse the existing row, and unsubscribed addresses are marked subscribed again. Guest signups use `user_id = 0`; authenticated signups are associated with their account. The legacy table stores no newsletter name, phone number, or city, so the forms collect only an email address.

## Import the existing BusinessX database

The additive migration `database/migrations/legacy/2026_10_05_000000_create_profiles_and_import_legacy_profile_links.php` creates a canonical `profiles` table and copies ownership links from the existing `user_profiles` mapping table. It is isolated under `database/migrations/legacy/` so a normal fresh Laravel setup does not try to run it without the legacy tables. The original profile and account tables remain unchanged.

Before running it:

1. Make a restorable backup of the target database.
2. Import `businessex_200807 (1).sql` into the MySQL/MariaDB database.
3. Set the `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` values in `.env` for that imported database.
4. Run only the migration below against that legacy database:

```powershell
php artisan config:clear
php artisan migrate --path=database/migrations/legacy/2026_10_05_000000_create_profiles_and_import_legacy_profile_links.php
```

Do not run the default Laravel starter migrations against the imported dump as part of this step; the dump already has framework tables and its own `migrations` table. The migration requires all seven legacy profile tables and validates each `user_profiles` row against its account, profile ID, type, and profile key. It aborts on unknown types, duplicate mappings, missing accounts, or mismatched profile ownership rather than guessing. The profile type IDs are taken from the dump's documented mapping: Business (1), Investor (2), Lender (3), Mentor (4), Incubation (5), Broker (6), Startup (7).

The new `profiles` rows retain the legacy profile type/ID/key and `user_profiles` ID, so an account may own multiple profiles, including multiple profiles of the same type. Rollback drops only the new `profiles` table; it leaves all imported legacy tables and rows untouched. Use `php artisan migrate:status` to confirm that the migration ran.

## Articles

The article pages use the existing `bx_articles` table and optionally join `bx_author`. Configure Laravel to use the MySQL/MariaDB database containing those legacy tables; the default fresh SQLite database does not include them. The listing at `/article` shows published rows (`article_status = 1`) and supports keyword search, tag-derived categories, date ranges, sorting, and pagination. Article detail pages use `/article/{article_id}`; `/article-detail?id={article_id}` redirects to the matching detail page for old links. Viewing a published detail page increments `article_views`.

Article categories are derived from the comma-separated `article_tags` field because the legacy article table has no category relationship. Stored article body HTML is sanitized before it is rendered.

Signed-in users can comment on published article detail pages. New comments are saved with pending status (`comment_status = 0`) and only appear publicly after approval (`comment_status = 1`). The comment form is authenticated and rate-limited. Run `php artisan migrate` to expand the legacy comment name and email columns for account details.

To add the ten published sample articles, run the focused, repeat-safe seeder after the article migrations:

```powershell
php artisan db:seed --class=BxArticlesTableSeeder
```

It uses an existing author when available (creating a demo editorial author only when needed) and updates its own sample records by title when run again. Use this focused command rather than `php artisan db:seed` when you only want article content; the default database seeder also runs the other legacy sample-data seeders.
