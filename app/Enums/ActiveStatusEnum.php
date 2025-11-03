<?php

namespace App\Enums;

enum ActiveStatusEnum: int
{
    //
    case Inactive = 0;
    case Active = 1;

    public static function fromValue(int $value): self
    {
        return match ($value) {
            0 => self::Inactive,
            1 => self::Active,
            default => throw new \InvalidArgumentException("Invalid active status value: $value"),
        };
    }

    public function toValue(): int
    {
        return $this->value;
    }
}