<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    public function index() {
        return view('dashboard.gallery.index');
    }

    public function data(Request $request) {
        $query = Gallery::query();

        return DataTables::eloquent($query)->toJson();
    }

    public function storeGallery(Request $request) {
        try {
            DB::beginTransaction();

            $g = new Gallery();
            $g->title = $request->title;
            $g->description = $request->description;
            $g->save();

            if ($request->photos) {
                foreach ($request->photos as $key => $photo) {
                    $imageName = time() .'.'. $photo->extension();

                    $gI = new GalleryImage();
                    $gI->gallery_id = $g->id;
                    $gI->photo = $imageName;
                    $gI->save();

                    $photo->move(public_path('teams'), $imageName);
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
