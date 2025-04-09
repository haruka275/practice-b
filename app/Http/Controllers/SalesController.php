<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    /**
     * 購入処理
     * 商品を購入し、salesテーブルに記録、在庫を減算します。
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーションルールの設定
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',  // 商品ID（必須、productsテーブルに存在すること）
            'quantity' => 'required|integer|min:1',         // 購入数量（必須、整数、最小値1）
        ]);

        // バリデーションエラーがあればエラーレスポンスを返す
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '無効なデータがあります。',
                'errors' => $validator->errors(),
            ], 422);
        }

        // リクエストから商品IDと購入数量を取得
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // 商品を取得
        $product = Product::findOrFail($productId);

        // 在庫数が購入数量より少ない場合、エラーレスポンス
        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => '在庫数が不足しています。',
            ], 400);
        }

        // トランザクションの開始（データ整合性のため）
        DB::beginTransaction();

        try {
            // 商品の在庫を減算
            $product->decrement('stock', $quantity);

            // salesテーブルに購入情報を追加
            Sale::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'purchased_at' => now(),  // 購入日時を現在の日時に設定
            ]);

            // トランザクションをコミット
            DB::commit();

            // 成功レスポンス
            return response()->json([
                'success' => true,
                'message' => '購入が完了しました。',
            ], 200);

        } catch (\Exception $e) {
            // トランザクション失敗時にロールバック
            DB::rollBack();

            // エラーレスポンス
            return response()->json([
                'success' => false,
                'message' => '購入処理に失敗しました。再度お試しください。',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
