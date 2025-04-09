@extends('layouts.app')

@section('content')
<head>
    <!-- CSS ファイルのリンク -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <!-- カードのヘッダー部分: ログイン -->
                <div class="card-header">{{ __('ログイン') }}</div> <!-- ログインを日本語表記 -->

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- メールアドレス入力 -->
                        <div class="form-group">
                            <label for="email">{{ __('メールアドレス') }}</label> <!-- メールアドレスを日本語表記 -->
                            <input id="email" type="email" class="input-field @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- パスワード入力 -->
                        <div class="form-group">
                            <label for="password">{{ __('パスワード') }}</label> <!-- パスワードを日本語表記 -->
                            <input id="password" type="password" class="input-field @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- ログインフォームの「覚えておく」チェックボックス -->
                        <div class="form-group">
                            <div class="col-md-8 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">{{ __('ログイン状態を保持') }}</label> <!-- 「覚えておく」チェックボックスを日本語表記 -->
                                </div>
                            </div>
                        </div>

                        <!-- ボタンの配置 -->
                        <div class="button-group">
                            <!-- 新規登録ボタン -->
                            <a href="{{ route('register') }}" class="btn-register">
                                {{ __('新規登録') }} <!-- 新規登録を日本語表記 -->
                            </a>
                            <!-- ログインボタン -->
                            <button type="submit" class="btn-login">
                                {{ __('ログイン') }} <!-- ログインボタンを日本語表記 -->
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
