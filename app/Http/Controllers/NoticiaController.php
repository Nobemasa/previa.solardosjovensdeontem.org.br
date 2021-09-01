<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Noticia;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    public function __construct(Noticia $noticia){
        $this->noticia = $noticia;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noticia = Noticia::orderBy('id', 'DESC')->take(5)->get();
        return view('noticias.index', ['noticias' => $noticia]);  
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate($this->noticia->rules(), $this->noticia->feedback());
        
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/noticias', 'public');

        $noticia = $this->noticia->create([
            'titulo' => $request->titulo,
            'imagem' => $imagem_urn,
            'descricao' => $request->descricao
        ]);

        $noticia = Noticia::orderBy('id', 'DESC')->take(5)->get();
        return view('noticias.index', ['noticias' => $noticia]);  
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $noticia = $this->noticia->find($id);
        if ($noticia === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        } else {
            return response()->json($noticia, 200);
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
        $noticia = $this->noticia->find($id);

        if ($noticia === null){
            // caso não encontre o item pesquisado
            return response()->json(['erro' => 'Não foi possível alterar o item!'], 404);
        } 
        
        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($noticia->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $noticia->feedback());

        } else {
            // validação
            $request->validate($noticia->rules(), $noticia->feedback());

            //remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
            if($request->file('imagem')) {
                Storage::disk('public')->delete($noticia->imagem);
            }
            
            $imagem = $request->file('imagem');
            // diretório para armazenar as fotos
            $imagem_urn = $imagem->store('imagens/noticias', 'public');

            //preencher o objeto $foto com os dados do request
            $noticia->fill($request->all());
            $noticia->imagem = $imagem_urn;
      
            $noticia->save();

            return response()->json($noticia, 200);
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
        $noticia = $this->noticia->find($id);
        if ($noticia === null){
            return response()->json(['erro' => 'Recurso para deletar não existe!'], 404);
        } 
        
        //remove o arquivo antigo
        Storage::disk('public')->delete($noticia->imagem);
     
        $noticia->delete();
        $noticia = Noticia::orderBy('id', 'DESC')->take(5)->get();
        return view('noticias.index', ['noticias' => $noticia, 'msg' => 'Notícia excluído com sucesso']); 
    }
}
