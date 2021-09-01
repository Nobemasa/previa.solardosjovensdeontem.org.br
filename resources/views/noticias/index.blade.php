@extends('layouts.app')

@section('content')
    <div class='container mt-3'>

        <h1>Notícias</h1>
        <hr>

        @if ($msg ?? '')
            <div class="text-center alert alert-primary"> {{ $msg }}</div>
        @endif
 
        <!-- inicio card de cadastro -->
        <div class="card text-center mb-3">
            <div class="card-header">
                Fotos em Exibição Online
            </div>
            <div class="card-body">
                @foreach ($noticias as $noticia )
                <img class="p-3" src="{{ asset('storage/'.$noticia->imagem) }}" width="200" height="200">
                @endforeach
            </div>
            <div class="card-footer text-muted">
                <div class="col-12 col-sm-3 float-right">
                    <button type="submit" class="form-control btn btn-primary float-right" data-toggle="modal" data-target="#modalCadastrarFotos">Cadastrar novo</button>
                </div>
            </div>
        </div>
  

        <!-- Modal -->
        <div class="modal fade" id="modalCadastrarFotos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastrar nova notícia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  method="post" action="{{route('add_noticias')}}" enctype="multipart/form-data">
            @csrf
                <div class="modal-body">
                    <input type="text" name="titulo" class="form-control" value='{{ old('inputTitulo') }}' placeholder="Título da Notícia">
                    <small class="form-text text-muted">{{ $errors->has('inputTitulo') ? $errors->first('inputTitulo') : ''}}</small>  

                    <input type="file" id="image" name="imagem" class="form-control">
                    <small class="form-text text-muted">{{ $errors->has('inputTitulo') ? $errors->first('inputTitulo') : ''}}</small>  

                    <div class="form-group">
                        <label for="descricao">Conteúdo da Notícia</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
            </div>
        </div>
        </div>

        <!-- inicio listagem  -->
        <div class="card">
            <div class="card-header">
                {{ $titulo_card ?? '5 Últimos Cadastros' }} 
            </div>

            <div class="card-body">
                <table  class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th scope="col col-1">ID</th>
                            <th scope="col col-2">Data</th>
                            <th scope="col col-3">Título</th>
                            <th scope="col col-2">Imagem</th>
                            <th scope="col col-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($noticias as $noticia )
                            <tr>
                                <td class="col-1">{{$noticia->id}}</td>
                                <td class="col-2">{{date( 'd/m/Y' , strtotime($noticia->created_at))}}</td>
                                <td class="col-3">{{$noticia->titulo}}</td>
                                <td class="col-2"><img src="{{ asset('storage/'.$noticia->imagem) }}" width="50" height="50"></td>
                                <td class="col-4">
                                    <div class="row">
                                        <div class="col-4">
                                            <a class="btn btn-primary btn-sm btn-block" href="">Detalhes</a>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalExcluir" data-idFoto="{{$noticia->id}}">Excluir</button>
                                        </div>
                                        <div class="col-4">
                                            <a class="btn btn-warning btn-sm btn-block" href="">Alterar</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach 
                    </tbody>
                </table>
            </div>    
        </div>
    
    </div> {{--  div container --}}

    {{-- Modal confirma exclusão --}}
    <!-- Modal -->
    <div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="TituloModalLongoExemplo">Atenção</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post" action="{{route('del_fotos')}}">
            @method('DELETE')
            <input name="_token" type="hidden" value="{{ @csrf_token() }}">
            <div class="modal-body">
                <h5 class="text-center">Confirma a exclusão desse item? </h5>
                <input name="id" id="id" type="text" value="">
            </div>
        
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Excluir</button>
            </div>
        </form>
        </div>
    </div>
    </div>
@endsection

<script type="text/javascript" >
    // EXCLUI FOTO
    $('#modalExcluir').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget);

        var foto_id = button.data('idFoto');
        var modal = $(this)
        console.log(foto_id)
        modal.find('#id').val(foto_id);
    })
</script>