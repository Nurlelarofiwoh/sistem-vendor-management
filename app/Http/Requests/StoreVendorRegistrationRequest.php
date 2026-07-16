<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRegistrationRequest extends FormRequest
{
    /**
     * Semua user (publik, tanpa login) boleh mengakses form ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_vendor' => ['required', 'string', 'max:255'],
            'kategori_jasa' => ['required', 'string', 'in:Venue,Catering,Dekorasi,Dokumentasi,Sound System,Entertainment,Attire,Makeup Artist'],
            'email' => ['required', 'email', 'max:255', 'unique:vendors,email'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:500'],
            'harga' => ['required', 'numeric', 'min:0'],
            'proposal_file' => ['required', 'file', 'mimes:pdf', 'max:5120'], // PDF, maks 5MB
            'link_portofolio' => ['nullable', 'url', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_vendor.required' => 'Nama vendor wajib diisi.',
            'nama_vendor.max' => 'Nama vendor maksimal 255 karakter.',
            'kategori_jasa.required' => 'Kategori jasa wajib dipilih.',
            'kategori_jasa.in' => 'Kategori jasa tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar dalam sistem kami.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'harga.required' => 'Estimasi harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'proposal_file.required' => 'File proposal wajib diunggah.',
            'proposal_file.mimes' => 'File proposal harus berformat PDF.',
            'proposal_file.max' => 'Ukuran file proposal maksimal 5MB.',
            'link_portofolio.url' => 'Link portofolio harus berupa URL yang valid (contoh: https://...).',
        ];
    }
}
