<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BranchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder,  $model): void
    {
        $user = Auth::user();

         if ($user && $user->active_branch_id) {
            $builder->where($model->getTable() . '.branch_id', $user->active_branch_id);
        }
    }
}
