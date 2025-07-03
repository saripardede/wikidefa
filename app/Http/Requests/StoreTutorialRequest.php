<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTutorialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // pastikan user boleh kirim
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'isi' => 'nullable|array',
            'isi.*' => 'nullable|string',
            'media' => 'nullable|array',
            'media.*.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240',
        ];
    }
}
