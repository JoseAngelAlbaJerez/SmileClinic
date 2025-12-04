<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class RoleController extends Controller
{
    public function filter(Request $request)
{
    $filters = $request->input('filters', []);

    $query = Role::when(!empty($filters['name']), function ($q) use ($filters) {
        $q->where('name', 'LIKE', '%' . $filters['name'] . '%');
    });

    $roles = $query->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString();

    return response()->json([
        'roles' => $roles,
        'filters' => $filters,
    ]);
}

}
