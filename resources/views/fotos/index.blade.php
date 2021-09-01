@extends('layouts.app')

@section('content')
    <div class='container mt-3'>

        <h1>Fotos</h1>
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
                @foreach ($fotos as $foto )
                <img class="p-3" src="{{ asset('storage/'.$foto->imagem) }}" width="200" height="200"/>
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
                <h5 class="modal-title" id="exampleModalLabel">Cadastrar nova foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  method="post" action="{{route('add_fotos')}}" enctype="multipart/form-data">
            @csrf
                <div class="modal-body">
                    <input type="text" name="titulo" class="form-control" value='{{ old('inputTitulo') }}' placeholder="Título da Foto">
                    <small class="form-text text-muted">{{ $errors->has('inputTitulo') ? $errors->first('inputTitulo') : ''}}</small>  

                    <input type="file" id="imagem" name="imagem" class="form-control">
                    <small class="form-text text-muted">{{ $errors->has('inputTitulo') ? $errors->first('inputTitulo') : ''}}</small>  
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
                        @foreach ($fotos as $foto )
                            <tr>
                                <td class="col-1">{{$foto->id}}</td>
                                <td class="col-2">{{date( 'd/m/Y' , strtotime($foto->created_at))}}</td>
                                <td class="col-3">{{$foto->titulo}}</td>
                                <td class="col-2"><img src="{{ asset('storage/'.$foto->imagem) }}" width="50" height="50"></td>
                                <td class="col-4">
                                    <div class="row">
                                        <div class="col-4">
                                            
                                        </div>
                                        <div class="col-4">
                                            <a href="#" class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalExcluir" data-delfoto="{{$foto->id}}">Excluir</a>
                                        </div>
                                        <div class="col-4">
                                           
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
    <form method="post" action="{{ route('del_fotos', 'id') }}"> 
    @csrf
    @method('DELETE')
    <div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalLongoExemplo">Atenção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                    <h5 class="text-center">Confirma a exclusão desse item? </h5>
                    <input name="id" id="id" type="hidden" value="">
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
            </div>
        </div>
    </div>
    </form>
<script type="text/javascript">
    // EXCLUI FOTO
    $('#modalExcluir').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);

        var foto_id = button.data('delfoto');
        var modal = $(this)
        console.log(foto_id)
        modal.find('.modal-body #id').val(foto_id);
    })
</script>

@endsection

