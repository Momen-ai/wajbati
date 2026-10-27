<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class I18nCoverageTest extends TestCase
{
    /**
     * Test money helper formats correctly in AR and EN.
     */
    public function test_money_helper_formats_currency_correctly(): void
    {
        app()->setLocale('ar');
        $this->assertEquals('15.50 ₪', money(15.5));
        $this->assertEquals('0.00 ₪', money(0));

        app()->setLocale('en');
        $this->assertEquals('₪ 15.50', money(15.5));
        $this->assertEquals('₪ 0.00', money(0));
    }

    /**
     * Ensure lang/_pending directory is empty (no unmerged keys remain).
     */
    public function test_lang_pending_directory_is_empty(): void
    {
        $pendingPath = base_path('lang/_pending');

        if (! File::isDirectory($pendingPath)) {
            $this->assertTrue(true);

            return;
        }

        $files = File::files($pendingPath);
        $nonEmptyFiles = [];

        foreach ($files as $file) {
            $content = trim(File::get($file->getPathname()));
            if (! empty($content) && $content !== '{}') {
                $nonEmptyFiles[] = $file->getFilename();
            }
        }

        $this->assertEmpty(
            $nonEmptyFiles,
            'lang/_pending contains unmerged translation files: '.implode(', ', $nonEmptyFiles)
        );
    }

    /**
     * Assert no literal dollar amounts or ${{ remain in Blade views.
     */
    public function test_no_literal_dollar_patterns_in_views(): void
    {
        $viewsPath = resource_path('views');
        $files = File::allFiles($viewsPath);
        $violations = [];

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $content = File::get($file->getPathname());
            $relPath = str_replace(resource_path('views').DIRECTORY_SEPARATOR, '', $file->getPathname());

            // Check for ${{ pattern
            if (preg_match('/\$(\{\{)/', $content)) {
                $violations[] = "{$relPath}: contains '\${{'";
            }

            // Check for $[0-9] pattern (e.g. $3.00, $10)
            if (preg_match('/\$[0-9]+(\.[0-9]{1,2})?/', $content, $matches)) {
                $violations[] = "{$relPath}: contains literal '{$matches[0]}'";
            }
        }

        $this->assertEmpty(
            $violations,
            "Literal currency symbols found in views (must use money()):\n".implode("\n", $violations)
        );
    }

    /**
     * Scan resources/views and app/ for __('...'), trans('...'), @lang('...')
     * and assert every key is defined in lang/ar.json and lang/en.json.
     */
    public function test_all_translation_keys_are_defined_in_ar_and_en(): void
    {
        $arJsonPath = base_path('lang/ar.json');
        $enJsonPath = base_path('lang/en.json');

        $this->assertFileExists($arJsonPath);
        $this->assertFileExists($enJsonPath);

        $arKeys = json_decode(File::get($arJsonPath), true) ?: [];
        $enKeys = json_decode(File::get($enJsonPath), true) ?: [];

        $discoveredKeys = $this->extractTranslationKeys();

        $missingInAr = [];
        $missingInEn = [];

        foreach ($discoveredKeys as $key => $occurrences) {
            // Ignore dynamic keys or validation rule references with dots if they are in validation.php
            if (str_starts_with($key, '$') || str_contains($key, '{$')) {
                continue;
            }
            if (str_starts_with($key, 'validation.') || str_starts_with($key, 'auth.') || str_starts_with($key, 'passwords.')) {
                continue;
            }

            if (! array_key_exists($key, $arKeys)) {
                $missingInAr[] = "{$key} (in ".implode(', ', array_slice($occurrences, 0, 2)).')';
            }

            if (! array_key_exists($key, $enKeys)) {
                $missingInEn[] = "{$key} (in ".implode(', ', array_slice($occurrences, 0, 2)).')';
            }
        }

        $messages = [];
        if (! empty($missingInAr)) {
            $messages[] = 'Missing in lang/ar.json ('.count($missingInAr)." keys):\n".implode("\n", array_slice($missingInAr, 0, 25));
        }
        if (! empty($missingInEn)) {
            $messages[] = 'Missing in lang/en.json ('.count($missingInEn)." keys):\n".implode("\n", array_slice($missingInEn, 0, 25));
        }

        $this->assertEmpty($messages, implode("\n\n", $messages));
    }

    /**
     * Scan directories for translation keys.
     */
    protected function extractTranslationKeys(): array
    {
        $scanPaths = [
            resource_path('views'),
            app_path(),
        ];

        $keys = [];
        $patterns = [
            '/__\(\s*[\'"]([^\'"]+)[\'"]\s*[\),]/',
            '/trans\(\s*[\'"]([^\'"]+)[\'"]\s*[\),]/',
            '/@lang\(\s*[\'"]([^\'"]+)[\'"]\s*\)/',
        ];

        foreach ($scanPaths as $path) {
            $files = File::allFiles($path);
            foreach ($files as $file) {
                if (! in_array($file->getExtension(), ['php', 'blade.php'])) {
                    continue;
                }

                $content = File::get($file->getPathname());
                $relPath = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname());

                foreach ($patterns as $pattern) {
                    if (preg_match_all($pattern, $content, $matches)) {
                        foreach ($matches[1] as $matchedKey) {
                            $cleanKey = trim($matchedKey);
                            if (! empty($cleanKey)) {
                                $keys[$cleanKey][] = $relPath;
                            }
                        }
                    }
                }
            }
        }

        return $keys;
    }
}
