# Tổng Kết Triển Khai CI Backend Và Bài Học Kỹ Thuật

## Mục Đích

Tài liệu này ghi lại phần backend và CI đã triển khai ngày 2026-08-21: các
lỗi đã gặp, nguyên nhân, cách sửa, bước kiểm chứng và chủ đề cần tìm hiểu thêm.

## Refactor Backend Theo API Contract

Goal được migrate từ các field cũ:

```text
title, target_date, is_completed
```

sang các field theo API contract:

```text
name, type, target_value, unit, repeat_rule,
start_date, end_date, is_active, color, icon, tags
```

Những phần đã triển khai:

- Goal CRUD hỗ trợ filter, pagination và disable Goal.
- Goal Entry dùng `log_date`, `value` và `status` được tính lại khi tạo, sửa,
  hoặc xóa entry.
- Goal và Note hỗ trợ tags.
- Form Request xử lý validation tại ranh giới request.
- Policy bảo vệ các resource thuộc user hiện tại.
- Resource tạo JSON response ổn định, tách khỏi Eloquent serialization.
- API dùng chung success/error response envelope.

## Lỗi Migration PostgreSQL

### Lỗi

```text
column "value" cannot be cast automatically to type numeric
```

### Nguyên Nhân

Migration đổi tên field text `label` thành `value`, sau đó đổi trực tiếp kiểu
sang `decimal`. PostgreSQL không thể tự quyết định cách chuyển một chuỗi text
bất kỳ thành số.

### Cách Sửa

Migration được chuyển sang chiến lược add-copy-drop-rename:

1. Đổi tên `entry_date` thành `log_date`.
2. Thêm cột decimal mới tên `value_numeric`.
3. Copy các giá trị `label` hợp lệ sang cột mới.
4. Đưa giá trị cũ không phải số về `0`.
5. Xóa cột `label` cũ.
6. Đổi tên `value_numeric` thành `value`.

### Bài Học

Migration database là quá trình chuyển đổi dữ liệu, không chỉ là đổi schema.
Với PostgreSQL, khi thay đổi type mà dữ liệu cũ không tương thích, cần chiến
lược chuyển đổi rõ ràng.

### Chủ Đề Nên Tìm Hiểu

- Laravel Schema Builder `change()`
- PostgreSQL `ALTER TABLE ... USING`
- Forward-only migrations
- Data migration và rollback strategy

## Workflow CI Backend

Backend nằm trong thư mục `backend/`, nên lệnh CI cần dùng
`working-directory: backend`.

Backend job hiện thực hiện các bước:

1. Cài PHP và extension cho PostgreSQL.
2. Chạy PostgreSQL service dành cho CI.
3. Tạo `.env` tạm cho test từ `.env.example`.
4. Cài Composer dependencies.
5. Generate Laravel application key.
6. Chạy migration, PHPStan, PHP Insights và test.

### Bài Học Về File Environment

Không commit `.env` chứa secret. Tuy nhiên CI hoàn toàn có thể tạo `.env` tạm
từ `.env.example` hoặc `.env.ci` đã commit nếu file đó chỉ có giá trị test.
File tạm chỉ tồn tại trong GitHub Actions runner của job.

### Chủ Đề Nên Tìm Hiểu

- GitHub Actions `working-directory`
- GitHub Actions service containers
- Laravel test environment
- `.env.example`, `.env.ci` và GitHub Secrets

## Composer Và Static Analysis

### Lỗi

```text
./vendor/bin/phpstan: No such file or directory
```

### Nguyên Nhân

Workflow gọi PHPStan nhưng project chưa khai báo PHPStan trong dev dependency.
Do đó `composer install` không thể tạo binary trong `vendor/bin`.

### Cách Sửa

Backend đã bổ sung:

- `phpstan/phpstan`
- `larastan/larastan`
- `nunomaduro/phpinsights`
- `phpstan.neon`
- `composer.lock` được commit vào Git

Larastan cần thiết vì Laravel dùng Eloquent relationship và model property động,
những phần plain PHPStan không thể suy luận đầy đủ.

### Bài Học

`composer.json` mô tả version dependency được phép cài. `composer.lock` chốt
đúng version đã được kiểm chứng trong CI. Với Laravel application, nên commit
lock file để build có thể tái lập.

### Chủ Đề Nên Tìm Hiểu

- Composer `require-dev`
- Composer lock file
- Composer `allow-plugins`
- PHPStan levels
- Larastan

## PHPStan: Lỗi Và Cách Sửa

### Lỗi Runtime Thật

PHPStan báo `authorize()` không tồn tại trong API controller. Controller đang
gọi `$this->authorize()` nhưng base controller chưa dùng trait
`AuthorizesRequests` của Laravel.

