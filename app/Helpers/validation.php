<?php

function str_between(?string $value, int $min, int $max): bool
{
    $length = mb_strlen(trim((string) $value));
    return $length >= $min && $length <= $max;
}

function valid_choice(?string $value, array $choices): bool
{
    return $value !== null && array_key_exists($value, $choices);
}

function collect_errors(array $rules): array
{
    return array_filter($rules, fn ($message) => $message !== null);
}
