<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'kegiatan_id' => 'required|exists:kegiatan,id',

            'nama_donatur' => 'required|string|max:255',

            'nomor_hp' => [
                'required',
                'regex:/^[0-9+\-\s()]+$/',
                'min:10',
                'max:15'
            ],

            'jumlah' => [
                'required',
                'numeric',
                'min:10000',
                'max:999999999'
            ],

            'catatan' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'kegiatan_id.required' =>
                'Program atau kegiatan donasi wajib dipilih.',

            'kegiatan_id.exists' =>
                'Kegiatan yang dipilih tidak valid.',

            'nama_donatur.required' =>
                'Nama donatur wajib diisi.',

            'nomor_hp.required' =>
                'Nomor HP wajib diisi.',

            'nomor_hp.regex' =>
                'Format nomor HP tidak valid.',

            'nomor_hp.min' =>
                'Nomor HP minimal terdiri dari 10 digit.',

            'nomor_hp.max' =>
                'Nomor HP maksimal 15 digit.',

            'jumlah.required' =>
                'Nominal donasi wajib diisi.',

            'jumlah.numeric' =>
                'Nominal donasi harus berupa angka.',

            'jumlah.min' =>
                'Minimal donasi adalah Rp 10.000.',

            'jumlah.max' =>
                'Nominal donasi terlalu besar.',
        ];
    }
}