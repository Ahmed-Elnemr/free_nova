# Starter Code For Laravel 12

Laravel 12 API starter with a clean **Repository–Service** architecture, dual authentication (admin + client users), FCM push notifications, Spatie media uploads, roles/permissions, and CMS-style modules (blogs, contact, settings).

Use this repo as a base: add your domain models on top of the existing patterns instead of rebuilding auth, notifications, or file handling.

---

## Requirements

- PHP **8.2+**
- Composer
- MySQL (or compatible database)
- Optional: Redis + queue worker for async notifications
- Optional: Firebase credentials file for FCM (`FIREBASE_CREDENTIALS` in `.env`)

---

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Default seeders create permissions, a **Super Admin** role, and an admin account (see `database/seeders/AdminSeeder.php`).

For local OTP testing, set `USE_FIXED_OTP=true` in `.env` (codes become `000000` for numeric OTPs).

---

## Architecture

| Layer | Responsibility |
|--------|----------------|
| **Controller** | HTTP only: validate request, call service, return `ApiResponse` |
| **Service** | Business logic, transactions, orchestration |
| **Repository** | All database queries (via contract + `BaseRepository`) |
| **Model** | Relationships, casts, media collections, scopes — **no query logic** |

Rules enforced in this project:

- Do not query the database outside repositories.
- Use `MediaUpload` facade for all file uploads (Spatie Media Library under the hood).
- Register repository bindings in `app/Providers/RepositoryServiceProvider.php`.

---

## API routes

Routes are registered in `bootstrap/app.php`:

| Prefix | Guard | File | Purpose |
|--------|-------|------|---------|
| `api/v1/client` | `api` (Sanctum) | `routes/api.php` | Public + user auth, profile, blogs, contact, notifications |
| `api/v1/admin` | `admin` (Sanctum) | `routes/admin.php` | Admin auth, CRUD, settings, notification groups |

Shared notification listing routes live in `routes/notifications.php` (included under both prefixes when authenticated).

**Locale:** send `X-Locale: ar` or `X-Locale: en` (middleware `locale`).

---

## Project structure

```
app/
├── Auth/Login/              # Password & OTP login strategies
├── Enums/                   # OtpPurpose, NotificationType, ContactMessage*, etc.
├── Exceptions/
├── Facades/                 # ApiResponse, MediaUpload
├── Http/
│   ├── Controllers/Api/
│   │   ├── Admin/           # Admin panel APIs
│   │   ├── Client/          # Public client APIs
│   │   ├── User/            # Authenticated user (profile, auth)
│   │   └── Notification/    # In-app notifications (read/mark read)
│   ├── Filters/             # Query filters for list endpoints
│   ├── Requests/            # Form requests (Admin / User / Client)
│   └── Resources/           # API transformers
├── Jobs/                    # e.g. SendGroupNotificationJob
├── Models/
├── Notifications/         # FCM + database notification classes
├── Repositories/
│   └── Contracts/           # Repository interfaces
├── Services/
│   ├── ApiResponse/         # Standard JSON envelope
│   ├── Auth/                # AuthAdminService, AuthUserService
│   └── MediaUpload/         # Image processing + Spatie upload
├── Settings/                # Spatie settings (General, Contact, etc.)
├── Support/                 # PermissionGenerator, etc.
└── Traits/                  # HasOtps, etc.

config/
├── auth.php                 # guards: api, admin
├── entities.php             # make:entity namespaces
├── permission.php
└── settings.php

database/
├── migrations/
└── seeders/                 # PermissionsSeeder, RoleSeeder, AdminSeeder

routes/
├── api.php                  # Client API
├── admin.php                # Admin API
└── notifications.php        # Shared notification routes
```

---

## Available features

### Authentication

| Actor | Method | Login | Notes |
|-------|--------|-------|-------|
| **User** (`api` guard) | OTP | Phone/email + OTP | Register, login, resend OTP, change phone |
| **Admin** (`admin` guard) | Password | Email + password | Forgot/reset via OTP, Sanctum token |

