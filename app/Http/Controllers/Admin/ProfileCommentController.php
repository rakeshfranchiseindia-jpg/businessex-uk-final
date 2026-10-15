<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactComment;
use App\Support\Admin\ProfileTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileCommentController extends Controller
{
    /**
     * contact_status codes, per the contact_comment table comment:
     * 1 Follow Up, 2 Closed, 3 Not Interested, 4 Not Responding, 5 Wrong No, 6 Switch Off, 7 Other.
     */
    public const LEAD_STATUSES = [
        1 => 'Follow Up',
        2 => 'Closed',
        3 => 'Not Interested',
        4 => 'Not Responding',
        5 => 'Wrong No',
        6 => 'Switch Off',
        7 => 'Other',
    ];

    /**
     * contact_response codes: 1 Hot, 2 Cold, 3 Warm.
     */
    public const LEAD_QUALITIES = [
        1 => 'Hot',
        2 => 'Cold',
        3 => 'Warm',
    ];

    public function store(Request $request, string $type, int $id): RedirectResponse
    {
        $config = ProfileTypes::get($type);
        $profile = $this->findProfile($type, $id);

        $data = $request->validate([
            'contact_status' => ['required', 'integer', 'in:'.implode(',', array_keys(self::LEAD_STATUSES))],
            'contact_response' => ['nullable', 'integer', 'in:'.implode(',', array_keys(self::LEAD_QUALITIES))],
            'reminder' => ['nullable', 'date', 'required_if:contact_status,1'],
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        ContactComment::query()->create([
            'contact_id' => $profile->user_id,
            'contact_type' => $config['id'],
            'commented_by' => $this->admin()->admin_id,
            'contact_status' => $data['contact_status'],
            'contact_response' => $data['contact_response'] ?? null,
            'reminder' => $data['contact_status'] == 1 ? $data['reminder'] : null,
            'comment' => $data['comment'],
            'status' => 1,
        ]);

        return back()->with('success', 'Comment added.');
    }

    private function findProfile(string $type, int $id): Model
    {
        return ProfileTypes::query($type)->findOrFail($id);
    }
}
