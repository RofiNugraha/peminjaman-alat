<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 10;
        }

        $search = $request->search;
        $role   = $request->role;

        $allowedSorts = ['nama', 'username', 'email', 'created_at'];
        $sortBy = in_array($request->sort_by, $allowedSorts)
            ? $request->sort_by
            : 'created_at';

        $direction = $request->direction === 'asc' ? 'asc' : 'desc';

        $users = User::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, fn ($q) => $q->where('role', $role))
            ->orderBy($sortBy, $direction)
            ->paginate($perPage)
            ->withQueryString()
            ->onEachSide(1);

        if ($request->ajax()) {
            return view('admin.users.partials.table', compact('users', 'perPage'))->render();
        }

        return view('admin.users.index', compact('users', 'perPage'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,petugas,peminjam',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'nama.max' => 'Nama maksimal 100 karakter.',

            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.max' => 'Username maksimal 50 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',

            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $auth = Auth::user();

        if ($user->id === $auth->id) {
            abort(403, 'Tidak boleh mengubah akun sendiri.');
        }

        if ($user->role === 'admin') {
            abort(403, 'Tidak boleh mengubah akun admin lain.');
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,petugas,peminjam',
        ]);

        $user->update([
            'role' => $validated['role']
        ]);

        return redirect()->route('users.index')->with('success', 'Role user berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $auth = Auth::user();

        if ($user->id === $auth->id) {
            abort(403, 'Tidak boleh menghapus akun sendiri.');
        }

        if ($user->role === 'admin') {
            abort(403, 'Tidak boleh menghapus akun admin lain.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }

}