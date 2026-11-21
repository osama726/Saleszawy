# توثيق مشروع Saleszawy CMS API

**Backend:** Laravel 12 (API-only) · **DB:** MySQL · **الإصدار:** 1.0 (مسودة) · **التاريخ:** 19 نوفمبر 2026

## 1. نظرة عامة

### 1.1 الهدف

بناء Backend على شكل REST API يدير محتوى الموقع التسويقي لمنظومة Saleszawy (CRM مبيعات) وبوابة التوثيق (Docs) الخاصة بها. الـ API هو المصدر الوحيد للمحتوى، والفرونت (Next.js) بيستهلكه.

### 1.2 النطاق

- **داخل النطاق:** الصفحات وأقسامها، المميزات، الباقات والأسعار، الأسئلة الشائعة، الشركاء، التكاملات، المدونة، التوثيق، طلبات التواصل، الإعدادات العامة، المستخدمين والصلاحيات.
- **خارج النطاق:** واجهات Next.js، ونظام Saleszawy الأساسي نفسه (مشروع منفصل).

### 1.3 الأطراف

| الطرف              | الدور                                                  |
| ------------------ | ------------------------------------------------------ |
| Backend (Laravel)  | يوفر Public API و Admin API                            |
| Frontend (Next.js) | الموقع العام + لوحة الإدارة (افتراض: تستهلك Admin API) |
| الأدمن / المحررين  | يديروا المحتوى من لوحة الإدارة                         |
| الزوار             | يقرأوا المحتوى ويبعتوا طلبات التواصل                   |

> **افتراض مهم:** لوحة الـ CMS هتتبني في الفرونت وتستهلك Admin API. لو اتقرر استخدام Filament جوه Laravel بدلها، الـ Public API والطبقات (Actions / Services) تفضل زي ما هي.

## 2. القرارات المعمارية

| القرار                         | السبب                                                     |
| ------------------------------ | --------------------------------------------------------- |
| API-only (بدون Blade/Vite)     | الفرونت منفصل تماماً ومفيش داعي لأي assets في الـ backend |
| Public API منفصل عن Admin API  | اختلاف الـ caching والصلاحيات والـ rate limits            |
| Versioning بالـ URL: `/api/v1` | تطوير الـ API من غير كسر الفرونت                          |
| Actions + Services + DTOs      | Controllers خفيفة وكود قابل للاختبار وإعادة الاستخدام     |
| Response موحد عبر Trait        | شكل ثابت للنجاح والأخطاء والـ pagination                  |
| Sanctum (Bearer tokens)        | بسيط ومناسب للوحة إدارة منفصلة                            |
| Cache + Revalidation Webhook   | الموقع سريع والمحتوى يتحدث فوراً بعد النشر                |
| Enums بدل الـ strings          | حالات المحتوى والأدوار تبقى type-safe                     |

## 3. التقنيات والإصدارات

### 3.1 البيئة

| المكون   | الإصدار                         |
| -------- | ------------------------------- |
| PHP      | ^8.2 (المقترح للتشغيل 8.3+)     |
| Laravel  | ^12.0                           |
| MySQL    | 8.0+ (مطلوب لـ JSON و FULLTEXT) |
| Redis    | 7+ (cache + queue)              |
| Composer | 2.x                             |

### 3.2 المكتبات المثبتة في composer.json الحالي

**الحزم الأساسية والإنتاجية**

| الحزمة                                       | الإصدار  | الاستخدام                               |
| -------------------------------------------- | -------- | --------------------------------------- |
| php                                          | ^8.2     | متطلبات تشغيل المشروع                   |
| laravel/framework                            | ^12.0    | الإطار الأساسي                          |
| laravel/tinker                               | ^2.10.1  | REPL للتجربة وتشغيل الأوامر             |
| bezhansalleh/filament-shield                 | ^4.3     | الأدوار والصلاحيات داخل Filament        |
| filament/filament                            | ^5.11    | لوحة إدارة Filament                     |
| filament/spatie-laravel-media-library-plugin | ^5.11    | ربط Filament بمكتبة الملفات والصور      |
| spatie/laravel-medialibrary                  | ^11.23   | رفع وإدارة الصور والملفات               |
| spatie/laravel-query-builder                 | ^6.4     | filter و sort و include من query string |
| spatie/laravel-sluggable                     | ^3.8     | توليد الـ slugs بشكل آلي                |
| dedoc/scramble                               | ^0.13.55 | توثيق OpenAPI من الكود                  |

