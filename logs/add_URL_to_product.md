# TypeError - Internal Server Error

App\Models\Store::scopeDomainFilter(): Argument #2 ($domains) must be of type array|string, null given, called in /app/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php on line 1757

PHP 8.4.5
Laravel 12.43.1
192.168.10.243:8011

## Stack Trace

0 - app/Models/Store.php:117
1 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php:1757
2 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:1621
3 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:1602
4 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:1620
5 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:2231
6 - app/Services/ScrapeUrl.php:209
7 - app/Rules/StoreUrl.php:17
8 - vendor/laravel/framework/src/Illuminate/Validation/InvokableValidationRule.php:101
9 - vendor/laravel/framework/src/Illuminate/Validation/Validator.php:902
10 - vendor/laravel/framework/src/Illuminate/Validation/Validator.php:678
11 - vendor/laravel/framework/src/Illuminate/Validation/Validator.php:481
12 - vendor/laravel/framework/src/Illuminate/Validation/Validator.php:516
13 - vendor/laravel/framework/src/Illuminate/Validation/Validator.php:558
14 - vendor/livewire/livewire/src/Features/SupportValidation/HandlesValidation.php:266
15 - vendor/filament/forms/src/Concerns/InteractsWithForms.php:224
16 - vendor/filament/forms/src/Concerns/CanBeValidated.php:130
17 - vendor/filament/forms/src/Concerns/HasState.php:239
18 - vendor/filament/actions/src/Concerns/InteractsWithActions.php:87
19 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:36
20 - vendor/laravel/framework/src/Illuminate/Container/Util.php:43
21 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:96
22 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:35
23 - vendor/livewire/livewire/src/Wrapped.php:23
24 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:492
25 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:101
26 - vendor/livewire/livewire/src/LivewireManager.php:102
27 - vendor/livewire/livewire/src/Mechanisms/HandleRequests/HandleRequests.php:94
28 - vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:46
29 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:265
30 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:211
31 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:822
32 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
33 - vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php:50
34 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
35 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php:87
36 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
37 - vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php:48
38 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
39 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:120
40 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:63
41 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
42 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php:36
43 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
44 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php:74
45 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
46 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
47 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
48 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:800
49 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:764
50 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:753
51 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:200
52 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
53 - vendor/livewire/livewire/src/Features/SupportDisablingBackButtonCache/DisableBackButtonCacheMiddleware.php:19
54 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
55 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php:27
56 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
57 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php:47
58 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
59 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php:27
60 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
61 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php:109
62 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
63 - vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php:48
64 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
65 - vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php:58
66 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
67 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php:22
68 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
69 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php:26
70 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
71 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
72 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:175
73 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:144
74 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1220
75 - public/index.php:17

## Request

POST /livewire/update

## Headers

