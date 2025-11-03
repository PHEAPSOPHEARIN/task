<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ability\StoreAbilityRequest;
use App\Http\Requests\Ability\StoreRoleAbilityRequest;
use App\Http\Requests\Ability\UpdateAbilityRequest;
use App\Http\Resources\Ability\AbilityConllection;
use App\Http\Resources\Ability\AbilityResource;
use App\Models\Ability;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AbilityController extends Controller
{
    public function index(Request $request)
    {
            // Gate::authorize('view', Ability::class);
        $abilities = Ability::query();
        if ($request->has('roleId')) {
            $abilities->whereHas('roles', function ($query) use ($request) {
                $query->where('role_id', $request->roleId);
            });
        }

        return new AbilityConllection($abilities->get());
    }

    


    public function show(Ability $ability)
    {
        // Gate::authorize('view', $ability);

        return new AbilityResource($ability);
    }

     
    public function update(UpdateAbilityRequest $request, Ability $ability)
    {
        $ability->update($request->all());

        return new AbilityResource($ability);
    }
    public function store(StoreAbilityRequest $request)
    {
        $ability = Ability::create($request->all());

        return new AbilityResource($ability);
    }

    public function destroy(Ability $ability)
    {
        // Gate::authorize('delete', $ability);
    }

    public function userAbility(Request $request)
    {
        $user = User::find($request->user()->id);
        $abilities = $user->role->abilities;

        return new AbilityConllection($abilities);
    }

    public function storeAbilitiesByRole(StoreRoleAbilityRequest $request, Role $role)
    {

        // UserLog::create([
        //     'user_id'=>request()->user()->id,
        //     'action'=>'create role ability',
        //     'data'=>json_encode($request),
        // ]);
        $role->abilities()->sync($request->input('abilityIds'));
        $users = User::where('role_id', $role->id)->get();
        foreach ($users as $user) {
            $user->tokens()->delete();
        }
    }
}