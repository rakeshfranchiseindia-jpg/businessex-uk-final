<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::query()->orderBy('sort_order')->latest('created_at')->paginate($this->perPage());

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('admin.testimonials.form', ['testimonial' => new Testimonial(['is_active' => 1, 'rating' => 5, 'sort_order' => 0])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Testimonial());

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $this->save($request, $testimonial);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->deleteUpload($testimonial->image_path);
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }

    private function save(Request $request, Testimonial $testimonial): void
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'text' => ['required', 'string', 'max:2000'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['required', 'in:0,1'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['image_path'] = $this->storeUpload($request->file('image'), 'testimonials', $testimonial->image_path);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        unset($data['image']);

        $testimonial->fill($data)->save();
    }
}
