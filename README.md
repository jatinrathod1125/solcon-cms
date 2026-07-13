# Solcon Production Management System (PMS)

Solcon PMS is an enterprise-grade Production Management System built with **Laravel 11 & Tailwind CSS** for coordinating factory workflows, recipes (formulas), mixer machines, and stock ledger movements in Tile Adhesive production.

---

## 🚀 Completed Modules & Features

### 1. Authentication & Role-Based Access Control (RBAC)
- **Role & Permission Models**: Native database models mapping permissions to roles, and roles to users.
- **Middleware Guards**: Built custom `role` and `permission` middleware to restrict master management and reports to authorized administrators, and batch operations to supervisors.
- **Custom Authentication**: Custom login and logout controls under `LoginController` ensuring session security and credentials checks.

### 2. Configuration & Master Modules

- **Department Master**: Manage production sections (e.g. TAD - Tile Adhesive, GRT - Grout, EPX - Epoxy). Supports active/inactive states and blocks deleting departments with active machines or supervisor links.
- **Machine Master**: Manage mixer machines (e.g. Mixer 1, Mixer 2). Supports department assignment and unique mixer code index boundaries.
- **Unit Master**: Manage standard units of measurement (e.g., KG, GM) cached dynamically.
- **Bag Size Master**: Manage packaging sizes (e.g., 20 KG, 25 KG) used to convert finished product packaging bags into absolute weight values.
- **Raw Material Master**: Manage ingredients. During creation, sets current stock equal to opening stock. Equips low-stock warnings when inventory drops below the minimum limit.
- **Grade Master**: Manage product grades (e.g. F101, F107). Automatically audits creation and modifications using `$table->foreignId('created_by')` and `updated_by`.
- **Formula Master**: Version-controlled recipe builder supporting dynamically added/removed ingredient rows via jQuery.
  - Enforces **unique raw materials** within a recipe version.
  - Enforces the **Single Active Recipe Rule** per Grade; activating a version automatically deactivates other versions.
  - Formula versions remain read-only once a batch is run using them.

### 3. Adhesive Production Engine
- **Sequential Batch Numbers**: Automatically generates sequential, date-stamped numbers using `ADH-YYYYMMDD-XXXX` format.
- **Mixer Collision Guard**: Prevents starting multiple running batches on a single machine simultaneously.
- **Department Bounds**: Restricts supervisor options (machines, grades) and query lists strictly to their assigned department.
- **Formula Snapshotting**: Clones the active formula composition into a JSON column (`formula_snapshot`) at batch start, safeguarding historical reports against future master recipe edits.
- **Stock Validation Guard**: Verifies available quantities of raw materials *before* allowing batch completion. Aborts execution and triggers transaction rollbacks if stock is insufficient.

### 4. Inventory Stock Ledger
- **Transaction Types**: Logs `IN` (receivables), `OUT` (consumed in production batches), and `ADJUSTMENT` (manual corrections) movements.
- **Audit Trails**: Ledger records are immutable (can never be updated or deleted).
- **Balance Tracking**: Computes and stores the `balance_after` value at each movement step to maintain historical audit transparency.

### 5. Daily Production Reports
- **Dynamic Summaries**: Queries date-wise metrics compiling total packages completed, grand total weights produced, machine breakdowns, supervisor counts, and detailed batch lists.
- **PDF Export**: Exports daily summaries into a clean, print-friendly template using `barryvdh/laravel-dompdf`.

---

## 🗄️ Database Schema & Architecture

All tables use the **InnoDB engine** with strict foreign key constraints and indexed keys to guarantee performance and referential integrity:

### Tables Map

1. **`users`**: User records, department assignment, and status.
2. **`roles`**: User role lookup (`admin`, `supervisor`).
3. **`permissions`**: Access gate definitions.
4. **`departments`**: Sections list (e.g. TAD). Unique index on `code`.
5. **`machines`**: Mixer machines. Unique index on `code`. Foreign key to `departments`.
6. **`units`**: Units of measurement. Unique index on `code`.
7. **`bag_sizes`**: Packing sizes. Unique index on `name`.
8. **`raw_materials`**: Ingredients. Unique index on `code`. Foreign keys to `departments` and `units` (stock/purchase).
9. **`grades`**: Product grades. Unique index on `code`. Foreign keys to `departments`, `bag_sizes`, and `units`.
10. **`formulas`**: Recipe versions. Foreign key to `grades`. Unique index on `[grade_id, version]`.
11. **`formula_items`**: Ingredients within recipes. Foreign keys to `formulas` and `raw_materials`. Unique index on `[formula_id, raw_material_id]`.
12. **`production_batches`**: Batch runs. Unique index on `batch_no`. Foreign keys to `machines`, `grades`, `formulas`, and `users`. Holds `formula_snapshot` JSON column.
13. **`stock_ledgers`**: Stock movements ledger. Foreign keys to `raw_materials`, `production_batches`, and `users`.
14. **`stock_adjustments`**: Manual stock edits. Foreign keys to `raw_materials` and `users`.
15. **`activity_logs`**: System audit trail log. Foreign key to `users`.

