<?php
    // i18n
    class Lang {
        private static array  $strings  = [];
        private static string $langCode = 'en';

        public static function init(string $lang): void {
            self::$langCode = $lang;

            $file = __DIR__ . '/../../lang/' . $lang . '.php';

            if (!file_exists($file)) {
                $file = __DIR__ . '/../../lang/en.php';
                self::$langCode = 'en';
            }

            self::$strings = require $file;
        }

        public static function current(): string {
            return self::$langCode;
        }

        public static function get(string $key, array $replacements = []): string {
            $str = self::$strings[$key] ?? $key;

            foreach ($replacements as $placeholder => $value) {
                $str = str_replace($placeholder, (string) $value, $str);
            }

            return $str;
        }

        // Keys starting with '_' are server-only, strip them before sending to the client
        public static function jsLocale(): array {
            return array_filter(
                self::$strings,
                static fn(string $k) => $k[0] !== '_',
                ARRAY_FILTER_USE_KEY
            );
        }

        public static function detectFromBrowser(array $supported = ['en', 'de']): string {
            // E.g. "de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7
            $header = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
            if ($header === '') return 'en';

            // Extract language code and priority (q) from each entry
            $entries = [];
            foreach (explode(',', $header) as $part) {
                $part = trim($part);
                $pieces = explode(';q=', $part);
                $code = strtolower(substr(trim($pieces[0]), 0, 2));
                $q = isset($pieces[1]) ? (float) $pieces[1] : 1.0;
                $entries[] = [$code, $q];
            }

            // Sort by quality descending
            usort($entries, static fn($a, $b) => $b[1] <=> $a[1]);

            foreach ($entries as [$code]) {
                if (in_array($code, $supported, true)) return $code;
            }

            return 'en';
        }
    }

    function __(string $key, array $replacements = []): string {
        return Lang::get($key, $replacements);
    }