<?php

namespace App\Infrastructure\Environment;

class Settings
{
    public const UUID_NAMESPACE = '4bdbe8ec-5cb5-11ea-bc55-0242ac130003';

    private function __construct(
        private readonly array $settings
    )
    {
    }

    public function get(string $parents)
    {
        $settings = $this->settings;
        $parents = explode('.', $parents);

        foreach ($parents as $parent) {
            if (is_array($settings) && (isset($settings[$parent]) || array_key_exists($parent, $settings))) {
                $settings = $settings[$parent];
            } else {
                return null;
            }
        }
        return $settings;
    }

    public static function load(): self
    {
        return new self(require self::getAppRoot() . '/config/settings.php');
    }

    public static function getAppRoot(): string
    {
        return dirname(__DIR__, 3);
    }
}