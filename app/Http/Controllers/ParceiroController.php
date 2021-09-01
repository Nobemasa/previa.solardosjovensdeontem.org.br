<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Parceiro;
use Illuminate\Http\Request;

class ParceiroController extends Controller
{
    public function __construct(Parceiro $parceiro){
        $this->parceiro = $parceiro;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parceiro = Parceiro::orderBy('id', 'DESC')->take(5)->get();
        return view('parceiros.index', ['parceiros' => $parceiro]);  
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate($this->parceiro->rules(), $this->parceiro->feedback());
        
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/parceiros', 'public');

        $parceiro = $this->parceiro->create([
            'nome' => $request->nome,
            'zap' => $request->zap,
            'imagem' => $imagem_urn,
            'descricao' => $request->descricao
        ]);

        $parceiro = Parceiro::orderBy('id', 'DESC')->take(5)->get();
        return view('parceiros.index', ['parceiros' => $parceiro]);  
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parceiro = $this->parceiro->find($id);
        if ($parceiro === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        } else {
            return response()->json($parceiro, 200);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {    
        // buscando o item 
        $parceiro = $this->parceiro->find($id);

        if ($parceiro === null){
            // caso não encontre o item pesquisado
            return response()->json(['erro' => 'Não foi possível alterar o item!'], 404);
        } 
        
        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($parceiro->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $parceiro->feedback());

        } else {
            // validação
            $request->validate($parceiro->rules(), $parceiro->feedback());

            //remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
            if($request->file('imagem')) {
                Storage::disk('public')->delete($parceiro->imagem);
            }
            
            $imagem = $request->file('imagem');
            // diretório para armazenar as fotos
            $imagem_urn = $imagem->store('imagens/parceiros', 'public');

            //preencher o objeto $foto com os dados do request
            $parceiro->fill($request->all());
            $parceiro->imagem = $imagem_urn;
      
            $parceiro->save();

            return response()->json($parceiro, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $parceiro = $this->parceiro->find($id);
        if ($parceiro === null){
            return response()->json(['erro' => 'Recurso para deletar não existe!'], 404);
        } 
        
        //remove o arquivo antigo
        Storage::disk('public')->delete($parceiro->imagem);
     
        $parceiro->delete();
        $parceiro = Parceiro::orderBy('id', 'DESC')->take(5)->get();
        return view('parceiros.index', ['parceiros' => $parceiro, 'msg' => 'Parceiro excluído com sucesso']); 
    }
}
