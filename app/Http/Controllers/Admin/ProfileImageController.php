<?php

namespace App\Http\Controllers\Admin;

use App\Support\Admin\ProfileTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileImageController extends Controller
{
    public function store(Request $request, string $type, int $id): RedirectResponse
    {
        $config = $this->imagesConfig($type);
        $profile = $this->findProfile($type, $id);

        $data = $request->validate([
            'kind' => ['required', 'in:image,document'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $imageModel = $config['model'];
        $path = $this->storeUpload($request->file('file'), $config['folder']);

        $imageModel::query()->create([
            $config['foreign_key'] => $profile->getKey(),
            'type' => $data['kind'] === 'document' ? $imageModel::TYPE_DOCUMENT : $imageModel::TYPE_IMAGE,
            $config['path_column'] => $path,
            $config['name_column'] => $request->file('file')->getClientOriginalName(),
            'is_active' => 1,
        ]);

        return back()->with('success', 'File uploaded.');
    }

    public function toggle(string $type, int $id, int $image): RedirectResponse
    {
        $config = $this->imagesConfig($type);
        $this->findProfile($type, $id);

        $record = $config['model']::query()
            ->where($config['foreign_key'], $id)
            ->findOrFail($image);

        $record->update(['is_active' => $record->is_active ? 0 : 1]);

        return back()->with('success', 'Visibility updated.');
    }

    public function destroy(string $type, int $id, int $image): RedirectResponse
    {
        $config = $this->imagesConfig($type);
        $this->findProfile($type, $id);

        $record = $config['model']::query()
            ->where($config['foreign_key'], $id)
            ->findOrFail($image);

        $this->deleteUpload($record->{$config['path_column']});
        $record->delete();

        return back()->with('success', 'File removed.');
    }

    private function imagesConfig(string $type): array
    {
        $config = ProfileTypes::get($type);

        abort_if(! isset($config['images']), 404);

        return $config['images'];
    }

    private function findProfile(string $type, int $id): Model
    {
        return ProfileTypes::query($type)->findOrFail($id);
    }
}
