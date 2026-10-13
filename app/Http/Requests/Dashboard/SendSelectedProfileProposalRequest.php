<?php

namespace App\Http\Requests\Dashboard;

/**
 * A proposal sent from the "Proposal sent" composer, which names the
 * owner's profile and the interested member explicitly.
 */
class SendSelectedProfileProposalRequest extends SendProposalRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'profile' => ['required', 'string', 'regex:/^(business|investor|mentor|startup):[1-9][0-9]*$/'],
            'recipient_user_id' => ['required', 'integer', 'min:1'],
            ...parent::rules(),
        ];
    }
}
