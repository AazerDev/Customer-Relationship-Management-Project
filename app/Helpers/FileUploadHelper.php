<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    /**
     * Upload file to public storage
     *
     * @param UploadedFile|null $file
     * @param string $path
     * @return string|null
     */
    public static function uploadFile(?UploadedFile $file, string $path = 'uploads'): ?string
    {
        if ($file) {
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($path, $fileName, 'public');
            return '/storage/' . $filePath;
        }
        return null;
    }

    public static function deleteFile($path)
    {
        if (!$path) return false;

        $relativePath = str_replace(Storage::url(''), '', $path);
        return Storage::disk('public')->delete($relativePath);
    }
}
