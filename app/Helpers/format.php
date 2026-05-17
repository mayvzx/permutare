<?php

function label_for(string $group, ?string $key): string
{
    if (!$key) {
        return '-';
    }

    return config('constants.' . $group . '.' . $key, $key);
}

function format_date(?string $date): string
{
    if (!$date) {
        return '-';
    }

    return date('d/m/Y H:i', strtotime($date));
}

function excerpt(?string $text, int $limit = 120): string
{
    $text = trim((string) $text);

    if (mb_strlen($text) <= $limit) {
        return $text;
    }

    return mb_substr($text, 0, $limit - 3) . '...';
}