Đã thêm trait vào base controller.

### Cải Thiện Type Information

Các thay đổi giúp static analysis hiểu code Laravel tốt hơn:

- Eloquent relationship có return type `BelongsTo` hoặc `HasMany`.
- Model có type information cho date và decimal cast khi cần.
- API Resource lấy model typed từ `$this->resource`, thay vì chỉ dựa vào
  dynamic forwarding của `JsonResource`.
- Goal Entry aggregation dùng callback có type rõ ràng.

### Kiểm Chứng

```text
PHPStan level 5: 0 errors
Laravel tests: 2 passed
```

### Bài Học

Test chạy những kịch bản cụ thể. Static analysis kiểm tra các kiểu code có thể
xảy ra trên toàn bộ source, nên có thể phát hiện method không tồn tại trước khi
route đó được cover bằng test.

### Chủ Đề Nên Tìm Hiểu

- Laravel `AuthorizesRequests`
- Laravel Policies
- Eloquent relationship return types
- Laravel JsonResource
- Static analysis và automated tests

## Lỗi YAML Trong Workflow

### Lỗi

```text
Invalid workflow file
YAML syntax error on line 108
```

### Nguyên Nhân Và Cách Sửa

Key `run` trong PHPStan step bị thụt lề sâu hơn `working-directory`. YAML dùng
indentation để mô tả cấu trúc, nên hai key này phải cùng cấp:

```yaml
- name: PHPStan
  working-directory: backend
  run: ./vendor/bin/phpstan analyse app database --level=5 --memory-limit=1G
```

### Bài Học

YAML nhạy với whitespace. Chỉ một lỗi indentation cũng làm GitHub Actions chặn
toàn bộ workflow trước khi PHP hoặc Laravel kịp chạy.

## PHP Insights

### Lỗi

```text
First, publish the configuration using: php artisan vendor:publish
```

### Cách Sửa

Đã publish `config/insights.php` từ PHP Insights Laravel service provider.

### Điểm Chất Lượng Hiện Tại

```text
Code:         96.7%
Complexity:   96.7%
Architecture: 93.8%
Style:        92.7%
Security:     0 issues trên CI
```

Mọi metric đều vượt ngưỡng CI là 90.

### Vì Sao GitHub Hiện Error Nhưng CI Vẫn Pass

Formatter `github-action` tạo annotation cho từng finding như line dài, inline
condition, unused import, braces và complexity. Formatter gắn finding dưới dạng
error annotation, nhưng process vẫn trả exit code 0 nếu các quality threshold
đều đạt và không có dependency security issue.

```text
Finding không đồng nghĩa với CI fail.
Exit code mới quyết định GitHub Actions pass hay fail.
```

### Chủ Đề Nên Tìm Hiểu

- PHP Insights metrics và presets
- Quality gates
- Cyclomatic complexity
- GitHub Actions annotations
- Laravel Pint so với PHP Insights

## Ví Dụ Code Theo Từng Lỗi

### 1. PostgreSQL Không Tự Cast Text Sang Decimal

Đoạn migration cũ gây lỗi:

```php
Schema::table('goal_entries', function (Blueprint $table) {
    $table->renameColumn('label', 'value');
});

Schema::table('goal_entries', function (Blueprint $table) {
    $table->decimal('value', 10, 2)->default(0)->change();
});
```

PostgreSQL không biết chuỗi như `"done"` hoặc `"morning log"` phải đổi thành
số nào, nên báo `column "value" cannot be cast automatically to type numeric`.

Đoạn đã sửa:

```php
$table->decimal('value_numeric', 10, 2)->default(0);

DB::table('goal_entries')->get()->each(function (object $entry): void {
    $value = preg_match('/^\d+(\.\d+)?$/', (string) $entry->label) === 1
        ? $entry->label
        : '0';

    DB::table('goal_entries')->where('id', $entry->id)
        ->update(['value_numeric' => $value]);
});

$table->dropColumn('label');
$table->renameColumn('value_numeric', 'value');
```

### 2. CI Copy Environment Sai Thư Mục

Đoạn template cũ:

```yaml
- name: Copy .env file
  run: cp .env.ci .env

- name: Run tests
  run: php artisan test
```

Repo đặt Laravel trong `backend/`, nên hai lệnh trên tìm `.env.ci` và `artisan`
ở root repository. Ngoài ra project không có `.env.ci` ở root.

Đoạn đã sửa:

```yaml
- name: Copy test environment
  working-directory: backend
  run: cp .env.example .env

- name: Run tests
  working-directory: backend
  run: php artisan test
```

### 3. PHPStan Binary Không Tồn Tại

Workflow gọi binary:

