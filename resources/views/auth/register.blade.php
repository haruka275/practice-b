@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('User Registration') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">{{ __('User Name') }}</label>
                            <input type="text" id="name" name="name" class="form-control input-field" placeholder="ユーザ名" required>
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input type="email" id="email" name="email" class="form-control input-field" placeholder="メールアドレス" required>
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input type="password" id="password" name="password" class="form-control input-field" placeholder="パスワード" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control input-field" placeholder="パスワード（確認用）" required>
                        </div>

                        <div class="button-group">
                            <button type="submit" class="btn btn-register">{{ __('Register') }}</button>
                            <a href="{{ route('login') }}" class="btn btn-back">{{ __('Back') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
