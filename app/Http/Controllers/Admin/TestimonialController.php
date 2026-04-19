<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->per_page, [5,10,25,50,100]) ? $request->per_page : 10;
        $search  = $request->search;
        $status  = $request->status;

        $query = Testimonial::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('pesan', 'like', "%{$search}%");
        }

        if ($status !== null && $status !== '') {
            $query->where('is_approved', $status);
        }

        $data = $query->latest()->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return view('admin.testimonial.partials.table', compact('data','perPage'))->render();
        }

        return view('admin.testimonial.index', compact('data','perPage'));
    }

    public function show(Testimonial $testimonial)
    {
        return view('admin.testimonial.show', compact('testimonial'));
    }

    public function update(Testimonial $testimonial)
    {
        $testimonial->update([
            'is_approved' => !$testimonial->is_approved
        ]);

        return response()->json([
            'success' => true,
            'status' => $testimonial->is_approved
        ]);
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success','Dihapus');
    }
}