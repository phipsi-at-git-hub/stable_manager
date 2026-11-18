<?php
// app/Core/Localization/Localization.php

declare(strict_types=1);

namespace App\Core\Localization;

use FilesystemIterator;
use JsonException; 
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

final class Localization
{
    private array $translations;
    private array $fallbackTranslations;

    public function __construct(
        private readonly string $locale,
        private readonly string $fallbackLocale,
        private readonly string $translationsPath,
        private readonly array $availableLocales, 
    ) {
        $this->translations = $this->loadLocale($locale);

        $this->fallbackTranslations = $locale === $fallbackLocale
            ? $this->translations
            : $this->loadLocale($fallbackLocale);
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function get(string $key, array $replace = []): string
    {
        $value = $this->find($this->translations, $key)
            ?? $this->find($this->fallbackTranslations, $key);

        if (! is_string($value)) {
            return $key;
        }

        foreach ($replace as $name => $replacement) {
            $value = str_replace(
                '{' . $name . '}',
                (string) ($replacement ?? ''),
                $value,
            );
        }

        return $value;
    }

    public function all(): array
    {
        return $this->translations;
    }

    public function fallbackLocale(): string 
    {
        return $this->fallbackLocale; 
    }

    public function allLocales(): array
    {
        $locales = []; 

        foreach ($this->availableLocales as $locale) {
            if ($locale === $this->locale) {
                $locales[$locale] = $this->translations; 
                continue; 
            }

            if ($locale === $this->fallbackLocale) {
                $locales[$locale] = $this->fallbackTranslations; 
                continue; 
            }

            $locales[$locale] = $this->loadLocale($locale); 
        }

        return $locales; 
    }

    private function loadLocale(string $locale): array
    {
        $directory = rtrim($this->translationsPath, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . $locale;

        if (! is_dir($directory)) {
            throw new RuntimeException(
                "Localization directory not found: {$directory}"
            );
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $directory,
                FilesystemIterator::SKIP_DOTS,
            ),
        );

        $files = [];

        foreach ($iterator as $file) {
            if ($file->isFile() && strtolower($file->getExtension()) === 'json') {
                $files[] = $file->getPathname();
            }
        }

        sort($files, SORT_STRING);

        $catalogue = [];

        foreach ($files as $file) {
            $contents = file_get_contents($file);

            if ($contents === false) {
                throw new RuntimeException(
                    "Unable to read localization file: {$file}"
                );
            }

            if (trim($contents) === '') {
                continue; 
            }

            try {
                $decoded = json_decode(
                    $contents,
                    true,
                    512,
                    JSON_THROW_ON_ERROR,
                );
            } catch (JsonException $exception) {
                // ToDo: Use Logger or ErrorHandler
            }

            $decoded = json_decode(
                $contents,
                true,
                512,
                JSON_THROW_ON_ERROR,
            );

            if (! is_array($decoded)) {
                throw new RuntimeException(
                    "Localization file must contain a JSON object: {$file}"
                );
            }

            $catalogue = $this->mergeWithoutDuplicates(
                $catalogue,
                $decoded,
                $file,
            );
        }

        return $catalogue;
    }

    private function mergeWithoutDuplicates(
        array $target,
        array $incoming,
        string $file,
    ): array {
        foreach ($incoming as $key => $value) {
            if (! array_key_exists($key, $target)) {
                $target[$key] = $value;
                continue;
            }

            if (is_array($target[$key]) && is_array($value)) {
                $target[$key] = $this->mergeWithoutDuplicates(
                    $target[$key],
                    $value,
                    $file,
                );

                continue;
            }

            throw new RuntimeException(
                "Duplicate localization key '{$key}' found in {$file}."
            );
        }

        return $target;
    }

    private function find(array $translations, string $key): mixed
    {
        $value = $translations;

        foreach (explode('.', $key) as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return null;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}