**حزم التطوير والاختبار**

| الحزمة               | الإصدار  | الاستخدام           |
| -------------------- | -------- | ------------------- |
| larastan/larastan    | ^3.15    | Static analysis     |
| laravel/pint         | ^1.24    | تنسيق الكود وفحصه   |
| laravel/pail         | ^1.2.2   | متابعة الـ logs     |
| laravel/sail         | ^1.41    | بيئة Docker للتطوير |
| phpunit/phpunit      | ^11.5.50 | الاختبارات          |
| mockery/mockery      | ^1.6     | Mocking             |
| nunomaduro/collision | ^8.6     | تحسين عرض الأخطاء   |
| fakerphp/faker       | ^1.23    | بيانات تجريبية      |

### 3.3 ملاحظات عن الحزم المثبتة

- الـ Filament يعمل من خلال لوحة باسم `admin` بعد تشغيل أمر التثبيت الخاص به.
- صلاحيات Filament تُدار عبر Filament Shield.
- الصور والملفات تُدار عبر Spatie Media Library، وليس أعمدة صور منفصلة في الجداول.
- البحث والفلترة من الـ query string تتم عبر Spatie Query Builder.
- التوثيق البرمجي لـ API يتم عبر Scramble.
- Larastan مستخدم كحزمة تطوير لفحص الأكواد تحليلياً.

### 3.4 أوامر التثبيت من README

```bash
composer require filament/filament:"^5.0" \
  bezhansalleh/filament-shield:"^4.0" \
  filament/spatie-laravel-media-library-plugin:"^5.0" \
  spatie/laravel-medialibrary \
  spatie/laravel-sluggable \
  spatie/laravel-query-builder \
  dedoc/scramble

composer require --dev larastan/larastan

php artisan filament:install --panels      # panel id: admin

php artisan vendor:publish --provider="Spatie\\Permission\\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\\MediaLibrary\\MediaLibraryServiceProvider" --tag="medialibrary-migrations"

php artisan migrate
php artisan make:filament-user
```

- نضيف سكربتات `lint` (Pint) و `analyse` (Larastan).

```bash
composer lint      # pint --test
composer analyse   # phpstan analyse
composer test
```

## 4. هيكل المشروع

التقسيم **layer-first** مع مجلدات فرعية لكل domain داخل كل طبقة:

```text
app/
├── Actions/            # عملية واحدة = كلاس واحد
│   ├── Admin/{Post,Plan,Page,Doc,...}/
│   └── Public/ContactRequest/
├── DTOs/               # كائنات نقل بيانات (readonly)
├── Enums/              # PostStatus, RoleName, ContactStatus, ...
├── Events/ Listeners/ Jobs/
├── Exceptions/         # ApiException + exceptions الخاصة بالـ domain
├── Http/
│   ├── Controllers/Api/V1/{Public,Admin}/
│   ├── Middleware/
│   ├── Requests/{Public,Admin}/
│   └── Resources/
├── Models/
├── Observers/
├── Policies/
├── Queries/            # منطق القراءة والفلترة المعقدة
├── Services/           # + Contracts/ للواجهات
└── Traits/             # ApiResponse, HasPublishStatus, HasSortOrder

routes/
├── api.php             # يحمّل الملفات التالية تحت prefix v1
└── api/v1/{public.php, admin.php}
```

## 5. طبقات الكود والمسؤوليات

### 5.1 مسار الطلب

```text
Request → Route → Middleware → FormRequest (validation → DTO)
        → Controller → Action → (Service / Query / Model)
        → Resource → ApiResponse → JSON
```

### 5.2 مسؤولية كل طبقة

