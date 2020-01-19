<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyseCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'description' => 'nullable|limit_tinymce_category',
            'meta_title' => 'max:80',
            'meta_description' => 'max:180',
            'meta_keywords' => 'max:255'
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => 'Название категории',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'description.limit_tinymce_category' => 'Количество символов в поле Описание не может превышать 10000.'
        ];
    }
}