Both use `BaseAuthController` + dedicated `Auth*Service` and Sanctum personal access tokens.

### Authorization (admin)

- Spatie Permission: roles + permissions (`admin` guard).
- Permissions seeded via `PermissionGenerator` (`database/seeders/PermissionsSeeder.php`).
- Enable on controllers with `protected bool $usePermissions = true` on `BaseApiController` children.

### Users & admins

- Admin: full CRUD, toggle status, role assignment.
- Users: list/show, toggle status, Excel/PDF export (admin).

### Notifications

- **In-app:** Laravel `notifications` table; list, unread count, mark read (`NotificationController`).
- **Push (FCM):** via `laravel-notification-channels/fcm`; users need FCM tokens stored on `fcm_tokens`.
- **Broadcast groups:** admin creates a `NotificationGroup`, selects `user_ids`, job sends `AdminNotification` to each user.

### Content & settings

- **Blogs** (translatable AR/EN) + comments.
- **Contact messages** + admin reply email.
- **Settings:** contact links, about us, terms, privacy, general (incl. free-period flags).

### Media

- Spatie Media Library + Intervention image processing.
- Central `MediaUpload` facade (resize, quality, collections).

### Developer tooling

- `php artisan make:entity` — scaffold model, migration, request, resource, service, filter, seeder.
- Laravel Telescope (local debugging).
- Activity log (Spatie).
- Standardized `ApiResponse` JSON format.

---

## Standard API response

Use the `ApiResponse` facade everywhere:

```php
use App\Facades\ApiResponse;

// Single resource
return ApiResponse::respondWithModel(new UserResource($user))->send();

// Collection + pagination
return ApiResponse::respondWithCollection(UserResource::collection($users))
    ->withPagination($users)
    ->send();

// Arbitrary payload (auth, custom actions)
return ApiResponse::respondWithArray([
    'user' => UserResource::make($user),
    'token' => $token,
])->send();

// Error
return ApiResponse::respondWithError(__('User not found.'), httpStatus: 404)->send();
```

Validation and auth errors are normalized in `bootstrap/app.php` for JSON requests.

---

## Guide: file uploads

All uploads go through **`MediaUpload`**. The model must implement `Spatie\MediaLibrary\HasMedia` and define collections in `registerMediaCollections()`.

### 1. Define media collections on the model

```php
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('gallery'); // multiple files
    }
}
```

### 2. Upload from a service (recommended)

```php
use App\Facades\MediaUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

public function update(int $id, array $data): Model
{
    return DB::transaction(function () use ($id, $data) {
        $file = Arr::pull($data, 'cover');

        $product = $this->repository->update($id, $data);

        if ($file instanceof UploadedFile) {
            $product->clearMediaCollection('cover'); // when replacing single file
            MediaUpload::file($file)
                ->collection('cover')
                ->quality(80)
                ->resize(1200, null) // width only, keep aspect ratio
                ->uploadTo($product);
        }

        return $product->refresh();
    });
}
```

### 3. Fluent options

| Method | Purpose |
|--------|---------|
| `file(UploadedFile $file)` | Set file (required) |
| `collection(string $name)` | Spatie collection name |
| `quality(int $percent)` | JPEG/WebP quality |
| `resize($w, $h, $maintainAspect)` | Scale image |
| `fit($w, $h)` | Crop to exact size |
| `name(string $name)` | Custom file name |
| `properties(array $meta)` | Custom media properties |
| `uploadTo(Model $model)` | Persist and return `Media` |

**Reference implementations:** `UserService::updateProfile`, `BlogController::store` / `update`, `AdminService` avatar handling.

---

## Guide: sending notifications

### A. Admin broadcast to many users (built-in)

1. Authenticate as admin: `POST /api/v1/admin/notification-groups`
2. Body (example):