| الطبقة      | المسؤولية                                                   | ممنوع فيها                       |
| ----------- | ----------------------------------------------------------- | -------------------------------- |
| Controller  | يستقبل الطلب، يستدعي Action، يرجع Response                  | منطق أعمال أو استعلامات          |
| FormRequest | Validation و Authorization الأولي                           | منطق أعمال                       |
| DTO         | نقل بيانات typed بين الطبقات                                | أي سلوك                          |
| Action      | عملية أعمال واحدة (مثلاً CreatePostAction) داخل transaction | الاعتماد على Request أو Response |
| Service     | منطق مشترك أو تكامل خارجي (Cache, Media, Revalidation)      | منطق تخص endpoint واحد           |
| Query       | بناء استعلامات القراءة والفلترة                             | الكتابة في الداتابيز             |
| Model       | علاقات و casts و scopes                                     | منطق أعمال معقد                  |
| Resource    | شكل الـ JSON الخارج                                         | استعلامات                        |
| Policy      | قرارات الصلاحيات                                            |                                  |

### 5.3 تطبيق مبادئ SOLID

| المبدأ                      | التطبيق في المشروع                                                  |
| --------------------------- | ------------------------------------------------------------------- |
| **S** Single Responsibility | كل Action عملية واحدة، وكل Service مسؤولية واحدة                    |
| **O** Open/Closed           | إضافة نوع block جديد للصفحات بكلاس جديد بدون تعديل الكود القديم     |
| **L** Liskov                | أي implementation للـ Contract يتبدل بدون تغيير سلوك المستهلك       |
| **I** Interface Segregation | واجهات صغيرة (RevalidationServiceInterface, MediaUploaderInterface) |
| **D** Dependency Inversion  | الـ Actions تعتمد على Contracts، والربط في AppServiceProvider       |

**ربط الـ Contract**

```php
// AppServiceProvider::register()
$this->app->bind(RevalidationServiceInterface::class, NextRevalidationService::class);
```

### 5.5 قواعد الكود

- `declare(strict_types=1)` في كل الملفات، والكلاسات `final` إلا لو في سبب للوراثة.
- كل Action فيها method واحدة عامة اسمها `execute`.
- ممنوع استخدام `request()` أو `auth()` جوه Actions؛ البيانات تيجي عبر DTO والمستخدم يتمرر كباراميتر.
- أي تعديل متعدد الجداول داخل `DB::transaction`.
- كل endpoint ليه FormRequest و Resource، ولا يتم إرجاع Model مباشرة.
- التزام بـ PSR-12 عبر Pint، و Larastan من level 6 وأعلى.

## 6. توحيد شكل الـ Response

### 6.1 الشكل الموحد

**نجاح**

```json
{
    "success": true,
    "message": "تمت العملية بنجاح",
    "data": {},
    "meta": {}
}
```

**قائمة مع pagination**

```json
{
    "success": true,
    "message": "",
    "data": [],
    "meta": {
        "current_page": 1,
        "per_page": 12,
        "total": 48,
        "last_page": 4
    }
}
```

**خطأ**

```json
{
    "success": false,
    "message": "البيانات المدخلة غير صحيحة",
    "code": "VALIDATION_ERROR",
    "errors": { "email": ["البريد الإلكتروني مطلوب"] }
}
```

### 6.3 معالجة الأخطاء مركزياً

في `bootstrap/app.php` بنحول كل الاستثناءات لنفس الشكل داخل `withExceptions`، مع `shouldRenderJsonWhen` لطلبات `api/*`.

| الاستثناء                                      | HTTP       | code                                                            |
| ---------------------------------------------- | ---------- | --------------------------------------------------------------- |
| ValidationException                            | 422        | VALIDATION_ERROR                                                |
| AuthenticationException                        | 401        | UNAUTHENTICATED                                                 |
| AuthorizationException                         | 403        | FORBIDDEN                                                       |
| ModelNotFoundException / NotFoundHttpException | 404        | NOT_FOUND                                                       |
| ThrottleRequestsException                      | 429        | TOO_MANY_REQUESTS                                               |
| ApiException (مخصص للـ domain)                 | حسب الحالة | كود خاص (مثلاً POST_ALREADY_PUBLISHED)                          |
| Throwable غير متوقع                            | 500        | SERVER_ERROR (رسالة عامة في الإنتاج، والتفاصيل في الـ logs فقط) |

## 7. المصادقة والصلاحيات

### 7.1 المصادقة

