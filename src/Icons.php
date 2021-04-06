<?php

namespace Feather;

use Feather\Exception\IconNotFoundException;

require __DIR__ . '/defaultAttributes.php';

class Icons
{
    private $attributes = DEFAULT_ATTRIBUTES;

    private $icons;

    public function __construct()
    {
        $this->icons = require implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), '..', 'resources', 'icons.php']);
    }

    public function get(string $name, array $attributes = []): string
    {
        if (isset($this->icons[$name])) {
            $contents   = $this->icons[$name];
            $attributes = array_merge($this->attributes, $attributes);

            if (isset($attributes['class'])) {
                $class_end = ' ' . $attributes['class'];
            } else {
                $class_end = '';
            }

            $attributes['class'] = 'feather feather-' . $name . $class_end;

            $dom_attributes = array_reduce(
                array_keys($attributes),
                function ($final, $current) use ($attributes) {
                    $attribute_value = $attributes[$current];

                    if (is_bool($attribute_value)) {
                        $attribute_value = $attribute_value ? 'true' : 'false';
                    }

                    $attribute_value = htmlspecialchars((string)$attribute_value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);

                    return $final . $current . '="' . $attribute_value . '" ';
                },
                ''
            );

            $icon = '<svg ' . $dom_attributes . '>' . $contents . '</svg>';

            return $icon;
        }

        throw new IconNotFoundException(\sprintf('Icon `%s` not found', $name));
    }

    public function setAttributes(array $attributes, bool $merge = true): self
    {
        if ($merge) {
            $this->attributes = array_merge($this->attributes, $attributes);
        } else {
            $this->attributes = $attributes;
        }

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
