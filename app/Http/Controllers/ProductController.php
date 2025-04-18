<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::all();

        $sortColumn = $request->get('sort_column', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');

        $query = Product::with('company');

        if ($request->has('product_name') && $request->product_name != '') {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->has('manufacturer') && $request->manufacturer != '') {
            $query->where('company_id', $request->manufacturer);
        }

        if ($request->has('price_min') && $request->price_min != '') {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max != '') {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->has('stock_min') && $request->stock_min != '') {
            $query->where('stock', '>=', $request->stock_min);
        }

        if ($request->has('stock_max') && $request->stock_max != '') {
            $query->where('stock', '<=', $request->stock_max);
        }

        $query->orderBy($sortColumn, $sortDirection);

        $products = $query->paginate(10);

    // 商品ごとの画像が存在するかどうかを確認
    foreach ($products as $product) {
        $product->img_exists = file_exists(public_path('storage/' . $product->img_path));  // 画像が存在するかチェック
    }

        if ($request->ajax()) {
            $html = view('products.partials.product_list', compact('products'))->render();

            return response()->json([
                'products' => $html,
                'pagination' => (string) $products->links(),
            ]);
        }

        return view('products.index', compact('products', 'companies', 'sortColumn', 'sortDirection'));
    }

    public function show($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('img_path')) {
                $imgPath = $request->file('img_path')->store('images', 'public');
                $validated['img_path'] = $imgPath;
            }

            Product::create($validated);

            return redirect()->route('products.index')->with('success', '商品が登録されました');
        } catch (Exception $e) {
            return back()->with('error', '商品登録に失敗しました。再度お試しください。')->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $product = Product::findOrFail($id);
            $product->update([
                'product_name' => $validated['product_name'],
                'company_id' => $validated['company_id'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
            ]);

            if ($request->hasFile('img_path')) {
                if ($product->img_path) {
                    Storage::disk('public')->delete($product->img_path);
                }

                $imagePath = $request->file('img_path')->store('images', 'public');
                $product->img_path = $imagePath;
                $product->save();
            }

            return redirect()->route('products.index')->with('success', '商品情報が更新されました');
        } catch (Exception $e) {
            return back()->with('error', '商品更新に失敗しました。再度お試しください。')->withInput();
        }
    }

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