- **Sanctum Personal Access Tokens** عبر `Authorization: Bearer <token>`.
- مدة صلاحية الـ token قابلة للضبط من `config/sanctum.php`، مع إلغاء الـ token عند logout.
- الـ Public API مفتوح بدون مصادقة، لكن عليه rate limiting.

### 7.2 الأدوار

| الدور       | الوصف                                       |
| ----------- | ------------------------------------------- |
| super_admin | كل الصلاحيات بما فيها المستخدمين والإعدادات |
| editor      | إنشاء وتعديل ونشر كل المحتوى                |
| author      | إنشاء وتعديل مقالاته هو فقط بدون نشر        |
| support     | عرض ومتابعة طلبات التواصل فقط               |

### 7.3 مصفوفة الصلاحيات

صيغة الصلاحية: `resource.action` مثل `posts.create` و `posts.publish`.

| الوحدة                                                   | super_admin | editor     | author          | support     |
| -------------------------------------------------------- | ----------- | ---------- | --------------- | ----------- |
| الصفحات / المميزات / الباقات / FAQ / الشركاء / التكاملات | CRUD        | CRUD       | —               | —           |
| المدونة                                                  | CRUD + نشر  | CRUD + نشر | CRUD (الخاص به) | —           |
| التوثيق                                                  | CRUD + نشر  | CRUD + نشر | —               | —           |
| طلبات التواصل                                            | CRUD        | عرض        | —               | عرض + تحديث |
| الإعدادات                                                | CRUD        | عرض        | —               | —           |
| المستخدمين والأدوار                                      | CRUD        | —          | —               | —           |

كل Model ليه **Policy**، وبتتفحص في FormRequest أو عبر `authorize` في الـ Controller.

## 8. قاعدة البيانات

### 8.1 اتفاقيات عامة

- Engine: InnoDB، و Charset: `utf8mb4` و Collation: `utf8mb4_unicode_ci`.
- المفتاح الأساسي `id` (bigint unsigned)، و`timestamps` في كل الجداول، و`softDeletes` للمحتوى الأساسي (pages, posts, doc_articles).
- حالة المحتوى عبر Enum `ContentStatus`: `draft` / `published` / `archived` مع عمود `published_at`.
- الترتيب اليدوي عبر `sort_order` (unsigned int).
- البيانات المرنة (النقاط، الـ blocks، الوسام) في أعمدة `JSON`.
- `slug` فريد (unique) في أي جدول بيظهر في URL.
- FULLTEXT index على `posts (title, excerpt, content)` و `doc_articles (title, content)` للبحث.
- كل Foreign Key له index و `onDelete` محدد (cascade للأبناء، restrict للمراجع المشتركة).

### 8.2 الجداول

| الجدول                  | الأعمدة الأساسية                                                                                                                                                         | ملاحظات                                                 |
| ----------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------- |
| users                   | name, email (unique), password, is_active, last_login_at                                                                                                                 | الأدوار عبر spatie                                      |
| pages                   | slug (unique), title, seo_title, seo_description, status, published_at                                                                                                   | home, about, ...                                        |
| page_blocks             | page_id, type, data (JSON), sort_order, is_visible                                                                                                                       | أنواع: hero, features_grid, steps, stats, faq, cta, ... |
| feature_categories      | name, slug, sort_order                                                                                                                                                   | المبيعات، التواصل، الذكاء الاصطناعي، الإدارة والأمان    |
| features                | category_id, title, slug, summary, description, points (JSON), how_it_works (JSON), icon, sort_order, status                                                             | 14 قدرة                                                 |
| plans                   | name, slug, description, price_monthly, price_yearly, currency, trial_days, max_sales_users, max_leads, features (JSON), is_featured, badge, cta_url, sort_order, status | الأسعار decimal(10,2)                                   |
| faqs                    | group, question, answer, sort_order, status                                                                                                                              |                                                         |
| partners                | name, url, sort_order, status                                                                                                                                            | الشعار عبر media                                        |
| integrations            | title, slug, description, tags (JSON), icon, sort_order, status                                                                                                          | ERP، تاجر لينك، بوابات الدفع                            |
| post_categories         | name, slug, sort_order                                                                                                                                                   |                                                         |
| posts                   | category_id, author_id, title, slug, excerpt, content, reading_time, seo_title, seo_description, status, published_at                                                    | FULLTEXT                                                |
| doc_sections            | parent_id (nullable), title, slug, icon, sort_order, status                                                                                                              | شجرة بمستويين أو أكتر                                   |
| doc_articles            | section_id, title, slug, excerpt, content (LONGTEXT markdown), version, status, sort_order, published_at                                                                 | unique (section_id, slug) و FULLTEXT                    |
| contact_requests        | name, phone, email (nullable), message, source, status, assigned_to, notes, ip, user_agent                                                                               | الحالات: new, in_progress, done, spam                   |
| settings                | key (unique), value (JSON), group, is_public                                                                                                                             | الإعدادات العامة بتتعرض للفرونت                         |
| media                   | (spatie medialibrary)                                                                                                                                                    | صور المقالات والشركاء والصفحات                          |
| roles, permissions, ... | (spatie permission)                                                                                                                                                      |                                                         |
| personal_access_tokens  | (sanctum)                                                                                                                                                                |                                                         |

