<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Admin\AdminUser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

abstract class Controller extends BaseController
{
    protected function admin(): AdminUser
    {
        /** @var AdminUser */
        return Auth::guard('admin')->user();
    }

    protected function perPage(): int
    {
        return (int) config('admin.per_page', 20);
    }

    /**
     * Store an uploaded file under public/uploads/admin/{folder} and return
     * the public-relative path (the format the front end resolves).
     */
    protected function storeUpload(?UploadedFile $file, string $folder, ?string $replace = null): ?string
    {
        if (! $file) {
            return $replace;
        }

        $directory = trim(config('admin.upload_path'), '/').'/'.$folder;
        $name = Str::random(20).'.'.strtolower($file->getClientOriginalExtension());

        $file->move(public_path($directory), $name);

        $this->deleteUpload($replace);

        return $directory.'/'.$name;
    }

    /**
     * Remove a file previously stored by storeUpload(). Paths outside the
     * admin upload folder (legacy S3 keys, external URLs) are left alone.
     */
    protected function deleteUpload(?string $path): void
    {
        $root = trim(config('admin.upload_path'), '/').'/';

        if (! $path || ! str_starts_with($path, $root) || str_contains($path, '..')) {
            return;
        }

        $full = public_path($path);

        if (is_file($full)) {
            @unlink($full);
        }
    }
}
