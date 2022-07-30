<?php

namespace App\Infrastructure\Container;

class Settings
{
    private function __construct(
        private array $settings
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

    public static function fromArray(array $settings): self
    {
        return new self($settings);
    }
}