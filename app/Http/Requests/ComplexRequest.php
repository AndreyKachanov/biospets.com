<?php

namespace App\Http\Requests;

use App\Models\Complex;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class ComplexRequest extends FormRequest
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
        if ($this->is_active == Complex::STATUS_NOT_ACTIVE) {
            return [
                'title' => 'required|max:255',
                'code' => "required|code_format|unique_complex_code",
                'term' => 'required|complex_term|integer|min:1|max:365',
                'discount' => 'nullable|less_than_price:price|regex:/\d{1,5},*\d*/',
                'price' => 'required|check_null:discount|regex:/\d{1,5},*\d*/',
                'analyse_id' => 'required',

                'meta_title' => 'max:80',
                'meta_description' => 'max:180',
                'meta_keywords' => 'max:255'
            ];
        } else {
            return [
                'title' => 'required|max:255',
                'code' => "required|code_format|unique_complex_code",
                'term' => 'required|complex_term|integer|min:1|max:365',
                'discount' => 'required|less_than_price:price|regex:/\d{1,5},*\d*/',
                'price' => 'required|check_null:discount|regex:/\d{1,5},*\d*/',
                'analyse_id' => 'required',

                'meta_title' => 'max:80',
                'meta_description' => 'max:180',
                'meta_keywords' => 'max:255'
            ];
        }
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => 'Название',
            'price' => 'Цена, руб.',
            'discount' => 'Цена со скидкой, руб.',
            'code' => 'Код',
            'term' => 'Срок (дней)'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        // Get max analyses term
        $maxDays = Session::pull('max');
        return [
            'title.required' => 'Поле Название комплекса обязательно для заполнения.',
            'price.regex' => 'Поле :attribute имеет ошибочный формат. Максимальное значение 99999,99.',
            'less_than_field' => "Поле :attribute не должно быть больше поля Цена, руб.",
            'code.required' => 'Поле :attribute обязательно для заполнения.',
            'code.regex' => "Поле :attribute имеет ошибочный формат. Правильный формат ввода - xx.xx.xxx.",
            'code.code_format' => 'Поле Код имеет ошибочный формат.',
            'term.required'  => 'Поле Срок (дней) обязательно для заполнения.',
            'term.complex_term' => "Количество дней в комплексе не может быть меньше количества дней в анализе 
                (" . $maxDays . " " . plural($maxDays, 'день', 'дня', 'дней') . ")",
            'price.required'  => 'Поле :attribute обязательно для заполнения.',
            'discount.required' => 'Поле Цена со скидкой, руб. обязательно для заполнения.',
            'discount.less_than_price' => 'Поле :attribute не должно быть больше или равно полю Цена, руб.',
            'analyse_id.required' => 'Добавьте хотя бы один анализ.',
            'code.unique_complex_code' => 'Такое значение поля Код уже существует.',
            'price.check_null' => 'Поле Цена, руб. не может быть равна или меньше 0.'
        ];
    }

}
