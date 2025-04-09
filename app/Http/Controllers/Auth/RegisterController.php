<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | このコントローラは、新規ユーザーの登録、バリデーション、および作成を処理します。
    | デフォルトでは、追加のコードなしでこの機能を提供するためにトレイトを使用します。
    |
    */

    use RegistersUsers;

    /**
     * ユーザー登録後のリダイレクト先URL
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * 新規インスタンスの作成
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * ユーザー登録リクエストのバリデータを取得
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // パスワード確認が必須
        ]);
    }

    /**
     * バリデーションを通過したユーザーインスタンスの作成
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
