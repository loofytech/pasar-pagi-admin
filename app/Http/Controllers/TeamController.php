<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TeamController extends Controller
{
    public function index() {
        return view('dashboard.team.index');
    }

    public function getData(Request $request) {
        $query = Team::query();

        return DataTables::eloquent($query)->toJson();
    }

    public function store(Request $request) {
        try {
            if (!$request->hasFile('photo')) {
                throw new \Exception('Error request photo');
            }

            $imageName = time() .'.'. $request->photo->extension();

            DB::beginTransaction();

            $team = new Team();
            $team->name = $request->name;
            $team->position = $request->position;
            $team->photo = $imageName;
            $team->save();

            DB::commit();

            $request->photo->move(public_path('teams'), $imageName);
            return response()->json();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
