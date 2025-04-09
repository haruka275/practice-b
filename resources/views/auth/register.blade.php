@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">{{ __('ユーザー新規登録') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- ユーザー名 -->
                        <div class="form-group">
                            <label for="name">{{ __('ユーザー名') }}</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                placeholder="ユーザ名" 
                                value="{{ old('name') }}" 
                                required 
                                autofocus
                            >
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- メールアドレス -->
                        <div class="form-group">
                            <label for="email">{{ __('メールアドレス') }}</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                placeholder="メールアドレス" 
                                value="{{ old('email') }}" 
                                required
                            >
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- パスワード -->
                        <div class="form-group">
                            <label for="password">{{ __('パスワード') }}</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                placeholder="パスワード" 
                                required
                            >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- パスワード確認 -->
                        <div class="form-group">
                            <label for="password_confirmation">{{ __('パスワード（確認用）') }}</label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="form-control" 
                                placeholder="パスワード（確認用）" 
                                required
                            >
                        </div>

                        <!-- ボタン群 -->
                        <div class="button-group d-flex justify-content-between">
                            <button 
                                type="submit" 
                                class="btn btn-warning btn-register">{{ __('新規登録') }}</button>
                            <a href="{{ route('login') }}" class="btn btn-info btn-back">{{ __('戻る') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
