<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

/*
|--------------------------------------------------------------------------
| Upload Configuration
|--------------------------------------------------------------------------
*/

const ALLOWED_IMAGE_TYPES = [
    'image/jpeg',
    'image/png',
    'image/webp',
];

const ALLOWED_DOCUMENT_TYPES = [
    'application/pdf',
    'image/jpeg',
    'image/png',
];

const MAX_UPLOAD_SIZE = 5 * 1024 * 1024; // 5 MB

/*
|--------------------------------------------------------------------------
| Upload Helpers
|--------------------------------------------------------------------------
*/

function upload_file(array $file, string $directory): string
{
    if (!isset($file['error']) || is_array($file['error'])) {
        throw new RuntimeException('Invalid upload.');
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('File upload failed.');
    }

    if ($file['size'] > MAX_UPLOAD_SIZE) {
        throw new RuntimeException('File size exceeds limit.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);

    if (
        !in_array(
            $mime,
            array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_DOCUMENT_TYPES),
            true
        )
    ) {
        throw new RuntimeException('Invalid file type.');
    }

    $extension = match ($mime) {
        'image/jpeg'      => 'jpg',
        'image/png'       => 'png',
        'image/webp'      => 'webp',
        'application/pdf' => 'pdf',
        default           => throw new RuntimeException('Unsupported file type.')
    };

    $directory = rtrim($directory, DIRECTORY_SEPARATOR);

    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $extension;

    $destination = $directory . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Unable to save uploaded file.');
    }

    return $filename;
}

function delete_file(string $path): bool
{
    if (!is_file($path)) {
        return false;
    }

    return unlink($path);
}
