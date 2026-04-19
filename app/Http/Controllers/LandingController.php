<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Alat;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        $alats = Alat::latest()->take(6)->get();

        $testimonials = Testimonial::where('is_approved', true)->latest()->get();

        $totalAlat = Alat::count();
        $totalUser = User::where('role', 'peminjam')->count();

        $contact = Contact::first();

        return view('welcome', compact(
            'settings',
            'alats',
            'testimonials',
            'totalAlat',
            'totalUser',
            'contact'
        ));
    }

    public function storeTestimoni(Request $request)
    {
        Testimonial::create([
            'nama' => $request->nama,
            'pesan' => $request->pesan,
        ]);

        return back()->with('success','Testimoni dikirim!');
    }
}