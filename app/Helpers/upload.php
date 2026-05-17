<?php

function is_upload_present(?array $file): bool
{
    return $file && isset($file['error']) && $file['error'] !== UPLOAD_ERR_NO_FILE;
}