---

## ⚙️ Service Layer Boundaries (`app/Services/`)

Business logic is completely decoupled from controllers using specialized services:

- **`BatchNumberService`**: Handles date-sequential batch code generations (`ADH-YYYYMMDD-XXXX`).
- **`FormulaService`**: Resolves active formula configurations and retrieves ingredient listings.
- **`ActivityLogService`**: Logs system audits (`BATCH_CREATED`, `BATCH_COMPLETED`, `FORMULA_UPDATED`, `STOCK_ADJUSTMENT`, `FAILED_PRODUCTION_ATTEMPT`) mapping IP and User-Agent.
- **`StockService`**: Manages atomic inventory transactions (`recordMovement` and `adjustStock`) under select-for-update database locks to prevent concurrency race conditions.
- **`ProductionService`**: Coordinates start and completion flows, executing machine locks, stock checks, snapshot creation, and database rollbacks on failure.
- **`ReportService`**: Compiles daily aggregates, machine total weights, and consumption metrics.

---

## 🛣️ API / Routes Index (`routes/web.php`)

### Public Routes
- `GET /login` : Displays login form.
- `POST /login` : Performs login.
- `POST /logout` : Logs out current user.

### Admin Restricted Routes (`middleware: role:admin`)
- `GET /admin/dashboard` : Admin home dashboard.
- `resource /admin/departments` : Departments CRUD.
- `resource /admin/machines` : Machines CRUD.
- `resource /admin/units` : Units CRUD.
- `resource /admin/bag-sizes` : Bag Sizes CRUD.
- `resource /admin/raw-materials` : Raw Materials CRUD.
- `resource /admin/grades` : Grades CRUD.
- `resource /admin/formulas` : Recipe versions CRUD.
- `GET /admin/reports/daily` : Daily production dashboard report.
- `GET /admin/reports/daily/pdf` : Export daily summary PDF.

### Shared Production Routes (`middleware: role:admin,supervisor`)
- `GET /production` : Production history dashboard.
- `GET /production/create` : Form to start a new batch.
- `POST /production` : Starts a new batch.
- `GET /production/{batch}` : Batch details.
- `PUT /production/{batch}/complete` : Completes a running batch.

---

## 🧪 Testing and Verification Suite

We use a feature testing suite to verify validations, database transactions, permissions, and service layer actions:
- **`AuthTest.php`**: Login, redirects, and session checks.
- **`MastersTest.php`**: CRUD logic and deletion blocker tests for masters.
- **`RawMaterialsTest.php`**: Checks opening stock alignment and low stock warning criteria.
- **`GradesTest.php`**: Validates creation parameters and auditing columns.
- **`FormulasTest.php`**: Validates dynamic ingredient duplicates block and active recipe constraints.
- **`ProductionTest.php`**: Verifies batch generation, machine locks, and conversions.
- **`ReportsTest.php`**: Validates summary logic and PDF creation headers.
- **`EnterpriseTest.php`**: Tests services (`StockService`, `ProductionService`, `FormulaService`, `BatchNumberService`) and audit logs.

### Running Tests
To run all tests and check assertions:
```bash
php artisan test
```

Current verification output:
```json
{"result":"passed","tests":43,"passed":43,"assertions":198}
```

## 🔔 Firebase Push Notification Setup
Solcon uses Firebase Cloud Messaging (FCM) for web push notifications. The flow is implemented in these main areas:

- `resources/views/layouts/app.blade.php` - injects Firebase config into the page via `<meta name="firebase-config" ...>`.
- `resources/js/firebase.js` - initializes Firebase, registers the service worker, requests permission, gets the FCM token, and sends it to the backend.
- `routes/web.php` - exposes a dynamic service worker endpoint at `GET /firebase-messaging-sw.js`.
- `app/Http/Controllers/NotificationController.php` - registers/removes device tokens and returns unread notifications.
- `app/Services/FirebaseService.php` - sends push messages through FCM using a Firebase service account.
- `app/Services/NotificationService.php` - logs notifications, stores device tokens, and routes notifications to users or departments.

### Required `.env` configuration
Set these Firebase variables in your `.env` file:

