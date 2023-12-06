<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
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

        $data['items'] = Role::with('permissions')
        ->where('sistema', 'finance')
        ->paginate($request->page_size ?? 10)
        ->withQueryString();
        
        $data['permissions'] = Permission::where('sistema', 'finance')->get();
        
        return Inertia::render('GestaoPermissions/roles/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        
        $request->validate([
            'name' => 'required',
        ], []);
    
        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
                'sistema' => 'finance',
            ]);
            
            // $permissions = Permission::get();
            
            foreach ($request->permissions as $permission) {
                $role->givePermissionTo($permission);
                // if($permission == "todos"){
                //     foreach ($permissions as $p) {
                //     }
                // }
                // dd(  $permission );
            }
            
            return response()->json(['message' => "Perfil criado com sucesso!"]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => "Error ao! {$th}"]);
        }
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $instituicaoRenuncia
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findById($id);

        $data['items'] = $role->permissions()->get();
        $data['role'] = $role;
 
        return Inertia::render('GestaoPermissions/roles/Show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $instituicaoRenuncia
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $instituicaoRenuncia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

}
