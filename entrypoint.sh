#!/bin/bash
set -e

# Tự động tạo link lưu trữ và chạy migration khi container khởi động
php artisan storage:link || true
php artisan migrate --force || true
php artisan db:seed --force || true

# Khởi chạy server Laravel
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}