```yaml
run: ./vendor/bin/phpstan analyse app database --level=5 --memory-limit=1G
```

Nhưng `composer.json` cũ không khai báo package:

```json
"require-dev": {
  "phpunit/phpunit": "^12.5.12"
}
```

Vì vậy `composer install` không thể tạo `vendor/bin/phpstan`.

Đoạn đã bổ sung:

```json
"require-dev": {
  "larastan/larastan": "^3.10",
  "nunomaduro/phpinsights": "^2.14",
  "phpstan/phpstan": "^2.2",
  "phpunit/phpunit": "^12.5.12"
}
```

### 4. YAML Sai Indentation

Đoạn lỗi:

```yaml
- name: PHPStan
  working-directory: backend
    run: ./vendor/bin/phpstan analyse app database --level=5 --memory-limit=1G
```

`run` bị thụt sâu hơn `working-directory`, nên GitHub báo `Invalid workflow file`.

Đoạn đúng:

```yaml
- name: PHPStan
  working-directory: backend
  run: ./vendor/bin/phpstan analyse app database --level=5 --memory-limit=1G
```

### 5. Controller Gọi `authorize()` Nhưng Không Có Trait

Controller API dùng:

```php
$this->authorize('view', $goal);
```

Nhưng base controller cũ chỉ có helper response:

```php
abstract class Controller
{
    protected function success(...): JsonResponse
    {
        // ...
    }
}
```

PHPStan báo `Call to an undefined method ...::authorize()`. Đây cũng là lỗi
runtime thật nếu request đi vào endpoint có authorization.

Đoạn đã sửa:

```php
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;
}
```

### 6. Resource Dựa Vào Dynamic Forwarding

Đoạn Resource cũ:

```php
class GoalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'start_date' => $this->start_date?->format('Y-m-d'),
        ];
    }
}
```

Laravel forward `$this->id` sang model ở runtime, nhưng PHPStan không biết
Resource đang bọc model nào. Vì vậy nó báo undefined property và method.

Đoạn đã sửa:

```php
public function toArray(Request $request): array
{
    $goal = $this->goal();

    return [
        'id' => $goal->id,
        'name' => $goal->name,
        'start_date' => $goal->start_date->format('Y-m-d'),
    ];
}

private function goal(): Goal
{
    return $this->resource;
}
```

### 7. Eloquent Relation Thiếu Return Type

Đoạn cũ:

```php
public function goal()
{
    return $this->belongsTo(Goal::class);
}
```

PHPStan khó suy luận `$goalEntry->goal` là `Goal`, đặc biệt khi code dùng field
hoặc method của relation đó.

Đoạn đã sửa:

```php
/** @return BelongsTo<Goal, $this> */
public function goal(): BelongsTo
{
    return $this->belongsTo(Goal::class);
}
```

### 8. PHP Insights Chưa Có Config

Workflow gọi:

```yaml
run: php artisan insights --no-interaction --min-quality=90
```

Nhưng chưa có `config/insights.php`, nên package báo:

```text
First, publish the configuration using: php artisan vendor:publish
```

Đã chạy:

```bash
php artisan vendor:publish \
  --provider="NunoMaduro\PhpInsights\Application\Adapters\Laravel\InsightsServiceProvider" \
  --tag=config
```

Lệnh này tạo `backend/config/insights.php` để PHP Insights biết preset, rule,
exclude path và requirement phải dùng.

### 9. PHP Insights Hiện `Error` Nhưng Job Vẫn Xanh

Workflow dùng formatter:

```yaml
run: php artisan insights --no-interaction \
  --min-quality=90 --min-complexity=90 \
  --min-architecture=90 --min-style=90 \
  --format=github-action
```

Ví dụ finding:

```php
if ($request->has('active')) $query->where('is_active', true);
```

PHP Insights tạo GitHub annotation `Error: [Inline control structure]`, nhưng
đây là style finding. Job vẫn pass vì điểm Style là `92.7%`, cao hơn threshold
`90%`, và security check trên CI không tìm thấy dependency vulnerability.

## Checklist Kiểm Chứng Cuối

```text
PHP syntax checks: pass
Migration fresh test: pass
Migration rollback test: pass
Laravel tests: pass
PHPStan level 5: 0 errors
PHP Insights: mọi score threshold đã pass
```

## Hướng Tiếp Theo

1. Sửa dần PHP Insights findings, bắt đầu từ format và unused import.
2. Thêm Feature Test cho policy authorization và Goal Entry status.
3. Khi phù hợp, thêm PostgreSQL-specific migration test vào CI.
4. Quyết định giữ GitHub Actions annotation hay chỉ hiển thị console output.
5. Chỉ tăng quality threshold sau khi giảm style backlog hiện tại.