### 8.3 العلاقات

```text
users 1─N posts (author_id)          users 1─N contact_requests (assigned_to)
post_categories 1─N posts            pages 1─N page_blocks
feature_categories 1─N features      doc_sections 1─N doc_sections (parent_id)
doc_sections 1─N doc_articles        media (polymorphic) ─ posts, partners, pages, features
```

## 9. تصميم الـ API

### 9.1 الاتفاقيات

- **Base URL:** `https://api.saleszawy.com/api/v1`
- **Headers:** `Accept: application/json` دايماً، و`Accept-Language: ar` (أو `en` لو اتفعلت الترجمة).
- **Pagination:** `?page=1&per_page=12` (الافتراضي 12، والحد الأقصى 50).
- **Filtering:** `?filter[category]=b2b-sales`، **Sorting:** `?sort=-published_at`، **Include:** `?include=category,author` (عبر spatie/laravel-query-builder).
- الـ Public API بيرجع المحتوى المنشور (`published`) فقط.
- أسماء الحقول `snake_case`، والتواريخ بصيغة ISO 8601.

### 9.2 Public API (بدون مصادقة)

| Method | Endpoint                    | الوصف                                                    | Cache tag       |
| ------ | --------------------------- | -------------------------------------------------------- | --------------- |
| GET    | `/pages/{slug}`             | صفحة مع الـ blocks مرتبة                                 | pages           |
| GET    | `/feature-categories`       | تصنيفات المميزات                                         | features        |
| GET    | `/features`                 | المميزات، `filter[category]`                             | features        |
| GET    | `/features/{slug}`          | تفاصيل ميزة                                              | features        |
| GET    | `/plans`                    | الباقات والأسعار                                         | plans           |
| GET    | `/faqs`                     | الأسئلة الشائعة، `filter[group]`                         | faqs            |
| GET    | `/partners`                 | الشركاء والعملاء                                         | partners        |
| GET    | `/integrations`             | التكاملات                                                | integrations    |
| GET    | `/post-categories`          | تصنيفات المدونة                                          | posts           |
| GET    | `/posts`                    | المقالات مع pagination، `filter[category]` و `filter[q]` | posts           |
| GET    | `/posts/{slug}`             | مقال واحد                                                | posts           |
| GET    | `/docs/tree`                | شجرة أقسام ومقالات التوثيق للـ sidebar                   | docs            |
| GET    | `/docs/{section}/{article}` | مقال توثيق                                               | docs            |
| GET    | `/docs/search?q=`           | بحث في التوثيق                                           | بدون cache طويل |
| GET    | `/settings`                 | الإعدادات العامة (لوجو، واتساب، سوشيال، SEO عام)         | settings        |
| GET    | `/sitemap`                  | كل الـ slugs للصفحات والمقالات والتوثيق                  | sitemap         |
| POST   | `/contact-requests`         | إرسال طلب تواصل (rate limited + honeypot)                | —               |

### 9.3 Admin API (Bearer token + صلاحيات)

الـ prefix: `/api/v1/admin`

**المصادقة**

| Method | Endpoint         | الوصف                         |
| ------ | ---------------- | ----------------------------- |
| POST   | `/auth/login`    | تسجيل الدخول وإرجاع الـ token |
| POST   | `/auth/logout`   | إلغاء الـ token الحالي        |
| GET    | `/auth/me`       | بيانات المستخدم وصلاحياته     |
| PUT    | `/auth/password` | تغيير كلمة المرور             |

