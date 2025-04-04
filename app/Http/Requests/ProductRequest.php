<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 認証を通すためにtrueを返す
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
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // 画像のバリデーション
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_name.required' => '商品名は必須項目です。',
            'product_name.string' => '商品名は文字列でなければなりません。',
            'product_name.max' => '商品名は255文字以内で入力してください。',
            'company_id.required' => 'メーカーは必須項目です。',
            'company_id.exists' => '選択されたメーカーは無効です。',
            'price.required' => '価格は必須項目です。',
            'price.integer' => '価格は整数で入力してください。',
            'price.min' => '価格は0以上で入力してください。',
            'stock.required' => '在庫数は必須項目です。',
            'stock.integer' => '在庫数は整数で入力してください。',
            'stock.min' => '在庫数は0以上で入力してください。',
            'comment.string' => 'コメントは文字列で入力してください。',
            'img_path.image' => '商品画像は画像ファイルでなければなりません。',
            'img_path.mimes' => '商品画像はjpg, jpeg, png, gifのいずれかの形式である必要があります。',
            'img_path.max' => '商品画像は最大2MBまでです。',
        ];
    }
}
