<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionMethod;
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
            "Found broken route actions pointing to non-existent methods:\n" . implode("\n", $missingActions)
        );
    }
}
