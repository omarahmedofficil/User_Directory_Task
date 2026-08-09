# User Directory — Project Guide (PHP + MySQL + Vanilla JS)

This is a full-stack **User Directory** single-page application consisting of:
- **Backend**: Framework-less PHP with a clean layered architecture (Controllers → Services → Repositories → Domain Entities).
- **Database**: MySQL (`departments` and `users` tables, One-to-Many relationship).
- **Frontend**: Vanilla HTML/CSS/JavaScript (ES Modules), with no build step.

## 1. Project Structure

```
my_project/
├── .env                      # Database connection settings
├── database/
│   ├── schema.sql            # Ready-made SQL script (creates DB + tables + seed data)
│   ├── migrate.php           # Migration runner script
│   ├── migrations/           # Migration files (001_..., 002_...)
│   └── seed.php              # Seeds sample data (5 departments + 50 users)
├── public/                   # Document root
│   ├── index.php             # Front controller: routes /api/* to the router, everything else to the SPA
│   ├── index.html            # SPA page shell
│   ├── .htaccess             # Rewrites all requests to index.php (Apache)
│   └── assets/               # CSS + JS (app.js, services/, components/)
└── src/
    ├── Autoload.php          # Lightweight PSR-4-style autoloader for the App\ namespace
    ├── Config/                # Settings + database (PDO) connection
    ├── Http/                  # Request / Response / Router
    ├── Controllers/           # UserController
    ├── Services/              # UserService (business logic + input validation)
    ├── Repositories/          # UserRepository / DepartmentRepository + interfaces
    ├── Domain/Entities/       # User / Department (immutable domain objects)
    ├── DTO/                   # Data Transfer Objects (API response shapes)
    ├── Mappers/                # Maps Entities → DTOs
    ├── Exceptions/             # ValidationException / NotFoundException
    └── Middleware/             # ExceptionHandlerMiddleware
```

## 2. Requirements

- **PHP 8.1 or newer** (the project uses `readonly` properties, named arguments, and `declare(strict_types=1)`).
- **PDO** and **pdo_mysql** extensions enabled in PHP.
- **MySQL** or **MariaDB** (5.7+ / 10.x).
- A web server: runs directly via the PHP Built-in Server, or via **Apache** (XAMPP/WAMP) using the `.htaccess` file already provided in `public/`.
- No external dependencies required (no Composer, no npm) — the project is pure PHP and vanilla JS.

## 3. Backend Setup & Run (PHP)

### Step 1 — Configure the `.env` file

The `.env` file at the project root holds the database connection settings:

```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=employees_test
DB_USERNAME=root
DB_PASSWORD=
```

Adjust the values to match your local MySQL server. You can optionally add `APP_DEBUG=false` to hide detailed error info in production (it defaults to `true`).

> Note: These values are read via `getenv()`. When using the PHP Built-in Server or a standard Apache setup, you need to export them as environment variables before starting the server, use a loader such as `phpdotenv`, or hardcode them directly in `src/Config/config.php` for local development.

Example of exporting them beforehand (Linux/macOS):
```bash
export DB_HOST=localhost DB_PORT=3306 DB_DATABASE=employees_test DB_USERNAME=root DB_PASSWORD=
```

### Step 2 — Run the server

**Option A: PHP Built-in Server (fastest for local testing)**

```bash
cd my_project
php -S localhost:8000 -t public public/index.php
```

Then open: `http://localhost:8000/`

**Option B: XAMPP / Apache**

1. Copy the entire `my_project` folder into `htdocs` (e.g. `C:\xampp\htdocs\my_project`).
2. Start the **Apache** and **MySQL** services from the XAMPP control panel.
3. Make sure `mod_rewrite` is enabled in Apache (required for `.htaccess` to work).
4. Open: `http://localhost/my_project/public/`

> `public/index.php` automatically detects the `basePath` (whether the app is at the domain root or inside a subfolder) and injects the correct `<base href>` tag into the page, so API calls and asset links (CSS/JS) work correctly in both cases.

## 4. Frontend Setup & Run

There is no separate frontend build process — the frontend is plain HTML/CSS/JavaScript (ES Modules) inside `public/`, served directly by the same PHP server:

- `public/index.html`: the base page shell (header, search bar, main container).
- `public/assets/js/app.js`: entry point; drives client-side routing via the History API between the list page (`/`) and the detail page (`/users/{id}`).
- `public/assets/js/services/api.service.js`: the HTTP layer (`fetch`) + loading-state management (loading bar).
- `public/assets/js/services/state.service.js`: a small pub/sub state store for shared list state (page, search, results...).
- `public/assets/js/components/`: render functions for the user card (`userCard.js`), the user list with infinite scroll (`userList.js`), and the user detail view (`userDetails.js`).

