<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 商品一覧表示
    public function index(Request $request)
    {
        // 商品検索の処理
        $companies = Company::all();

        // ソート用のカラムと方向を取得
        $sortColumn = $request->get('sort_column', 'id'); // デフォルトは'id'でソート
        $sortDirection = $request->get('sort_direction', 'desc'); // 初期状態は'desc'

        $query = Product::query();

        // 商品名検索
        if ($request->has('product_name') && $request->product_name != '') {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        // メーカー名検索
        if ($request->has('manufacturer') && $request->manufacturer != '') {
            $query->whereHas('company', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->manufacturer . '%');
            });
        }

        // 価格の絞り込み
        if ($request->has('price_min') && $request->price_min != '') {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max != '') {
            $query->where('price', '<=', $request->price_max);
        }

        // 在庫数の絞り込み
        if ($request->has('stock_min') && $request->stock_min != '') {
            $query->where('stock', '>=', $request->stock_min);
        }

        if ($request->has('stock_max') && $request->stock_max != '') {
            $query->where('stock', '<=', $request->stock_max);
        }

        // ソートの適用
        $query->orderBy($sortColumn, $sortDirection);

        // 結果をページネーションで取得
        $products = $query->paginate(10);

        // Ajax リクエストかどうかを確認
        if ($request->ajax()) {
            // 商品リストをHTMLとして返す
            $html = view('products.partials.product_list', compact('products'))->render();

            return response()->json([
                'products' => $html,  // 商品リストHTML
                'pagination' => (string) $products->links(),  // ページネーションHTML
            ]);
        }

        // 通常の表示
        return view('products.index', compact('products', 'companies', 'sortColumn', 'sortDirection'));
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
        $companies = Company::all();  // すべてのメーカーを取得
        return view('products.create', compact('companies'));
    }

    // 商品登録処理
    public function store(Request $request)
    {
        try {
            // バリデーション
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|numeric|min:0',
                'company_id' => 'required|exists:companies,id',
                'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // 画像が送信された場合、保存処理
            if ($request->hasFile('img_path')) {
                $imgPath = $request->file('img_path')->store('images', 'public');
                $validated['img_path'] = $imgPath;
            }

            // 商品情報を保存
            Product::create($validated);

            return redirect()->route('products.index')->with('success', '商品が登録されました');
        } catch (Exception $e) {
            return back()->with('error', '商品登録に失敗しました。再度お試しください。')->withInput();
        }
    }

    // 商品編集画面表示
    public function edit($id)
    {
        $product = Product::findOrFail($id);  // 指定されたIDの商品を取得
        $companies = Company::all();  // すべてのメーカーを取得
        return view('products.edit', compact('product', 'companies'));
    }

    // 商品更新処理
    public function update(Request $request, $id)
    {
        try {
            // バリデーション
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|numeric|min:0',
                'company_id' => 'required|exists:companies,id',
                'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // 商品データを更新
            $product = Product::findOrFail($id);
            $product->update([
                'product_name' => $validated['product_name'],
                'company_id' => $validated['company_id'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
            ]);

            // 画像がアップロードされている場合、処理
            if ($request->hasFile('img_path')) {
                // 古い画像を削除する処理（必要に応じて）
                if ($product->img_path) {
                    Storage::disk('public')->delete($product->img_path);
                }

                // 新しい画像を保存
                $imagePath = $request->file('img_path')->store('images', 'public');
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
            return response()->json(['success' => true, 'message' => '商品が削除されました']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => '商品削除に失敗しました。再度お試しください。']);
        }
    }
}
