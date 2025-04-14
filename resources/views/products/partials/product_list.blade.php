@foreach($products as $product)
<tr id="product-{{ $product->id }}">
    <td>{{ $product->id }}</td>
    <td>
    <img 
            src="{{ $product->img_exists 
                ? asset('storage/' . $product->img_path) 
                : url('images/no-image.png') }}" 
            alt="商品画像" 
            width="50">
    </td>
    <td>{{ $product->product_name }}</td>
    <td>{{ $product->price }}円</td>
    <td>{{ $product->stock }}</td>
    <td>{{ $product->company ? $product->company->company_name : '未登録' }}</td>
    <td>
        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info">詳細表示</a>
        <button type="button" class="btn btn-danger delete-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->product_name }}">削除</button>
    </td>
</tr>
@endforeach
