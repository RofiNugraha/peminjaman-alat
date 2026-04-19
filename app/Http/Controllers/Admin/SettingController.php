<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->per_page, [5,10,25,50,100]) ? $request->per_page : 10;
        $search  = $request->search;

        $query = Setting::query();

        if($search){
            $query->where('nama_aplikasi','like',"%$search%");
        }

        $settings = $query->latest()->paginate($perPage)->withQueryString();

        if($request->ajax()){
            return view('admin.setting.partials.table', compact('settings','perPage'))->render();
        }

        return view('admin.setting.index', compact('settings','perPage'));
    }

    public function create()
    {
        return view('admin.setting.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_aplikasi' => 'required|max:100',
            'logo_ungu'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'logo_putih'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nama_aplikasi',
            'hero_title',
            'hero_subtitle'
        ]);

        // upload logo ungu
        if ($request->hasFile('logo_ungu')) {
            $data['logo_ungu'] = $request->file('logo_ungu')->store('setting', 'public');
        }

        // upload logo putih
        if ($request->hasFile('logo_putih')) {
            $data['logo_putih'] = $request->file('logo_putih')->store('setting', 'public');
        }

        Setting::create($data);

        return redirect()->route('setting.index')
            ->with('success', 'Berhasil ditambahkan');
    }

    public function edit(Setting $setting)
    {
        return view('admin.setting.edit', compact('setting'));
    }

    public function show(Setting $setting)
    {
        return view('admin.setting.show', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'nama_aplikasi' => 'required|max:100',
            'logo_ungu'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'logo_putih'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nama_aplikasi',
            'hero_title',
            'hero_subtitle'
        ]);

        // update logo ungu
        if ($request->hasFile('logo_ungu')) {

            // hapus lama
            if ($setting->logo_ungu && Storage::disk('public')->exists($setting->logo_ungu)) {
                Storage::disk('public')->delete($setting->logo_ungu);
            }

            $data['logo_ungu'] = $request->file('logo_ungu')->store('setting', 'public');
        }

        // update logo putih
        if ($request->hasFile('logo_putih')) {

            if ($setting->logo_putih && Storage::disk('public')->exists($setting->logo_putih)) {
                Storage::disk('public')->delete($setting->logo_putih);
            }

            $data['logo_putih'] = $request->file('logo_putih')->store('setting', 'public');
        }

        $setting->update($data);

        return redirect()->route('setting.index')
            ->with('success', 'Berhasil diperbarui');
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();

        return back()->with('success','Berhasil dihapus');
    }
}