**الموارد (index, store, show, update, destroy)** `pages`، `pages/{page}/blocks`، `feature-categories`، `features`، `plans`، `faqs`، `partners`، `integrations`، `post-categories`، `posts`، `doc-sections`، `doc-articles`، `users`

**موارد بصلاحيات محدودة**

| Method          | Endpoint                                      | الوصف                                                               |
| --------------- | --------------------------------------------- | ------------------------------------------------------------------- |
| GET, GET, PATCH | `/contact-requests`، `/contact-requests/{id}` | عرض وتحديث الحالة والتعيين والملاحظات (بدون إنشاء أو حذف من الأدمن) |
| GET, PUT        | `/settings`                                   | قراءة وتحديث الإعدادات                                              |
| GET             | `/roles`                                      | قائمة الأدوار والصلاحيات                                            |

**إجراءات إضافية**

| Method | Endpoint                                  | الوصف                                       |
| ------ | ----------------------------------------- | ------------------------------------------- |
| PATCH  | `/{resource}/{id}/publish` و `/unpublish` | للمقالات والتوثيق والصفحات                  |
| PUT    | `/{resource}/reorder`                     | إعادة الترتيب، body: `{ "ids": [3, 1, 2] }` |
| PUT    | `/pages/{page}/blocks/reorder`            | ترتيب الـ blocks                            |
| POST   | `/media`                                  | رفع صورة (multipart)                        |
| DELETE | `/media/{id}`                             | حذف صورة                                    |
| POST   | `/cache/flush`                            | مسح الكاش (super_admin فقط)                 |

### 9.4 أمثلة

**GET /plans**

**POST /contact-requests**

```json
// Request (website = honeypot لازم يكون فاضي)
{ "name": "أحمد علي", "phone": "+201000000000", "message": "عايز عرض توضيحي", "website": "" }

// Response 201
{ "success": true, "message": "تم استلام طلبك وسنتواصل معك قريباً", "data": null, "meta": {} }
```

**POST /admin/auth/login**

```json
// Response 200
{
    "success": true,
    "message": "تم تسجيل الدخول",
    "data": {
        "token": "1|abc...",
        "token_type": "Bearer",
        "expires_at": "2026-11-20T10:00:00Z",
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@saleszawy.com",
            "roles": ["super_admin"],
            "permissions": ["posts.create"]
        }
    },
    "meta": {}
}
```

## 10. الـ Cache والـ Revalidation

### 10.1 الـ Cache

- Redis مع **cache tags** لكل مورد (`cms:posts`, `cms:docs`, ...)، والـ TTL الافتراضي ساعة.
- `CacheService::remember(tag, key, ttl, callback)` هو المدخل الوحيد للكاش داخل الـ Queries.
- Headers على الـ Public GET: `Cache-Control: public, max-age=60, stale-while-revalidate=300` مع ETag.

### 10.2 مسار التحديث بعد أي تعديل

```text
Admin يعدل/ينشر محتوى
  → Observer يطلق ContentChanged event
  → Listener يمسح cache tags المتأثرة
  → RevalidateFrontendJob (queued, 3 retries + backoff)
  → POST {REVALIDATE_ENDPOINT} على Next.js
       Header: X-Revalidate-Signature = HMAC-SHA256(body, REVALIDATE_SECRET)
       Body:   { "tags": ["posts", "post:slug-here"] }
```

### 10.3 خريطة الـ Tags

| الـ Model                 | الـ Tags                      |
| ------------------------- | ----------------------------- |
| Page / PageBlock          | pages, page:{slug}            |
| Feature / FeatureCategory | features                      |
| Plan                      | plans                         |
| Faq                       | faqs                          |
| Partner                   | partners                      |
| Integration               | integrations                  |
| Post / PostCategory       | posts, post:{slug}            |
| DocSection / DocArticle   | docs, doc:{section}/{article} |
| Setting                   | settings                      |

**المعاينة (Preview):** الفرونت يستخدم Admin token ويقرأ المسودات من Admin API (`GET /admin/posts/{id}`)، فمفيش حاجة تظهر للعامة قبل النشر.

