<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index() {
        return view("dashboard.category.index");
    }

    public function store(Request $request) {
        try {
            DB::beginTransaction();

            $category = new Category();
            $category->category_name = $request->category_name;
            $category->category_slug = Str::slug($request->category_name, '_');
            $category->save();

            DB::commit();

            return response()->json();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getData(Request $request) {
        $query = Category::query();

        return DataTables::eloquent($query)
                // ->addColumn('intro', 'Hi {{$name}}!')
                ->toJson();
    }
}
