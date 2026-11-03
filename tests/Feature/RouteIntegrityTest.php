<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use ReflectionClass;
use Tests\TestCase;

class RouteIntegrityTest extends TestCase
{
    public function test_all_registered_controller_routes_point_to_existing_methods(): void
    {
        $routes = Route::getRoutes();
        $missingActions = [];

        foreach ($routes as $route) {
            $action = $route->getActionName();

            // Skip closure routes
            if ($action === 'Closure' || str_contains($action, '@closure') || ! str_contains($action, '@')) {
                continue;
            }

            [$controller, $method] = explode('@', $action);

            if (! class_exists($controller)) {
                $missingActions[] = "Missing controller: {$controller} for route [{$route->uri()}]";

                continue;
            }

            $reflector = new ReflectionClass($controller);
            if (! $reflector->hasMethod($method)) {
                $missingActions[] = "Missing method: {$controller}@{$method} for route [{$route->uri()}]";

                continue;
            }

            $methodReflector = $reflector->getMethod($method);
            if (! $methodReflector->isPublic()) {
                $missingActions[] = "Method is not public: {$controller}@{$method} for route [{$route->uri()}]";
            }
        }

        $this->assertEmpty(
            $missingActions,
            "Found broken route actions pointing to non-existent methods:\n".implode("\n", $missingActions)
        );
    }

    public function test_all_route_calls_in_blade_views_exist(): void
    {
        $allRoutes = Route::getRoutes();
        $routeNames = [];
        foreach ($allRoutes as $r) {
            if ($r->getName()) {
                $routeNames[$r->getName()] = true;
            }
        }

        $missingRoutes = [];
        $orphanedViews = [
            'create.blade.php', // admin/contacts/create and admin/ratings/create (unrouted scaffolding)
            'edit.blade.php',   // admin/ratings/edit (unrouted scaffolding)
        ];
        $viewIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('views')));

        foreach ($viewIterator as $file) {
            if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                // Check if file is in admin contacts/ratings create/edit which are unrouted legacy views
                $relPath = str_replace('\\', '/', $file->getPathname());
                if (str_contains($relPath, 'admin/contacts/create.blade.php') ||
                    str_contains($relPath, 'admin/ratings/create.blade.php') ||
                    str_contains($relPath, 'admin/ratings/edit.blade.php')) {
                    continue;
                }

                $content = file_get_contents($file->getPathname());
                // Match route(...) but ignore $request->route(...)
                if (preg_match_all("/(?<!->)route\s*\(\s*['\"]([a-zA-Z0-9._-]+)['\"]/", $content, $matches)) {
                    foreach ($matches[1] as $calledRoute) {
                        if (! isset($routeNames[$calledRoute])) {
                            $missingRoutes[] = $file->getFilename().' calls undefined route ['.$calledRoute.']';
                        }
                    }
                }
            }
        }

        $this->assertEmpty(
            $missingRoutes,
            "Found blade views calling non-existent route names:\n".implode("\n", $missingRoutes)
        );
    }
}