```json
{
  "title": { "ar": "عنوان", "en": "Title" },
  "body": { "ar": "نص", "en": "Body text" },
  "user_ids": [1, 2, 3]
}
```

3. `NotificationGroupController` calls `NotificationGroupService::createAndSend()`, which dispatches `SendGroupNotificationJob`.
4. Each user receives `AdminNotification` on **database** + **FCM** channels.

Ensure `QUEUE_CONNECTION` is not `sync` in production if you want non-blocking sends, and configure Firebase:

```env
FIREBASE_CREDENTIALS=storage/app/firebase.json
```

Users must have rows in `fcm_tokens` (and `User::routeNotificationForFcm()` returns those tokens).

### B. Notify one user from code

Create a notification class:

```php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class OrderShippedNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => ['en' => 'Order shipped', 'ar' => 'تم شحن الطلب'],
            'body'  => ['en' => 'Your order is on the way.', 'ar' => 'طلبك في الطريق.'],
            'type'  => 'order_shipped',
        ];
    }

    public function toFcm(object $notifiable): FcmMessage
    {
        return (new FcmMessage(
            notification: new FcmNotification(
                title: 'Order shipped',
                body: 'Your order is on the way.',
            )
        ))->data(['type' => 'order_shipped']);
    }
}
```

Send it:

```php
$user->notify(new OrderShippedNotification());
```

**Examples in repo:** `AdminNotification`, `FreePeriodStatusChangeNotification`.

### C. Client reads notifications

Authenticated user or admin:

- `GET /api/v1/client/notifications` (or `/api/v1/admin/notifications`)
- `GET .../notifications/unread-count`
- `POST .../notifications/mark-as-read`
- `POST .../notifications/{id}/mark-as-read`

---

## Guide: add a new auth model (e.g. Vendor)

Follow the same pattern as **User** (OTP) or **Admin** (password). Below is a password-based **Vendor** example.

### 1. Migration & model

```bash
php artisan make:model Vendor -m
```

```php
// app/Models/Vendor.php
class Vendor extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasOtps; // HasOtps if using OTP

    protected $fillable = ['name', 'email', 'password', 'is_active'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array {
        return ['password' => 'hashed', 'is_active' => 'boolean'];
    }
}
```

### 2. Register guard & provider — `config/auth.php`

```php
'guards' => [
    // ...
    'vendor' => [
        'driver' => 'sanctum',
        'provider' => 'vendors',
    ],
],

'providers' => [
    // ...
    'vendors' => [
        'driver' => 'eloquent',
        'model' => App\Models\Vendor::class,
    ],
],
```

### 3. Auth service — `app/Services/Auth/AuthVendorService.php`

Implement `App\Services\Auth\Contracts\AuthLoginServiceContract` (copy `AuthAdminService`):

```php
class AuthVendorService implements AuthLoginServiceContract
{
    protected string $guard = 'vendor';
    protected string $authModel = Vendor::class;

    public function login(array $credentials, string $authKey): array
    {
        $result = app(UsingPasswordLoginStrategy::class)->login(
            $this->guard,
            $authKey,
            $this->authModel,
            $credentials
        );

        return [
            'user' => VendorResource::make($result['user']),
            'token' => $result['token'],
        ];
    }
}
```

For OTP login, use `UsingOtpLoginStrategy` like `AuthUserService` instead.

### 4. Auth controller — extend `BaseAuthController`

```php
namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Api\BaseAuthController;
use App\Http\Requests\Vendor\VendorLoginRequest;
use App\Models\Vendor;
use App\Services\Auth\AuthVendorService;

class AuthController extends BaseAuthController
{
    protected string $guard = 'vendor';
    protected string $authModel = Vendor::class;
    protected string $loginKey = 'email';
    protected $loginFormRequest = VendorLoginRequest::class;
    protected $authService = AuthVendorService::class;
}
```

