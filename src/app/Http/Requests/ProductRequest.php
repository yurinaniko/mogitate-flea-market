<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // デフォルト（編集画面で画像そのままの時用）
        $imageRule = 'bail|mimes:jpeg,jpg,png|max:2048|image|nullable';

        // 編集(PUT/PATCH)のとき、画像削除が押されていたら必須に
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            if ($this->delete_image === "1") {
            $imageRule = 'bail|mimes:jpeg,jpg,png|max:2048|image|required';
            }
        }

        // 新規登録(POST)のときは常に必須
        if ($this->isMethod('post')) {
            $imageRule = 'bail|mimes:jpeg,jpg,png|max:2048|image|required';
        }


        $rules = [
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|between:0,10000',
            'seasons'     => 'required|array|min:1',
            'seasons.*'   => 'integer',
            'description' => 'required|string|max:120',
            'image'       => $imageRule,
        ];
        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->filled('seasons')) {
        $this->merge([
            'seasons' => array_map('intval', $this->seasons),
        ]);
        } else {
        $this->merge([
            'seasons' => [],  // 全部未選択なら空配列にする
        ]);
        }
    }

    public function attributes(): array
    {
        return [
            'name'        => '商品名',
            'price'       => '値段',
            'seasons'     => '季節',
            'description' => '商品説明',
            'image'       => '商品画像',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => '商品名を入力してください',
            'price.required'       => '値段を入力してください',
            'price.numeric'        => '数値で入力してください',
            'price.between'        => '0~10000円以内で入力してください',
            'seasons.required'     => '季節を選択してください',
            'description.required' => '商品説明を入力してください',
            'description.max'      => '120文字以内で入力してください',
            'image.required'       => '商品画像を入力してください',
            'image.mimes'          => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}