* **host**: 192.168.10.243:8011
* **connection**: keep-alive
* **content-length**: 2720
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36
* **content-type**: application/json
* **x-livewire**: 
* **accept**: */*
* **sec-gpc**: 1
* **accept-language**: en-US,en;q=0.9
* **origin**: http://192.168.10.243:8011
* **referer**: http://192.168.10.243:8011/admin/products/2
* **accept-encoding**: gzip, deflate
* **cookie**: organizrLanguage=en; rl_page_init_referrer=RudderEncrypt%3AU2FsdGVkX1%2FA5T3lReWHpCX%2BpMGDiqBy1LAHcL8ScQnF1Gb18ZLgyHSlteg3cyY6; rl_page_init_referring_domain=RudderEncrypt%3AU2FsdGVkX19WrynQNQMNafCo2hbLdKyfGZbf3dsLuqb%2FLjMT28WWdhiU%2FLSKhFPI; rl_anonymous_id=RudderEncrypt%3AU2FsdGVkX1%2FLfsKmW%2BSkXr4UQfP0ogFqBbsT%2B%2BlyPas7Z4sePaWaw%2FGHZIPqyn0UzvX%2BsZ04Ha8USP0u6IfyoA%3D%3D; rl_user_id=RudderEncrypt%3AU2FsdGVkX18%2F0v2Kq%2F11Fprfk6uFfInHpssXGSEhYhoiXUDmqaJyynWCncJjtRUgxNLd0U0W38V8CcFRQLWlAUXylJGvv%2FrFBWYQq9l6XTmYV%2BDna7CECKzvEcY9iI96ZHOicgIGoEtjw4jVJ3WzlioQ8%2FV5Ypj8Nw85ifkIVdg%3D; rl_trait=RudderEncrypt%3AU2FsdGVkX1%2BXIsr5Wvv3yzMOj6Wo8inCqfMi%2BNcYTtnKcnOT2QNQvchlagjRBaoeQ3u8MzRNgQB88UWU%2BE0neHyZBAd0n7V5Y64IKPkhgkM9uXYJ2Nk7uWl%2Fn%2FVEbdt8CHALYJxPad8lCG3KMCBNtbNYRncPzRBwZUx8nQO9riE%3D; rl_session=RudderEncrypt%3AU2FsdGVkX18sg%2B2%2BruNM9JpeNFpMDFQdyNRf7WJuO5J2lnzI6h3Xcaz4XwntH%2BJFb9iSWTp5O1QKoVT%2BLq88%2BwYHhBTpqnrYwBYIixGnh7Nw63M9kSC%2BMhZIQ1Fl56Ms3FMpiAjlxV15gJrkly4ubw%3D%3D; n8n-auth=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjAyMGRlZjhhLTRjZjgtNDZmOS05MTMzLTk1ZmUxZDFlMGFjMyIsImhhc2giOiIycVppYW4vdDVuIiwiYnJvd3NlcklkIjoib1RvYm9CSUtpSTJzWG1iaHB4MmJ4cTI1VTZ0ZTd6b0JtNlp4MXFzMnQwUT0iLCJ1c2VkTWZhIjpmYWxzZSwiaWF0IjoxNzY2NzUwMzIzLCJleHAiOjE3NjczNTUxMjN9.FfeDubx3guSjDFWusemGyZzGOH2WfJPWvHMJL5cwZPw; organizr_user_uuid=fb8105e1-f603-4283-b4b2-fbb53bf33235; XSRF-TOKEN=eyJpdiI6Ik16eUZrWWF6by9hRS8vVFJwTHp0VkE9PSIsInZhbHVlIjoiOE1Bd2FhV3d0TDBHckVFZ0JGSFM3RitvcWZXVDBSZ3ZkL01JRHM5eUZUTlNNMEY4dVlTNTEvNUZ2U21mWWZFTldBQndxOTVNUlhTZ05DNGFWR3pXZDQyQ1ZDdFdReVArQkJZYU9RcENFUWI5Ti9SMEQzMG14ZFg3VVUza0dpejYiLCJtYWMiOiJmZmU5ZTYwNjAwZmZiMzZlNzE1MmViYTUzZmI0NmEwNjhiMzRjYzA0NzEzNzJlMzZmN2E2NzU1OTliOTVhMDViIiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6IkV0MlZnbGg5UzNXTXJ2TFpEVDlnaEE9PSIsInZhbHVlIjoiSFVud0JXakJ2ZjAxMEViM3BVVlBoZ2toVmVPdzVpaTR6VG53citseDc3cG9zQnZRbGxWL1BpWStjbEVYTFFGYnlDRDdmSWNqSkJhanBZeW54N0tmT0l1alo3c0RLTE9IKzQ5K0JXK3ROd2FZcWExR01TdkdCenJ2SlFXQmQzV2YiLCJtYWMiOiI3Y2ZkOTBjMTdmMGY3NDAyMjM0YmZhOWIxNWQ1MjQ4ODhhMTUxMTEwY2Y3ZDExMGQwYWJmMDA3YTkzZTYxZmI5IiwidGFnIjoiIn0%3D

## Route Context

controller: Livewire\Mechanisms\HandleRequests\HandleRequests@handleUpdate
route name: livewire.update
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

* pgsql - select exists (select 1 from pg_class c, pg_namespace n where n.nspname = current_schema() and c.relname = 'settings' and c.relkind in ('r', 'p') and n.oid = c.relnamespace) (19.64 ms)
* pgsql - select "name", "payload" from "settings" where "group" = 'app' (1.44 ms)
* pgsql - select exists (select 1 from pg_class c, pg_namespace n where n.nspname = current_schema() and c.relname = 'settings' and c.relkind in ('r', 'p') and n.oid = c.relnamespace) (0.77 ms)
* pgsql - select exists (select 1 from pg_class c, pg_namespace n where n.nspname = current_schema() and c.relname = 'settings' and c.relkind in ('r', 'p') and n.oid = c.relnamespace) (0.52 ms)
* pgsql - select * from "sessions" where "id" = '4BhaSyotdo8f8o2MEUCtcfoGWUfGPQKlXaUoo7Bj' limit 1 (0.98 ms)
* pgsql - select * from "users" where "id" = 1 limit 1 (1.06 ms)
* pgsql - select * from "products" where "products"."id" = 2 limit 1 (0.87 ms)
* pgsql - select exists (select 1 from pg_class c, pg_namespace n where n.nspname = current_schema() and c.relname = 'settings' and c.relkind in ('r', 'p') and n.oid = c.relnamespace) (0.96 ms)
* pgsql - select exists (select 1 from pg_class c, pg_namespace n where n.nspname = current_schema() and c.relname = 'settings' and c.relkind in ('r', 'p') and n.oid = c.relnamespace) (0.76 ms)
* pgsql - insert into "log_messages" ("level", "level_name", "message", "logged_at", "context", "extra") values (400, 'ERROR', 'App\Models\Store::scopeDomainFilter(): Argument #2 ($domains) must be of type array|string, null given, called in /app/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php on line 1757', '2026-01-02 02:34:09', '{"userId":1,"exception":"TypeError: App\Models\Store::scopeDomainFilter(): Argument #2 ($domains) must be of type array|string, null given, called in \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Eloquent\/Model.php on line 1757 and defined in \/app\/app\/Models\/Store.php:117\nStack trace:\n#0 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Eloquent\/Model.php(1757): App\Models\Store->scopeDomainFilter(Object(Illuminate\Database\Eloquent\Builder), NULL)\n#1 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Eloquent\/Builder.php(1621): Illuminate\Database\Eloquent\Model->callNamedScope('domainFilter', Array)\n#2 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Eloquent\/Builder.php(1602): Illuminate\Database\Eloquent\Builder->{closure:Illuminate\Database\Eloquent\Builder::callNamedScope():1620}(Object(Illuminate\Database\Eloquent\Builder), NULL)\n#3 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Eloquent\/Builder.php(1620): Illuminate\Database\Eloquent\Builder->callScope(Object(Closure), Array)\n#4 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Eloquent\/Builder.php(2231): Illuminate\Database\Eloquent\Builder->callNamedScope('domainFilter', Array)\n#5 \/app\/app\/Services\/ScrapeUrl.php(209): Illuminate\Database\Eloquent\Builder->__call('domainFilter', Array)\n#6 \/app\/app\/Rules\/StoreUrl.php(17): App\Services\ScrapeUrl->getStore()\n#7 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Validation\/InvokableValidationRule.php(101): App\Rules\StoreUrl->validate('mountedActionsD...', 'www.computerall...', Object(Closure))\n#8 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Validation\/Validator.php(902): Illuminate\Validation\InvokableValidationRule->passes('mountedActionsD...', 'www.computerall...')\n#9 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Validation\/Validator.php(678): Illuminate\Validation\Validator->validateUsingCustomRule('mountedActionsD...', 'www.computerall...', Object(Illuminate\Validation\InvokableValidationRule))\n#10 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Validation\/Validator.php(481): Illuminate\Validation\Validator->validateAttribute('mountedActionsD...', Object(Illuminate\Validation\InvokableValidationRule))\n#11 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Validation\/Validator.php(516): Illuminate\Validation\Validator->passes()\n#12 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Validation\/Validator.php(558): Illuminate\Validation\Validator->fails()\n#13 \/app\/vendor\/livewire\/livewire\/src\/Features\/SupportValidation\/HandlesValidation.php(266): Illuminate\Validation\Validator->validate()\n#14 \/app\/vendor\/filament\/forms\/src\/Concerns\/InteractsWithForms.php(224): Livewire\Component->validate(Array, Array, Array)\n#15 \/app\/vendor\/filament\/forms\/src\/Concerns\/CanBeValidated.php(130): Filament\Pages\BasePage->validate(Array, Array, Array)\n#16 \/app\/vendor\/filament\/forms\/src\/Concerns\/HasState.php(239): Filament\Forms\ComponentContainer->validate()\n#17 \/app\/vendor\/filament\/actions\/src\/Concerns\/InteractsWithActions.php(87): Filament\Forms\ComponentContainer->getState(true, Object(Closure))\n#18 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php(36): Filament\Pages\BasePage->callMountedAction(Array)\n#19 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/Util.php(43): Illuminate\Container\BoundMethod::{closure:Illuminate\Container\BoundMethod::call():35}()\n#20 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php(96): Illuminate\Container\Util::unwrapIfClosure(Object(Closure))\n#21 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php(35): Illuminate\Container\BoundMethod::callBoundMethod(Object(Illuminate\Foundation\Application), Array, Object(Closure))\n#22 \/app\/vendor\/livewire\/livewire\/src\/Wrapped.php(23): Illuminate\Container\BoundMethod::call(Object(Illuminate\Foundation\Application), Array, Array)\n#23 \/app\/vendor\/livewire\/livewire\/src\/Mechanisms\/HandleComponents\/HandleComponents.php(492): Livewire\Wrapped->__call('callMountedActi...', Array)\n#24 \/app\/vendor\/livewire\/livewire\/src\/Mechanisms\/HandleComponents\/HandleComponents.php(101): Livewire\Mechanisms\HandleComponents\HandleComponents->callMethods(Object(App\Filament\Resources\ProductResource\Pages\ViewProduct), Array, Object(Livewire\Mechanisms\HandleComponents\ComponentContext))\n#25 \/app\/vendor\/livewire\/livewire\/src\/LivewireManager.php(102): Livewire\Mechanisms\HandleComponents\HandleComponents->update(Array, Array, Array)\n#26 \/app\/vendor\/livewire\/livewire\/src\/Mechanisms\/HandleRequests\/HandleRequests.php(94): Livewire\LivewireManager->update(Array, Array, Array)\n#27 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/ControllerDispatcher.php(46): Livewire\Mechanisms\HandleRequests\HandleRequests->handleUpdate()\n#28 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Route.php(265): Illuminate\Routing\ControllerDispatcher->dispatch(Object(Illuminate\Routing\Route), Object(Livewire\Mechanisms\HandleRequests\HandleRequests), 'handleUpdate')\n#29 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Route.php(211): Illuminate\Routing\Route->runController()\n#30 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php(822): Illuminate\Routing\Route->run()\n#31 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(180): Illuminate\Routing\Router->{closure:Illuminate\Routing\Router::runRouteWithinStack():821}(Object(Illuminate\Http\Request))\n#32 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Middleware\/SubstituteBindings.php(50): Illuminate\Pipeline\Pipeline->{closure:Illuminate\Pipeline\Pipeline::prepareDestination():178}(Object(Illuminate\Http\Request))\n#33 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Routing\Middleware\SubstituteBindings->handle(Object(Illuminate\Http\Request), Object(Closure))\n#34 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Middleware\/VerifyCsrfToken.php(87): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#35 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Foundation\Http\Middleware\VerifyCsrfToken->handle(Object(Illuminate\Http\Request), Object(Closure))\n#36 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/View\/Middleware\/ShareErrorsFromSession.php(48): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#37 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\View\Middleware\ShareErrorsFromSession->handle(Object(Illuminate\Http\Request), Object(Closure))\n#38 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Session\/Middleware\/StartSession.php(120): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#39 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Session\/Middleware\/StartSession.php(63): Illuminate\Session\Middleware\StartSession->handleStatefulRequest(Object(Illuminate\Http\Request), Object(Illuminate\Session\Store), Object(Closure))\n#40 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Session\Middleware\StartSession->handle(Object(Illuminate\Http\Request), Object(Closure))\n#41 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Cookie\/Middleware\/AddQueuedCookiesToResponse.php(36): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#42 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse->handle(Object(Illuminate\Http\Request), Object(Closure))\n#43 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Cookie\/Middleware\/EncryptCookies.php(74): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#44 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Cookie\Middleware\EncryptCookies->handle(Object(Illuminate\Http\Request), Object(Closure))\n#45 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(137): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#46 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php(821): Illuminate\Pipeline\Pipeline->then(Object(Closure))\n#47 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php(800): Illuminate\Routing\Router->runRouteWithinStack(Object(Illuminate\Routing\Route), Object(Illuminate\Http\Request))\n#48 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php(764): Illuminate\Routing\Router->runRoute(Object(Illuminate\Http\Request), Object(Illuminate\Routing\Route))\n#49 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php(753): Illuminate\Routing\Router->dispatchToRoute(Object(Illuminate\Http\Request))\n#50 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php(200): Illuminate\Routing\Router->dispatch(Object(Illuminate\Http\Request))\n#51 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(180): Illuminate\Foundation\Http\Kernel->{closure:Illuminate\Foundation\Http\Kernel::dispatchToRouter():197}(Object(Illuminate\Http\Request))\n#52 \/app\/vendor\/livewire\/livewire\/src\/Features\/SupportDisablingBackButtonCache\/DisableBackButtonCacheMiddleware.php(19): Illuminate\Pipeline\Pipeline->{closure:Illuminate\Pipeline\Pipeline::prepareDestination():178}(Object(Illuminate\Http\Request))\n#53 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Livewire\Features\SupportDisablingBackButtonCache\DisableBackButtonCacheMiddleware->handle(Object(Illuminate\Http\Request), Object(Closure))\n#54 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Middleware\/ConvertEmptyStringsToNull.php(27): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#55 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull->handle(Object(Illuminate\Http\Request), Object(Closure))\n#56 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Middleware\/TrimStrings.php(47): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#57 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Foundation\Http\Middleware\TrimStrings->handle(Object(Illuminate\Http\Request), Object(Closure))\n#58 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Http\/Middleware\/ValidatePostSize.php(27): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#59 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Http\Middleware\ValidatePostSize->handle(Object(Illuminate\Http\Request), Object(Closure))\n#60 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Middleware\/PreventRequestsDuringMaintenance.php(109): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#61 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance->handle(Object(Illuminate\Http\Request), Object(Closure))\n#62 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Http\/Middleware\/HandleCors.php(48): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#63 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Http\Middleware\HandleCors->handle(Object(Illuminate\Http\Request), Object(Closure))\n#64 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Http\/Middleware\/TrustProxies.php(58): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#65 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Http\Middleware\TrustProxies->handle(Object(Illuminate\Http\Request), Object(Closure))\n#66 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Middleware\/InvokeDeferredCallbacks.php(22): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#67 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks->handle(Object(Illuminate\Http\Request), Object(Closure))\n#68 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Http\/Middleware\/ValidatePathEncoding.php(26): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#69 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(219): Illuminate\Http\Middleware\ValidatePathEncoding->handle(Object(Illuminate\Http\Request), Object(Closure))\n#70 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(137): Illuminate\Pipeline\Pipeline->{closure:{closure:Illuminate\Pipeline\Pipeline::carry():194}:195}(Object(Illuminate\Http\Request))\n#71 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php(175): Illuminate\Pipeline\Pipeline->then(Object(Closure))\n#72 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php(144): Illuminate\Foundation\Http\Kernel->sendRequestThroughRouter(Object(Illuminate\Http\Request))\n#73 \/app\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Application.php(1220): Illuminate\Foundation\Http\Kernel->handle(Object(Illuminate\Http\Request))\n#74 \/app\/public\/index.php(17): Illuminate\Foundation\Application->handleRequest(Object(Illuminate\Http\Request))\n#75 {main}"}', '[]') returning "id" (30.71 ms)
