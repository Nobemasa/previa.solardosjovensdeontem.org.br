<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Foto;
use Illuminate\Http\Request;

class FotoController extends Controller
{
    public function __construct(Foto $foto){
        $this->foto = $foto;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foto = Foto::orderBy('id', 'DESC')->take(5)->get();
        return view('fotos.index', ['fotos' => $foto]);  
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $request->validate($this->foto->rules(), $this->foto->feedback());
        
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/fotos', 'public');

        $foto = $this->foto->create([
            'titulo' => $request->titulo,
            'imagem' => $imagem_urn
        ]);

        $foto = Foto::orderBy('id', 'DESC')->take(5)->get();
        return view('fotos.index', ['fotos' => $foto]);  
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $foto = $this->foto->find($id);
        if ($foto === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        } else {
            return response()->json($foto, 200);
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
        $foto = $this->foto->find($id);

        if ($foto === null){
            // caso não encontre o item pesquisado
            return response()->json(['erro' => 'Não foi possível alterar o item!'], 404);
        } 
        
        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($foto->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $marca->feedback());

        } else {
            // validação
            $request->validate($foto->rules(), $foto->feedback());

            //remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
            if($request->file('imagem')) {
                Storage::disk('public')->delete($foto->imagem);
            }
            
            $imagem = $request->file('imagem');
            // diretório para armazenar as fotos
            $imagem_urn = $imagem->store('imagens/fotos', 'public');

            //preencher o objeto $foto com os dados do request
            $foto->fill($request->all());
            $foto->imagem = $imagem_urn;
      
            $foto->save();

            return response()->json($foto, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id= $request->id;
        
        $foto = $this->foto->find($id);
        if ($foto === null){
            return response()->json(['erro' => 'Recurso para deletar não existe!'], 404);
        } 
        
        //remove o arquivo antigo
        Storage::disk('public')->delete($foto->imagem);
     
        $foto->delete();
        $foto = Foto::orderBy('id', 'DESC')->take(5)->get();
        return view('fotos.index', ['fotos' => $foto, 'msg' => 'Foto deletado com sucesso']); 
    }
}
