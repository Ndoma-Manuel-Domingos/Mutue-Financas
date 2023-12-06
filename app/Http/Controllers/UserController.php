<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\GrupoUtilizador;
use App\Models\Pessoa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    //
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
     
    public function lista_utilizadores(Request $request)
    {
        $user = auth()->user();
        
        $validacao = Grupo::where('designacao', "Validação de Pagamentos")->select('pk_grupo')->first();
        $admins = Grupo::where('designacao', 'Administrador')->select('pk_grupo')->first();
        $finans = Grupo::where('designacao', 'Area Financeira')->select('pk_grupo')->first();
        $tesous = Grupo::where('designacao', 'Tesouraria')->select('pk_grupo')->first();

        // $array_utilizadores = GrupoUtilizador::whereIn('fk_grupo', [$validacao->pk_grupo, $finans->pk_grupo, $admins->pk_grupo, $tesous->pk_grupo])
        // ->orWhere('fk_utilizador', Auth::user()->pk_utilizador)
        // ->with('utilizadores')
        // ->pluck('fk_utilizador');
        
        // $data["utilizadores"] = User::when($request->nome_text_input, function($query, $value){
        //     $query->where('nome', "like" ,"%{$value}%");
        // })
        // ->with('roles')->whereIn('pk_utilizador', $array_utilizadores)->where('active_state', 1)
        // ->paginate($request->page_size ?? 10);
        
        // whereIn('user_pertence', ['Finance','Finance-Cash'])->
        
        $data["utilizadores"] = User::whereIn('user_pertence', ['Finance','Finance-Cash', 'Todos'])
        ->when($request->nome_text_input, function($query, $value){
            $query->where('nome', "like" ,"%{$value}%");
        })
        ->with(['roles', 'pessoa'])
        ->where('active_state', 1)
        // ->join('tb_pessoa', DB::raw('json_extract(mca_tb_utilizador.ref_pessoa, "$.pk")'), '=', 'tb_pessoa.pk_pessoa')
        ->paginate($request->page_size ?? 10);
        
        $data['roles'] = Role::where('sistema', 'finance')->get();
    
        return Inertia::render('GestaoPermissions/utilizadores/Index', $data);
    }  
    
    
    public function criar_utilizadores(Request $request)
    {
        $request->validate([
            "nome" => 'required',
            "senha" => 'required',
            "confirmar_senha" => 'required',
            "email" => 'required',
            // "role_id" => 'required',
            "bilheite" => 'required',
        ], []);
        
        // dd($request->all());
        
        try {
            if($request->senha != $request->confirmar_senha){
                return response()->json(['error' => 'Senhas diferentes', 'status' => 404]);
            }
                                   
            $create_pessoa = Pessoa::create([
                'nome_completo' => $request->nome,
                'nome_do_pai' => NULL,
                'nome_da_mae' => NULL,
                'data_de_nascimento' => $request->data_de_nascimento,
                'num_doc_identificacao' => $request->bilheite,
                'fk_tipo_documento_identificacao' => 1,
                'data_de_emissao_documento' => $request->data_emissao,
                'data_de_expiracao_documento' => $request->data_expiracao,
                'fk_genero' => $request->genero,
                'fk_nacionalidade' => 25,
                'endereco' => NULL,
                'fk_naturalidade' => 1,
                'fk_estado_civil' => 1,
                'telefone1' => $request->telefone,
                'telefone2' => $request->telefone,
                'email' => $request->email,
            ]);
            
            
            $dados = [
                "pk"=> $create_pessoa->pk_pessoa, 
                "desc"=> $create_pessoa->nome_completo
            ];
            
            $json = json_encode($dados);
            
            $user_mutue['tipoUtilizador']=null;
            $usernames = preg_split('/\s+/', strtolower($request->nome), -1, PREG_SPLIT_NO_EMPTY);
            $username = head($usernames) . '.' . last($usernames);
            
            $create = User::create([
                'nome' => $request->nome,
                'userName' => $username,
                'password' => md5($request->senha) ,
                'user_pertence' =>  $request->user_pertence,
                'ref_pessoa' => $json,
            ]);
            
            $create->codigo_importado = $create->pk_utilizador;
            $create->update();
            
            
            // $role = Role::findById($request->role_id);
            
            // if($role){
            //     $create->assignRole($role);
            // }
            
            return response()->json(['message' => 'Dados salvos com sucesso', 'status' => 200]);
            //code...
        } catch (\Throwable $th) {
        
            return response()->json(['error' => 'Não foi possível cadastrar utilizadores', 'status' => 404]);
        }
    
    }
        

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPerfilUtilizador($user_id)
    {
        $user = auth()->user();
        
        $user = User::with(['roles'])
            ->where('codigo_importado', $user_id)
            ->first();
     
        return response()->json(['utilizador' => $user]);
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */   
    public function removerPerfilUtilizador($id)
    {
        $user = auth()->user();

        $user = User::with('roles')->where('codigo_importado', $id)->first();
        
        foreach ($user->roles as $role){
            $user->removeRole($role);
        }

        return redirect()->back();
    }
    
    
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
     
    public function adicionar_perfil_utilizador(Request $request)
    {
        $user = auth()->user();
        
        $request->validate(
            ['role_id' => 'required'],
            ['user_id' => 'required'],
            ['role_id.required' => "Obrigatória"],
            ['user_id.required' => "Obrigatória"]
        ); 
              
        try {
            
            $user = User::with('roles')->where('codigo_importado', $request->user_id)->first();
            
            foreach ($user->roles as $role){
                $user->removeRole($role);
            }
            
            foreach ($request->role_id as $value) {
                $roles = Role::findById($value);
                $user->assignRole($roles);
            }
         
            return response()->json(['message' => "Perfil actualizado com sucesso!"]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => "Error ao! {$th}"]);
        }
    }
    
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPermissionsRole($role_id)
    {
        $user = auth()->user();
        
        $permissions = Role::with('permissions')
            ->where('id', $role_id)
            ->first();
        return response()->json(['role' => $permissions]);
    }
    
    
        
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adicionar_permissions(Request $request)
    {
        $user = auth()->user();
        
        $request->validate(
            ['role_id' => 'required'],
            ['permissions_id' => 'required'],
            ['role_id.required' => "Obrigatória"],
            ['permissions_id.required' => "Obrigatória"]
        ); 
         
        try {
    
            $permissions = Role::with('permissions')->where('id', $request->role_id)->first();
            $role = Role::findById($request->role_id);
    
            foreach ($permissions->permissions as $permission) {
                $permission->removeRole($role);
            }
    
            if($request->permissions_id){
                foreach ($request->permissions_id as $permission) {
                    $role->givePermissionTo($permission);
                }
            }
            
            return response()->json(['message' => "Perfil actualizado com sucesso!"]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => "Error ao! {$th}"]);
        }

    }
    
}
