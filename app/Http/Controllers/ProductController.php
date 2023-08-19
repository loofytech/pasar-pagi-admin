<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index() {
        return view('dashboard.product.index');
    }

    public function data(Request $request) {
        $query = Product::query();

        return DataTables::eloquent($query)->toJson();
    }

    public function create() {
        return view('dashboard.product.create');
    }

    public function store(Request $request) {
        try {
            DB::beginTransaction();

            $p = new Product();
            $p->product_name = $request->product_name;
            $p->product_slug = Str::slug($request->product_name, '-');
            $p->description = $request->product_description;
            $p->price = $request->price;
            $p->stock = $request->stock;
            $p->save();

            if ($request->photos) {
                foreach ($request->photos as $key => $photo) {
                    $imageName = time() . $key .'.'. $photo->extension();

                    $pI = new ProductImage();
                    $pI->product_id = $p->id;
                    $pI->photo = $imageName;
                    $pI->save();

                    $photo->move(public_path('products'), $imageName);
                }
            }

            DB::commit();
            return response()->json();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
