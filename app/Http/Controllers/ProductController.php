<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;  // Companyモデルをインポート
use App\Http\Requests\ProductRequest;  // ProductRequestをインポート
use Illuminate\Support\Facades\Storage;
use Exception;  // 例外処理に必要
use Illuminate\Http\Request;  // Requestのインポート

class ProductController extends Controller
{
    // 商品一覧表示
    public function index(Request $request)
    {
        $companies = Company::all();

        $query = Product::query();

        // 商品名検索
        if ($request->has('product_name') && $request->product_name != '') {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        // メーカー名検索
        if ($request->has('manufacturer') && $request->manufacturer != '') {
            $query->where('manufacturer', 'like', '%' . $request->manufacturer . '%');
        }

        // 検索結果を取得
        $products = $query->paginate(10);

        // ビューにデータを渡す
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
    public function store(ProductRequest $request)
    {
        try {
            // バリデーションが成功している場合
            $validated = $request->validated();

            // 画像が送信された場合、保存処理
            if ($request->hasFile('img_path')) {
                $imgPath = $request->file('img_path')->store('images', 'public');
                $validated['img_path'] = $imgPath;
            }

            // 商品情報を保存
            Product::create($validated);

            return redirect()->route('products.index')->with('success', '商品が登録されました');
        } catch (Exception $e) {
            // エラーハンドリング
            return back()->with('error', '商品登録に失敗しました。再度お試しください。')->withInput();
        }
    }

    // 商品編集画面表示
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    // 商品更新処理
    public function update(ProductRequest $request, $id)
    {
        try {
            // バリデーションが成功している場合
            $validated = $request->validated();

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
        } catch (Exception $e) {
            return back()->with('error', '商品更新に失敗しました。再度お試しください。')->withInput();
        }
    }

    // 商品削除処理
    public function destroy($id)
    {
        try {
            Product::destroy($id);
            return redirect()->route('products.index')->with('success', '商品が削除されました');
        } catch (Exception $e) {
            return redirect()->route('products.index')->with('error', '商品削除に失敗しました。再度お試しください。');
        }
    }
}
