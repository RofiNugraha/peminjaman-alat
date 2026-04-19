<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->per_page, [5,10,25,50,100]) ? $request->per_page : 10;
        $search  = $request->search;

        $query = Contact::query();

        if($search){
            $query->where('nama_sekolah','like',"%$search%")
                  ->orWhere('email','like',"%$search%");
        }

        $contacts = $query->latest()->paginate($perPage)->withQueryString();

        if($request->ajax()){
            return view('admin.contact.partials.table', compact('contacts'))->render();
        }

        return view('admin.contact.index', compact('contacts'));
    }

    public function create()
    {
        return view('admin.contact.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|max:100',
            'alamat'       => 'required',
            'email'        => 'nullable|email',
            'telepon'      => 'nullable|max:20',
            'instagram'    => 'nullable|max:100',
            'website'      => 'nullable|max:100',
        ]);

        Contact::create($request->all());

        return redirect()->route('contact.index')
            ->with('success','Berhasil ditambahkan');
    }

    public function show(Contact $contact)
    {
        return view('admin.contact.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        return view('admin.contact.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'nama_sekolah' => 'required|max:100',
            'alamat'       => 'required',
            'email'        => 'nullable|email',
            'telepon'      => 'nullable|max:20',
            'instagram'    => 'nullable|max:100',
            'website'      => 'nullable|max:100',
        ]);

        $contact->update($request->all());

        return redirect()->route('contact.index')
            ->with('success','Berhasil diperbarui');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return back()->with('success','Berhasil dihapus');
    }
}