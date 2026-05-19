<?php

namespace App\Http\ApiControllers;

use App\Models\Division;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;


class WorkerController
{
    /**
     * Display a listing of the resource.s
     */
    public function index(Request $request, Division $division) {
        if($request->ajax())
            return getResource(
                User::where("role_id", UserRole::byCode("division_worker")->id)
                    ->where("division_id", $division->id)
            );
    }
}
