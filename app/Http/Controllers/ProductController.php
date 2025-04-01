<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 商品一覧表示
    public function index(Request $request)
    {
        $companies = Company::all();

        $query = Product::query();

        if ($request->has('product_name') && $request->product_name != '') {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->has('company_id') && $request->company_id != '') {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('manufacturer') && $request->manufacturer != '') {
            $query->where('manufacturer', 'like', '%' . $request->manufacturer . '%');
        }

        $products = $query->paginate(10);

        return view('products.index', compact('products', 'companies'));
    }

    // 商品詳細表示
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    // 商品登録画面表示
    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    // 商品登録処理
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image',
        ]);

        if ($request->hasFile('img_path')) {
            $imgPath = $request->file('img_path')->store('images', 'public');
            $validated['img_path'] = $imgPath;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', '商品が登録されました');
    }

    // 商品編集画面表示
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    // 商品更新処理
    public function update(Request $request, $id)
    {
        // バリデーション
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // 商品データを更新
        $product = Product::findOrFail($id);
        $product->update([
            'product_name' => $validated['product_name'],
            'company_id' => $validated['company_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'comment' => $validated['comment'],
        ]);

        // 画像がアップロードされている場合、処理
        if ($request->hasFile('img_path')) {
            // 古い画像を削除する処理（必要に応じて）
            if ($product->img_path) {
                Storage::disk('public')->delete($product->img_path);
            }

            // 新しい画像を保存
            $imagePath = $request->file('img_path')->store('products', 'public');
            $product->img_path = $imagePath;
            $product->save();
        }

        return redirect()->route('products.index')->with('success', '商品情報が更新されました');
    }

    // 商品削除処理
    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('products.index')->with('success', '商品が削除されました');
    }
}
