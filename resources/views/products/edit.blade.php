@extends('layouts.app')

@section('content')
<!-- 商品情報編集画面のスタイルシートを読み込む -->
<link href="{{ asset('css/show_product.css') }}" rel="stylesheet">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">{{ __('商品情報編集') }}</div>

                <div class="card-body">
                    <!-- 商品編集フォーム -->
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- 商品ID -->
                        <div class="form-group">
                            <label for="id">商品ID</label>
                            <input type="text" class="form-control" id="id" value="{{ $product->id }}" disabled>
                        </div>

                        <!-- 商品名 -->
                        <div class="form-group">
                            <label for="product_name">商品名*</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
                            @error('product_name') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <!-- メーカー名 -->
                        <div class="form-group">
                            <label for="company_id">メーカー名*</label>
                            <select name="company_id" class="form-control" required>
                                <option value="">選択してください</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $product->company_id) == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <!-- 価格 -->
                        <div class="form-group">
                            <label for="price">価格*</label>
                            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            @error('price') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <!-- 在庫数 -->
                        <div class="form-group">
                            <label for="stock">在庫数*</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                            @error('stock') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <!-- コメント -->
                        <div class="form-group">
                            <label for="comment">コメント</label>
                            <textarea class="form-control" id="comment" name="comment">{{ old('comment', $product->comment) }}</textarea>
                            @error('comment') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <!-- 商品画像 -->
                        <div class="form-group">
                            <label for="img_path">商品画像</label>
                            <input type="file" class="form-control-file" id="img_path" name="img_path" style="background-color: #dcdcdc;">
                            @if($product->img_path)
                                <p>現在の画像:</p>
                                <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" class="img-fluid">
                            @endif
                            @error('img_path') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <!-- 更新ボタン -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-warning">更新</button>
                            <a href="{{ route('products.index') }}" class="btn btn-info">戻る</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
