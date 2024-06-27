<?php

namespace App\Entity\Enum;

class ArticleStatus
{
    public const DRAFT = 'draft';
    public const PUBLISHED = 'published';

    /**
     * Mapuje wartość liczbową na stałą ArticleStatus.
     *
     * @param int $value The numeric value to be mapped.
     *
     * @throws \InvalidArgumentException If the provided value is not valid.
     *
     * @return string ArticleStatus
     */
    public static function from(int $value): string
    {
        return match ($value) {
            1 => self::DRAFT,
            2 => self::PUBLISHED,
            default => throw new \InvalidArgumentException(sprintf('Invalid value "%s" for ArticleStatus', $value)),
        };
    }
}
