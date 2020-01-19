<?php

namespace App\Http\Requests;

use App\Models\Analyse;
use Illuminate\Foundation\Http\FormRequest;

class AnalyseRequest extends FormRequest
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
        if ($this->is_active == Analyse::STATUS_NOT_ACTIVE) {
            return [
                'title' => 'required|max:1000',
                'code' => "required|code_format|unique_analyse_code",
                'discount' => 'nullable|less_than_price:price|regex:/\d{1,5},*\d*/',
                'price' => 'required|check_null:discount|regex:/\d{1,5},*\d*/',
                'title_lat' => 'nullable|max:255',
                'material' => 'max:255',
                'preparation' => 'max:255',
                'result' => 'max:255',
                'term' => 'required|check_complex_term|integer|min:0|max:365',
                'method' => 'max:255',
                'description' => 'nullable|limit_tinymce',

                'meta_title' => 'max:80',
                'meta_description' => 'max:180',
                'meta_keywords' => 'max:255'
            ];
        } else {
            return [
                'title' => 'required|max:1000',
                'code' => "required|code_format|unique_analyse_code",
                'discount' => 'required|less_than_price:price|regex:/\d{1,5},*\d*/',
                'price' => 'required|check_null:discount|regex:/\d{1,5},*\d*/',
                'title_lat' => 'max:255',
                'material' => 'required|max:255',
                'preparation' => 'max:255',
                'result' => 'required|max:255',
                'term' => 'required|check_complex_term|integer|min:0|max:365',
                'method' => 'max:255',
                'description' => 'nullable|limit_tinymce',

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
            'title' => 'Название анализа',
            'code' => 'Код',
            'discount' => 'Цена со скидкой, руб.',
            'price' => 'Цена, руб.',
            'title_lat' => 'Второе название',
            'material' => 'Биоматериал',
            'preparation' => 'Подготовка',
            'result' => 'Результат',
            'term' => 'Срок (дней)',
            'method' => 'Метод',
            'description' => 'Описание',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'title_lat.regex' => 'Поле Второе название должно иметь латинские буквы',
            'code.regex' => "Поле :attribute имеет ошибочный формат.",
            'discount.regex' => 'Поле :attribute имеет ошибочный формат. Максимальное значение 99999,99.',
            'price.regex' => 'Поле :attribute имеет ошибочный формат. Максимальное значение 99999,99.',
            'description.max' => 'Количество символов в поле Описание не может превышать 20000',
            'less_than_price' => "Поле :attribute не должно быть больше или равно полю Цена, руб.",
            'description.limit_tinymce' => 'Количество символов в поле Описание не может превышать 5000.',
            'code.code_format' => 'Поле Код имеет ошибочный формат.',
            'term.check_complex_term' => 'Измените значение в поле Срок (дней), т.к. комплекс, в который входит данный анализ, в поле Срок (дней) имеет меньшее значение.',
            'code.unique_analyse_code' => 'Такое значение поля Код уже существует.',
            'price.check_null' => 'Поле Цена, руб. не может быть равна или меньше 0.'
        ];
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $data = $this->all();
        $data['method'] = trim(preg_replace('/\s\s+/', '', $data['method']));
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }

}
