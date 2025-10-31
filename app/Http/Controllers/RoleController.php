<?php

namespace App\Http\Controllers;

use App\Http\Constants\AppConstant;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
 $roles = Role::where('name', '!=', AppConstant::DEFAULT_USER_ROLE["CUSTOMER"])
            ->orWhere('name', '!=', AppConstant::DEFAULT_USER_ROLE["FRONTEND_DEV"])
            ->get();
        return new RoleCollection($roles);
    }
    public function store(StoreRoleRequest $request){
        $role = Role::create($request->all());
        return new RoleResource($role);
    }

    public function update(UpdateRoleRequest $request,Role $role){
        $role->updated($request->all());
        return new RoleResource($role);
    }



}