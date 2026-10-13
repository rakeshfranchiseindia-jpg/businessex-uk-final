<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Account details editable from the dashboard. Email and mobile are the
 * identity details tied to the account at registration and are locked
 * (read-only in the UI); they are deliberately not accepted here, so any
 * submitted values are ignored.
 */
class UpdateAccountProfileRequest extends FormRequest
{
    private const NAME_PATTERN = "regex:/^[A-Za-z][A-Za-z .'-]*$/";

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', self::NAME_PATTERN],
            'location' => ['required', 'string', 'max:60'],
            'designation' => ['required', 'string', 'max:60', self::NAME_PATTERN],
            'company' => ['required', 'string', 'max:255', self::NAME_PATTERN],
        ];
    }
}
