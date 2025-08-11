<?php

namespace App\Http\Requests\API\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ExportRepairPdfRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }
}
