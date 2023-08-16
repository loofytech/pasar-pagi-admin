<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    public function home() {
        return view('dashboard.settings.home');
    }

    public function information() {
        return view('dashboard.settings.information');
    }

    public function data(Request $request) {
        $query = Setting::query();

        return DataTables::eloquent($query)
                // ->addColumn('intro', 'Hi {{$name}}!')
                ->toJson();
    }

    public function updateSetting(Request $request, $prefix) {
        try {
            DB::beginTransaction();

            $setting = Setting::take(1)->first();
            $setting[$prefix] = $request[$prefix];
            $setting->save();

            DB::commit();

            return response()->json();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