## 11. الأمان

| الموضوع         | الإجراء                                                                                                                |
| --------------- | ---------------------------------------------------------------------------------------------------------------------- |
| Rate limiting   | Public: 60/دقيقة لكل IP — contact-requests: 5/دقيقة لكل IP — login: 5/دقيقة لكل email+IP — Admin: 120/دقيقة لكل مستخدم |
| CORS            | الدومينات المسموحة من `.env` فقط                                                                                       |
| طلبات التواصل   | Honeypot + validation صارم، وإضافة Turnstile/reCAPTCHA لو ظهر spam                                                     |
| المحتوى         | تنقية أي HTML داخل (Purifier) لو المحرر بيطلع HTML، والفرونت يعمل sanitize عند العرض                                   |
| رفع الملفات     | whitelist للأنواع (jpg, png, webp)، والحد الأقصى 5MB، ومنع SVG إلا بعد تنقيته، وتحويل الصور لـ webp                    |
| كلمات المرور    | Hashing افتراضي من Laravel، وحد أدنى 10 حروف، وقفل مؤقت بعد محاولات فاشلة                                              |
| الإعدادات       | الأسرار في `.env` فقط، و`APP_DEBUG=false` في الإنتاج، وHTTPS إجباري                                                    |
| Mass assignment | `$fillable` صريحة في كل Model                                                                                          |
| Audit log       | مرحلة لاحقة عبر spatie/laravel-activitylog لتسجيل من عدّل إيه ومتى                                                     |

## 12. الاختبارات والجودة

### 12.1 الاختبارات (PHPUnit)

- **Feature tests** لكل endpoint: نجاح، validation، مصادقة، صلاحيات.
- **Unit tests** للـ Actions والـ Services (مع Mockery أو `Http::fake()` للـ revalidation).
- Factories لكل الـ Models، و`RefreshDatabase` على قاعدة MySQL الاختبار.
- هدف تغطية: 80% على طبقة Actions.

### 12.2 بوابات الجودة (CI)

1. `composer lint` (Pint)
2. `composer analyse` (Larastan)
3. `composer test` (PHPUnit)

أي Pull Request لازم يعدي الثلاثة قبل الدمج.

### 12.3 الـ Git Workflow

- Branches: `main` (إنتاج) / `develop` / `feature/*`.
- Commits بصيغة Conventional Commits (`feat:`, `fix:`, `refactor:`, ...).
- Pull Request إجباري مع مراجعة واحدة على الأقل.

### 12.4 توثيق الـ API للفرونت

- **Scramble** بيولد OpenAPI تلقائياً من الـ Routes و FormRequests و Resources، وبيتعرض على `/docs/api` (مقفول بـ auth أو متاح في بيئة التطوير فقط).
- ملف `openapi.json` بيتشارك مع تيم Next لتوليد الـ types (مثلاً `openapi-typescript`).
- أي endpoint جديد لازم يتوثق بالـ docblocks قبل ما يتدمج.

##

## 14. خطة التنفيذ

| المرحلة                | المحتوى                                                                                                                                     | المخرجات                               |
| ---------------------- | ------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------- |
| 1. التأسيس             | تثبيت الحزم، ApiResponse و exception handling، Sanctum، الأدوار، CI                                                                         | مشروع جاهز + endpoint للـ login        |
| 2. المحتوى الأساسي     | Migrations و Models و Factories و Seeders (بمحتوى الموقع الحالي)، Public + Admin لـ features, plans, faqs, partners, integrations, settings | أول endpoints ينفع الفرونت يشتغل عليها |
| 3. الصفحات والـ Blocks | pages و page_blocks مع الترتيب                                                                                                              | الرئيسية ومن نحن من الـ API            |
| 4. المدونة             | posts و categories و media و بحث                                                                                                            | المدونة كاملة                          |
| 5. بوابة التوثيق       | doc_sections و doc_articles، الشجرة، البحث                                                                                                  | Docs portal كامل                       |
| 6. التواصل             | contact_requests + rate limits + honeypot                                                                                                   | فورم التواصل شغال                      |
| 7. الإنتاج             | Cache، Revalidation، Hardening، OpenAPI، تسليم للفرونت                                                                                      | جاهز للإطلاق                           |
