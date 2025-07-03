<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTutorialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'judul' => 'required|string|max:255',
            'isi' => 'nullable|array',
            'isi.*' => 'nullable|string',
            'media' => 'nullable|array',
            'media.*.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240',
        ];
    }
}