`BaseAuthController` already provides `login`, `forgotPassword`, `verifyOtp`, `resetPassword` (OTP flow).

### 5. Routes

Either add to `routes/api.php` or create `routes/vendor.php` and register it in `bootstrap/app.php` (mirror the admin/client groups):

```php
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
});

Route::middleware('auth:vendor')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    // protected vendor routes...
});
```

### 6. Protect routes

```php
Route::middleware(['auth:vendor', 'locale'])->group(function () {
    // ...
});
```

### OTP purposes

Add cases to `app/Enums/OtpPurpose.php` if you need new flows, then use them in strategies / `AuthUserService`.

---

## Guide: add a new CRUD module

### Option 1 — Artisan generator

```bash
php artisan make:entity Product
```

Choose namespace (`admin` or `user`) from `config/entities.php`. The command can generate model, migration, requests, resource, service, filter, and seeder.

Then:

1. Implement repository + contract (extend `BaseRepository`).
2. Bind contract in `RepositoryServiceProvider`.
3. Add permissions in `PermissionsSeeder` and re-seed.
4. Create controller extending `BaseApiController` (set `$modelName`, `$serviceName`, `$resource`).
5. Register routes in `routes/admin.php` or `routes/api.php`.

### Option 2 — Manual (minimal)

**Repository contract + implementation:**

```php
// app/Repositories/Contracts/ProductRepositoryContract.php
interface ProductRepositoryContract extends RepositoryContract {}

// app/Repositories/ProductRepository.php
class ProductRepository extends BaseRepository implements ProductRepositoryContract
{
    protected function resolveModel(): Model { return new Product(); }
    protected function resolveFilter(): ?BaseFilters { return app(ProductFilter::class); }
}
```

**Service:**

```php
class ProductService extends BaseModelService
{
    public function __construct(ProductRepositoryContract $repository)
    {
        parent::__construct($repository);
    }
}
```

**Controller:**

```php
class ProductController extends BaseApiController
{
    protected string $modelName = 'Product';
    protected string $serviceName = ProductService::class;
    protected string $resource = ProductResource::class;
    protected string $storeRequest = StoreProductRequest::class;
    protected string $updateRequest = UpdateProductRequest::class;
    protected bool $usePermissions = true;
}
```

`BaseApiController` provides `index`, `show`, `store`, `update`, `destroy`, `toggleStatus` when configured.

---

## Permissions

Naming convention: `{entity}.{action}` on guard `admin`, e.g. `users.read`, `blogs.create`.

Generate in seeder:

```php
use App\Support\PermissionGenerator;

PermissionGenerator::generate(['Products'], ['read', 'create', 'update', 'delete'], 'admin');
```

Run:

```bash
php artisan db:seed --class=PermissionsSeeder
php artisan db:seed --class=RoleSeeder
```

---

## Environment variables (common)

| Variable | Purpose |
|----------|---------|
| `APP_URL` | Base URL for links / Sanctum |
| `USE_FIXED_OTP` | `true` = predictable OTP in dev |
| `TIME_ZONE` | Default app timezone |
| `FIREBASE_CREDENTIALS` | Path to Firebase service account JSON |
| `QUEUE_CONNECTION` | `sync` (dev) or `database` / `redis` (prod) |
| `SETTINGS_CACHE_ENABLED` | Cache Spatie settings |

---

## Testing the API

Example admin login:

```http
POST /api/v1/admin/auth/login
Content-Type: application/json
X-Locale: en

{
  "email": "admin@example.com",
  "password": "password"
}
```

Use the returned `token` as:

```http
Authorization: Bearer {token}
```

User OTP login flow:

1. `POST /api/v1/client/auth/login` (or `register`)
2. `POST /api/v1/client/auth/verify-otp` with `code` and `purpose`

---

## License

MIT (Laravel framework components remain under their respective licenses).
