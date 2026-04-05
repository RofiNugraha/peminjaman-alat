<?php

namespace App\Imports;

use App\Models\DataSiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class DataSiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    /**
     * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    use SkipsFailures;

    protected $tahunAjaran;

    public function __construct($tahunAjaran)
    {
        $this->tahunAjaran = $tahunAjaran;
    }

    public function model(array $row)
    {
        if (
            empty(trim($row['nisn'] ?? '')) &&
            empty(trim($row['nama'] ?? '')) &&
            empty(trim($row['kelas'] ?? '')) &&
            empty(trim($row['jurusan'] ?? ''))
        ) {
            return null;
        }
        
        return DataSiswa::updateOrCreate(
            ['nisn' => trim($row['nisn'])],
            [
                'nama' => $row['nama'],
                'kelas' => $row['kelas'],
                'jurusan' => $row['jurusan'],
                'tahun_angkatan' => $row['tahun_angkatan'],
                'tahun_ajaran' => $this->tahunAjaran,
                'status' => 'aktif',
            ]
        );
    }

    public function rules(): array
    {
        return [
            '*.nisn' => 'required|digits:10',
            '*.nama' => 'required|string|max:100',
            '*.kelas' => 'required|string|max:10',
            '*.jurusan' => 'required|string|max:100',
            '*.tahun_angkatan' => 'required|digits:4|integer|min:2000|max:' . date('Y'),
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nisn.required' => 'NISN wajib diisi',
            '*.nisn.digits' => 'NISN harus 10 digit',
            '*.nisn.unique' => 'NISN sudah terdaftar',

            '*.nama.required' => 'Nama wajib diisi',

            '*.kelas.required' => 'Kelas wajib diisi',

            '*.jurusan.required' => 'Jurusan wajib diisi',

            '*.tahun_angkatan.required' => 'Tahun angkatan wajib diisi',
            '*.tahun_angkatan.digits' => 'Tahun harus 4 digit',
            '*.tahun_angkatan.integer' => 'Tahun harus angka',
            '*.tahun_angkatan.min' => 'Tahun minimal 2000',
            '*.tahun_angkatan.max' => 'Tidak boleh lebih dari tahun ini',
        ];
    }
}