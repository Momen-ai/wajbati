<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AssetSecurityTest extends TestCase
{
    /**
     * Test that no Blade template contains external http:// or https:// URLs,
     * except allowed standard namespaces (such as w3.org, schema.org).
     */
    public function test_no_third_party_asset_urls_in_views(): void
    {
        $viewsPath = resource_path('views');
        $files = File::allFiles($viewsPath);
        $violations = [];

        $allowedHosts = [
            'w3.org',
            'schema.org',
            'localhost',
            '127.0.0.1',
        ];

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $content = File::get($file->getPathname());
            $relPath = str_replace(resource_path('views').DIRECTORY_SEPARATOR, '', $file->getPathname());

            // Match any http:// or https:// url
            if (preg_match_all('/https?:\/\/[^\s\'"<>\)]+/', $content, $matches)) {
                foreach ($matches[0] as $url) {
                    $isAllowed = false;
                    foreach ($allowedHosts as $allowed) {
                        if (str_contains($url, $allowed)) {
                            $isAllowed = true;
                            break;
                        }
                    }

                    if (! $isAllowed) {
                        $violations[] = "{$relPath}: contains external URL '{$url}'";
                    }
                }
            }
        }

        $this->assertEmpty(
            $violations,
            "Third-party external URLs found in Blade views (must be self-hosted / bundled via Vite):\n".implode("\n", $violations)
        );
    }

    /**
     * Verify that self-hosted placeholder SVG exists in public assets.
     */
    public function test_placeholder_svg_exists(): void
    {
        $placeholderPath = public_path('assets/images/placeholder.svg');
        $this->assertFileExists($placeholderPath);
    }
}
