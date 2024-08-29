<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TourokuRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_name' => 'required | max:255',
            'price' => 'required | max:255',
            'stock' => 'required | max:255',
            'company_name' => 'required | max:255',
            'comment' => 'required | max:255',
            'img_path' => 'required',

        ];
    }

   public function attributes()
   {
       return [
        'product_name' => '商品名',
        'price' => '価格',
        'stock' => '在庫数',
        'company_name' => 'メーカー名',
        'comment' => 'コメント',
        'img_path' => 'コメント',
       ];
   }

   /**
    * エラーメッセージ
    *
    * @return array
    */
   public function messages() {
       return [
           'product_name' => ':attributeは必須項目です。',
           'price' => ':attributeは必須項目です。',
           'stock' => ':attributeは必須項目です。',
           'company_name' => ':attributeは必須項目です。',
           'comment' => ':attributeは必須項目です。',
           'img_path' => ':attributeは必須項目です。'
       ];
   }
}




