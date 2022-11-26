# Panduan Instalasi Task DOT Onboarding [Laravel]

by : M. Azzam Azizi <azzamazizi09@gmail.com>


## Langkah 1 : Konfigurasi
atur `.env`, pastikan nama database : ```onboarding_movie```
```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=onboarding_movie
DB_USERNAME=root
DB_PASSWORD=
```
kemudian jalankan `composer update` untuk unduh resource vendor
## Langkah 2 : Migrate Database
jalankan perintah
```sh
php artisan migrate
```
maka proses import database akan dijalankan
## Langkah 3 : Jalankan service laravel
jalankan serve laravel
```sh
php artisan serve --port 8081
```
port ```8081``` yang saya gunakan.
## Langkah 4 : Jalankan Database Seeder
pertama jalankan seeder Studio dengan perintah :
```sh
php artisan db:seed StudiosSeeder
```
kemudian jalankan seeder Tag dengan perintah :
```sh
php artisan db:seed TagsSeeder
```
## Langkah 5 : Jalankan Scheduler
scheduler digunakan untuk import data movies, jalankan perintah :
```sh
php artisan schedule:work
```
proses import data setiap 1 menit, untuk log data bisa dilihat di directory ```/storage/logs/laravel.log```
## Langkah 6 : Atur Pagination
dikarenakan pagination saya custom, ada setting yg perlu disesuaikan di directory ```/vendor/dvsauto/laravel-json-paginate/src/app/Providers/JsonPaginateServiceProvider.php```

> `ubah data` 
```sh
return [
    'data'      =>  $paginate['data'],
    'paginator' =>  [
        'current_page'  =>  $paginate['current_page'],

        'prev_page'     =>  $prev_page,
        'next_page'     =>  $next_page,

        'first_page'    =>  1,
        'is_first_page' =>  $is_first_page,

        'last_page'     =>  $paginate['last_page'],
        'is_last_page'  =>  $is_last_page,

        'page_keys'     =>  $page_keys,

        'from_item'     =>  $paginate['from'],
        'to_item'       =>  $paginate['to'],
        'total_items'   =>  $paginate['total'],
        'amount'        =>  $amount,
        'per_page'      =>  $per_page,
        'display_items' =>  count($paginate['data']),
    ]
];
```

> `menjadi`

```sh
return [
    'items'      =>  $paginate['data'],
    'pagination' =>  [
        'page'  =>  $paginate['current_page'],
        'per_page'      =>  $per_page,
        'total_items'   =>  $paginate['total'],
        'total_pages'     =>  $paginate['last_page'],
        'prev_page_link'     =>  $paginate['prev_page_url'] ?? null,
        'next_page_link'     =>  $paginate['next_page_url'] ?? null,
    ]
];
```

# - - - Selesai- - -

# Tambahan
## Issue Error
perlu ubah setting di `app/Providers/AppServiceProvider.php`, tambahkan didalam function boot :
```sh
Schema::defaultStringLength(191);
```


ubah tipe data di import database 
- enum menjadi char/varchar
- json menjadi char/varchar
