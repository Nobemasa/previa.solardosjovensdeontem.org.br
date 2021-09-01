@extends('layouts.app')

@section('content')
    <div class='container mt-3'>

        <h1>Parceiros</h1>
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
                @foreach ($parceiros as $parceiro )
                <img class="p-3" src="{{ asset('storage/'.$parceiro->imagem) }}" width="200" height="200">
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
                <h5 class="modal-title" id="exampleModalLabel">Cadastrar novo parceiro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  method="post" action="{{route('add_parceiros')}}" enctype="multipart/form-data">
            @csrf
                <div class="modal-body">
                    <input type="text" name="nome" class="form-control" value='{{ old('inputTitulo') }}' placeholder="Nome completo da Parceira">
                    <small class="form-text text-muted">{{ $errors->has('inputTitulo') ? $errors->first('inputTitulo') : ''}}</small>  

                    <input type="text" name="zap" class="form-control" value='{{ old('inputZap') }}' placeholder="WhatssApp da Parceria">
                    <small class="form-text text-muted">{{ $errors->has('inputTitulo') ? $errors->first('inputTitulo') : ''}}</small>  

                    <input type="file" id="image" name="imagem" class="form-control">
                    <small class="form-text text-muted">{{ $errors->has('inputTitulo') ? $errors->first('inputTitulo') : ''}}</small>  

                    <div class="form-group">
                        <label for="descricao">Descrição prévia do perfil</label>
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
                {{ $titulo_card ?? 'Listagem de Parceiros' }} 
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
                        @foreach ($parceiros as $parceiro )
                            <tr>
                                <td class="col-1">{{$parceiro->id}}</td>
                                <td class="col-2">{{date( 'd/m/Y' , strtotime($parceiro->created_at))}}</td>
                                <td class="col-3">{{$parceiro->nome}}</td>
                                <td class="col-2"><img src="{{ asset('storage/'.$parceiro->imagem) }}" width="50" height="50"></td>
                                <td class="col-4">
                                    <div class="row">
                                        <div class="col-4">
                                            <a class="btn btn-primary btn-sm btn-block" href="">Detalhes</a>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalExcluir" data-idFoto="{{$parceiro->id}}">Excluir</button>
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