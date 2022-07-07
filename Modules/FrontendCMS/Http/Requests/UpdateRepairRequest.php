<?php

namespace Modules\FrontendCMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRepairRequest extends FormRequest
{
    
    public function rules()
    {
        return [
            'title' => 'required',
            'slug' => 'required|unique:repairs,slug,'.$this->id,
            'icon' => 'required',
            'status' => 'required',
        ];
    }

    
    public function authorize()
    {
        return true;
    }
}
