<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        if (count($roles) === 0) {
            return response()->json('No data', 404);
        }

        return response()->json($roles);
    }
}
