<?php

namespace App\Http\Controllers;

use App\Models\ProfileMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileMediaController extends Controller
{
    public function download(int $media): RedirectResponse
    {
        $file = ProfileMedia::query()->findOrFail($media);

        abort_if($file->is_public, 404);
        abort_unless((int) Auth::id() === $file->user_id, 403);

        $url = Storage::disk($file->disk)->temporaryUrl(
            $file->object_key,
            now()->addMinutes(5),
            ['ResponseContentDisposition' => 'attachment']
        );

        return redirect()->away($url);
    }
}