Once the backend is running (see above) and you open it in a browser, the frontend loads automatically and consumes the following API endpoints:

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/users?page=1&pageSize=10&searchTerm=` | Paginated user list with optional search |
| GET | `/api/users/{id}` | A single user's full detail, including department info |

## 5. Database Setup / Migration Guide

The project supports two ways to set up the database — pick one:

### Option A (fastest): run `schema.sql` directly

This script creates a database named `employees_test` with the tables and 50 rows of sample data already seeded in one go:

```bash
mysql -u root -p < database/schema.sql
```

> ⚠️ If you use this option, update `.env` (`DB_DATABASE=employees_test`) to match the created database name.

### Option B (recommended): Migrations + Seeder

This matches the default value `DB_DATABASE=employees_test` in `.env`:

1. Create an empty database first:
   ```sql
   CREATE DATABASE employees_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. Run the migrations (creates a `migrations` tracking table, then the `departments` and `users` tables in order):
   ```bash
   php database/migrate.php
   ```
   The script skips any migration that was already applied (idempotent).
3. (Optional) Seed the database with sample data — this empties the tables first, then inserts 5 departments and 50 users with unique names/emails:
   ```bash
   php database/seed.php
   ```

To add a new migration later: add a new PHP file under `database/migrations/` following the same pattern (returning `['name' => '...', 'up' => '<SQL>']`), named with the next number after `002_...`. It will be picked up automatically the next time `migrate.php` runs.

## 6. Architectural Decisions & Design Patterns Summary

- **Layered Architecture**: a clear separation between the presentation layer (`Controllers`), business logic / input validation (`Services`), and data access (`Repositories`), which improves maintainability and testability.
- **Repository Pattern + Dependency Inversion**: every data store has an interface (`UserRepositoryInterface`, `DepartmentRepositoryInterface`), and each `Service` depends on the interface rather than a concrete implementation, allowing the data source to be swapped (e.g., for testing) without touching business logic.
- **DTO Pattern (Data Transfer Objects)**: `UserListItemDTO`, `UserDetailDTO`, and `PaginatedResponseDTO` decouple the API response shape from the internal domain entities, and implement `JsonSerializable` for a consistent, controlled JSON output.
- **Mapper Pattern**: `UserMapper` is solely responsible for converting `User` entities into the appropriate DTOs (list/detail), keeping entities and DTOs from being directly coupled.
- **Immutable Domain Entities**: `User` and `Department` are `readonly` objects constructed through a static factory method (`fromRow()`) from raw database rows, guaranteeing data integrity after creation.
- **Centralized Exception Handling (Middleware Pattern)**: dedicated exceptions (`ValidationException` → 400, `NotFoundException` → 404) are caught centrally in `ExceptionHandlerMiddleware` and translated into standardized JSON error responses, with optional debug detail shown only when debug mode is enabled.
- **Front Controller Pattern**: `public/index.php` is the single entry point; it routes `/api/*` requests to a lightweight regex-based `Router`, and serves the SPA shell (`index.html`) for everything else, dynamically injecting a `<base>` tag to support deployment under any subfolder.
- **Singleton Connection**: `Database::connection()` lazily creates and reuses a single PDO connection for the lifetime of the request.
- **Custom PSR-4-like Autoloading**: `Autoload.php` maps the `App\` namespace to files under `src/`, removing the need for Composer.
- **Frontend — Framework-less SPA**: client-side routing via the History API (`app.js`), component-like render functions in `components/`, a dedicated HTTP layer (`api.service.js`), and a small pub/sub state store (`state.service.js`, an Observer-style pattern) that keeps state separate from rendering.
- **Infinite Scroll**: the user list loads more results progressively using `IntersectionObserver` in `userList.js`, instead of traditional pagination controls.

## 7. API Reference

### `GET /api/users`
**Query params**: `page` (default 1), `pageSize` (default 10, max 100), `searchTerm` (optional, matches first name, last name, email, job title, or department name).

```json
{
  "page": 1,
  "pageSize": 10,
  "totalCount": 50,
  "totalPages": 5,
  "data": [
    { "id": 1, "fullName": "George Bluth", "email": "...", "avatarUrl": "...", "jobTitle": "...", "departmentName": "Engineering" }
  ]
}
```

### `GET /api/users/{id}`
Returns full detail for a single user, including nested department info, or a 404 error if not found.

---
