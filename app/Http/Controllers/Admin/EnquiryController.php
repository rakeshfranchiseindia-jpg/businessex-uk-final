<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ChatbotLead;
use App\Models\Admin\ContactEnquiry;
use App\Models\Admin\NewsletterSubscriber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    public function contact(Request $request): View
    {
        $rows = $this->search(ContactEnquiry::query(), $request, ['contact_name', 'contact_email', 'contact_mobile', 'contact_comment']);

        return view('admin.enquiries.contact', compact('rows'));
    }

    public function newsletter(Request $request): View
    {
        $query = NewsletterSubscriber::query();

        if (array_key_exists((string) $request->query('status'), config('admin.newsletter_status'))) {
            $query->where('status', $request->query('status'));
        }

        $rows = $this->search($query, $request, ['email', 'name', 'phone', 'city']);

        return view('admin.enquiries.newsletter', compact('rows'));
    }

    public function chatbot(Request $request): View
    {
        $rows = $this->search(ChatbotLead::query(), $request, ['name', 'email', 'mobile', 'message']);

        return view('admin.enquiries.chatbot', compact('rows'));
    }

    private function search(Builder $query, Request $request, array $columns)
    {
        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($columns, $keyword) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', "%{$keyword}%");
                }
            });
        }

        return $query->latest('created_at')->paginate($this->perPage())->withQueryString();
    }
}
