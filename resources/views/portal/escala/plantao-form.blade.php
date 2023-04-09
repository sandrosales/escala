@extends('layouts.portal.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item {{ request()->is('plantoes/mes') ? 'active' : '' }}">
                                <a href="{{ route('plantoes.mes') }}">Plantões</a>
                            </li>
                            <li class="breadcrumb-item active"><span class=" fas fa-plus-square mr-2"></span>Cadastrar Plantão
                            </li>
                        </ol>
                    </div>
                    <div class="card-header">Novo Plantão</div>
                    @if (session('info'))
                        <div class="alert alert-info hide-msg" style="float: left; width: 100%; margin: 10px 0px;">
                            {{ session('info') }}
                        </div>
                    @endif


                    <div class="card-body">
                        <form method="POST" action="{{ route('plantoes.store') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="funcionario_id"
                                    class="col-md-4 col-form-label text-md-right">Funcionário</label>

                                <div class="col-md-6">
                                    <select id="funcionario_id" name="funcionario_id" class="form-control">
                                        @foreach ($funcionarios as $funcionario)
                                            <option value="{{ $funcionario->id }}">{{ $funcionario->nome }}</option>
                                        @endforeach
                                    </select>

                                    @error('funcionario_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="data" class="col-md-4 col-form-label text-md-right">Data</label>

                                <div class="col-md-6">
                                    <input id="data" type="date"
                                        class="form-control @error('data') is-invalid @enderror" name="data"
                                        value="{{ old('data') }}" required autocomplete="off">

                                    @error('data')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tipo" class="col-md-4 col-form-label text-md-right">Tipo</label>

                                <div class="col-md-6">
                                    <select id="tipo" name="tipo" class="form-control">
                                        <option value="normal">Plantão Normal (24 horas)</option>
                                        <option value="extra">Plantão Extra (12 horas)</option>
                                    </select>

                                    @error('tipo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Salvar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
