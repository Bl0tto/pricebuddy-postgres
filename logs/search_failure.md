# UnexpectedValueException - Internal Server Error

The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: The stream or file "/app/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
The exception occurred while attempting to log: SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type uuid: "searchJobDispatched"
CONTEXT:  unnamed portal parameter $4 = '...' (Connection: pgsql, SQL: delete from "notifications" where "notifications"."notifiable_type" = App\Models\User and "notifications"."notifiable_id" = 1 and "notifications"."notifiable_id" is not null and "data"->>'format' = filament and "id" = searchJobDispatched)
Context: {"userId":1,"exception":{"errorInfo":["22P02",7,"ERROR:  invalid input syntax for type uuid: \"searchJobDispatched\"\nCONTEXT:  unnamed portal parameter $4 = '...'"],"connectionName":"pgsql"}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}
Context: {"userId":1,"exception":{}}

PHP 8.4.5
Laravel 12.43.1
192.168.10.243:8011

## Stack Trace

0 - vendor/monolog/monolog/src/Monolog/Handler/StreamHandler.php:156
1 - vendor/monolog/monolog/src/Monolog/Handler/AbstractProcessingHandler.php:44
2 - vendor/monolog/monolog/src/Monolog/Logger.php:391
3 - vendor/monolog/monolog/src/Monolog/Logger.php:646
4 - vendor/laravel/framework/src/Illuminate/Log/Logger.php:183
5 - vendor/laravel/framework/src/Illuminate/Log/Logger.php:96
6 - vendor/laravel/framework/src/Illuminate/Log/LogManager.php:703
7 - vendor/laravel/framework/src/Illuminate/Foundation/Exceptions/Handler.php:402
8 - vendor/laravel/framework/src/Illuminate/Foundation/Exceptions/Handler.php:365
9 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:562
10 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:146
11 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1220
12 - public/index.php:17

## Request

POST /livewire/update

## Headers

* **host**: 192.168.10.243:8011
* **connection**: keep-alive
* **content-length**: 1598
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36
* **content-type**: application/json
* **x-livewire**: 
* **accept**: */*
* **sec-gpc**: 1
* **accept-language**: en-US,en;q=0.9
* **origin**: http://192.168.10.243:8011
* **referer**: http://192.168.10.243:8011/admin/products/2?searchQuery=MSI+MAG+B850+TOMAHAWK+MAX+WIFI+ATX+Motherboard
* **accept-encoding**: gzip, deflate
* **cookie**: organizrLanguage=en; rl_page_init_referrer=RudderEncrypt%3AU2FsdGVkX1%2FA5T3lReWHpCX%2BpMGDiqBy1LAHcL8ScQnF1Gb18ZLgyHSlteg3cyY6; rl_page_init_referring_domain=RudderEncrypt%3AU2FsdGVkX19WrynQNQMNafCo2hbLdKyfGZbf3dsLuqb%2FLjMT28WWdhiU%2FLSKhFPI; rl_anonymous_id=RudderEncrypt%3AU2FsdGVkX1%2FLfsKmW%2BSkXr4UQfP0ogFqBbsT%2B%2BlyPas7Z4sePaWaw%2FGHZIPqyn0UzvX%2BsZ04Ha8USP0u6IfyoA%3D%3D; rl_user_id=RudderEncrypt%3AU2FsdGVkX18%2F0v2Kq%2F11Fprfk6uFfInHpssXGSEhYhoiXUDmqaJyynWCncJjtRUgxNLd0U0W38V8CcFRQLWlAUXylJGvv%2FrFBWYQq9l6XTmYV%2BDna7CECKzvEcY9iI96ZHOicgIGoEtjw4jVJ3WzlioQ8%2FV5Ypj8Nw85ifkIVdg%3D; rl_trait=RudderEncrypt%3AU2FsdGVkX1%2BXIsr5Wvv3yzMOj6Wo8inCqfMi%2BNcYTtnKcnOT2QNQvchlagjRBaoeQ3u8MzRNgQB88UWU%2BE0neHyZBAd0n7V5Y64IKPkhgkM9uXYJ2Nk7uWl%2Fn%2FVEbdt8CHALYJxPad8lCG3KMCBNtbNYRncPzRBwZUx8nQO9riE%3D; rl_session=RudderEncrypt%3AU2FsdGVkX18sg%2B2%2BruNM9JpeNFpMDFQdyNRf7WJuO5J2lnzI6h3Xcaz4XwntH%2BJFb9iSWTp5O1QKoVT%2BLq88%2BwYHhBTpqnrYwBYIixGnh7Nw63M9kSC%2BMhZIQ1Fl56Ms3FMpiAjlxV15gJrkly4ubw%3D%3D; organizr_user_uuid=fb8105e1-f603-4283-b4b2-fbb53bf33235; XSRF-TOKEN=eyJpdiI6ImsvaFRYUFdrQW0yQkl4SDFrU1Yva1E9PSIsInZhbHVlIjoiVjNYTG9qalVSd3BBMnlMcno0c0JickNKY1JWT2t2a2M5WEVMQWhBL2hrc21mbXNidE52cDBIclNSSFVEL2hTRFNpQjZaWGRVcDZHRWo2emZyVWthS1B5c2tBZ1pRTi9lWmpMTmlvMkUrQkg2MktYRDZWUkkyNEFxQnpBaHBVNTkiLCJtYWMiOiIxYjVjODRjMzIzNDQ2NWIzZWY2MGM3MWRmYmJjODhhNTAzYWE3NTY1MWY0YTQyNWQ4MDNmYTg2M2QxN2Y1M2I2IiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6IjJYQjRnQld3MytXR09TTW45b0pKTmc9PSIsInZhbHVlIjoiQjJBdDNXd1ZDd21uVnhZd205akd3SDNsa0NjNTFwd1Y2eVQxejBLeFBleGVzSXJCS29OUmZqSUlOTU8wQ2tmZGVxbUlBNktNOS8ybDZwVTJ5NUVOaUxqZ0R1cG9MYlpGS201ZjAxbkg1ZDZqaTd5VHJZdXJUNGNUWEF3NU9LWTEiLCJtYWMiOiIyZmFmM2I1OWI4YzY3NGJhOTVjZjVhZmNkZjk5ZTcwYTA1ZGU2NTllNDk5ZTc1MGE1OTk0NmZlZGUzMTA1M2YwIiwidGFnIjoiIn0%3D

## Route Context

controller: Livewire\Mechanisms\HandleRequests\HandleRequests@handleUpdate
route name: livewire.update
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

* pgsql - select exists (select 1 from pg_class c, pg_namespace n where n.nspname = current_schema() and c.relname = 'settings' and c.relkind in ('r', 'p') and n.oid = c.relnamespace) (18.07 ms)
* pgsql - select "name", "payload" from "settings" where "group" = 'app' (1.49 ms)
* pgsql - select exists (select 1 from pg_class c, pg_namespace n where n.nspname = current_schema() and c.relname = 'settings' and c.relkind in ('r', 'p') and n.oid = c.relnamespace) (0.89 ms)
* pgsql - select exists (select 1 from pg_class c, pg_namespace n where n.nspname = current_schema() and c.relname = 'settings' and c.relkind in ('r', 'p') and n.oid = c.relnamespace) (0.64 ms)
* pgsql - select * from "sessions" where "id" = '4BhaSyotdo8f8o2MEUCtcfoGWUfGPQKlXaUoo7Bj' limit 1 (1.04 ms)
* pgsql - select * from "users" where "id" = 1 limit 1 (1 ms)
