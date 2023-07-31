<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $data['items'] = Permission::with('roles')->orderBy('id', 'desc')->paginate($request->page_size ?? 10)
        ->withQueryString();
        
        $data['roles'] = Role::get();
        
        return Inertia::render('GestaoPermissions/permissions/Index', $data);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $permission = Permission::create(['name' => $request->name]);
        
        $role = Role::findById($request->role_id);
        
        $permission->assignRole($role);
        
        return redirect()->back();
    }
    
        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $instituicaoRenuncia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update = Permission::findById($id);
        
        $update->name = $request->name;
        $update->update();
        
        return redirect()->back();
        
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $instituicaoRenuncia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $update = Permission::findById($id);
        
        $update->delete();
        
        return redirect()->back();
    }   
}