```env
FIREBASE_PROJECT_ID=your-firebase-project-id
FIREBASE_API_KEY=your-firebase-api-key
FIREBASE_AUTH_DOMAIN=your-firebase-auth-domain
FIREBASE_STORAGE_BUCKET=your-firebase-storage-bucket
FIREBASE_MESSAGING_SENDER_ID=your-firebase-messaging-sender-id
FIREBASE_APP_ID=your-firebase-app-id
FIREBASE_MEASUREMENT_ID=your-firebase-measurement-id
FIREBASE_VAPID_KEY=your-firebase-web-push-vapid-key
FIREBASE_CREDENTIALS=storage/app/firebase/firebase-service-account.json
```

Notes:
- `FIREBASE_VAPID_KEY` is the Web Push certificate key from Firebase Console > Cloud Messaging > Web Push certificates.
- `FIREBASE_CREDENTIALS` is the path to the Firebase service account JSON file used for server-side FCM send. The code resolves this path via `base_path()`.

### How the frontend flow works
1. `resources/views/layouts/app.blade.php` includes:
   - `<meta name="csrf-token" content="{{ csrf_token() }}">`
   - `<meta name="firebase-config" content="{{ json_encode(config('services.firebase')) }}">`
2. `resources/js/firebase.js` reads that JSON config and initializes Firebase with `initializeApp(...)`.
3. The script registers the service worker at `/firebase-messaging-sw.js`.
4. When permission is granted, it calls `getToken(messaging, { vapidKey, serviceWorkerRegistration })`.
5. The retrieved token is posted to `POST /notifications/devices` with browser/platform details.
6. Foreground messages are handled by `onMessage(...)` and show an in-app SweetAlert toast.
7. Background messages are handled inside the dynamic service worker route `GET /firebase-messaging-sw.js`.

### Service worker behavior
The service worker script is generated dynamically in `routes/web.php` and does the following:
- imports `firebase-app-compat.js` and `firebase-messaging-compat.js`
- initializes Firebase using service config from `services.firebase`
- listens for `messaging.onBackgroundMessage(...)`
- displays a browser notification with title/body/icon/badge
- handles notification clicks and opens the payload URL

### Backend registration and notification flow
- `POST /notifications/devices` registers a device token in `app/Services/NotificationService.php` and stores it in `user_devices`.
- `DELETE /notifications/devices` removes a token when the client no longer needs it.
- `GET /notifications/unread` fetches unread notifications for the user.
- `POST /notifications/{notification}/read` marks a notification as read.
- `POST /notifications/read-all` marks all current notifications read.

`NotificationService::registerDevice(...)` uses `UserDevice::updateOrCreate(...)` keyed by `device_token`.

### Server-side sending logic
`app/Services/FirebaseService.php` does these steps:
1. Reads the Firebase service account JSON from the configured credentials path.
2. Builds a JWT and exchanges it for a Google OAuth access token.
3. Sends the FCM payload to `https://fcm.googleapis.com/v1/projects/{project_id}/messages:send`.

The notification payload includes:
- `notification.title`
- `notification.body`
- `data` fields converted to strings
- `webpush.fcm_options.link` for the click action URL
- `webpush.notification.icon` and `webpush.notification.badge`

### Notification routing rules
`app/Services/NotificationService.php` supports:
- `sendToUser(...)` - sends to all devices for one user
- `sendToDepartment(...)` - sends to supervisors in a department plus admins
- `sendToAdmins(...)` - sends to all active admin users

It also logs notification status in the `notifications` table, including `sent`, `failed`, or `no_device_registered`.

### Important setup checks
- Confirm `resources/js/app.js` imports `./firebase` so the Firebase client code loads.
- Make sure your browser supports `Notification` and `serviceWorker` APIs.
- Ensure `routes/web.php` can serve `/firebase-messaging-sw.js` from the app root.
- Place your Firebase service account JSON in the path configured by `FIREBASE_CREDENTIALS`.
- If any config value is missing or placeholder-like, the client will skip registration and log a warning.

### Debugging hints
- Browser Console should show:
  - `FCM Service Worker registered with scope:`
  - `Notification permission granted.`
  - `FCM Device Token retrieved:`
  - `Device token successfully registered on server.`
- If the server cannot send messages, check `storage/logs/laravel.log` for Firebase OAuth or FCM send errors.
- If notifications do not appear in background, verify the service worker route and the `click_action` payload.

### Quick file map
- `resources/views/layouts/app.blade.php`
- `resources/js/firebase.js`
- `routes/web.php`
- `app/Http/Controllers/NotificationController.php`
- `app/Services/NotificationService.php`
- `app/Services/FirebaseService.php`
- `app/Models/UserDevice.php`
- `app/Models/Notification.php`

