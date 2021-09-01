<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct(Video $video){
        $this->video = $video;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $video = Video::orderBy('id', 'DESC')->take(5)->get();
        return view('videos.index', ['videos' => $video]);  
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $request->validate($this->video->rules(), $this->video->feedback());
    
        $video = $this->video->create([
            'titulo' => $request->titulo,
            'link' => $request->link
        ]);

        $video = Video::orderBy('id', 'DESC')->take(5)->get();
        return view('videos.index', ['videos' => $video]);  
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = $this->video->find($id);
        if ($video === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        } else {
            return response()->json($video, 200);
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
        $video = $this->video->find($id);

        if ($video === null){
            // caso não encontre o item pesquisado
            return response()->json(['erro' => 'Não foi possível alterar o item!'], 404);
        } 
        
        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($video->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $video->feedback());

        } else {
            // validação
            $request->validate($video->rules(), $video->feedback());


            //preencher o objeto $foto com os dados do request
            $video->fill($request->all());
      
            $video->save();

            return response()->json($video, 200);
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
        $video = $this->video->find($id);
        if ($video === null){
            return response()->json(['erro' => 'Recurso para deletar não existe!'], 404);
        } 
        
        $video->delete();
        $video = Video::orderBy('id', 'DESC')->take(5)->get();
        return view('videos.index', ['videos' => $video, 'msg' => 'Vídeo deletado com sucesso']); 
    }
}
