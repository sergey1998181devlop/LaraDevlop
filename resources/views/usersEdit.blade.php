@extends('layouts.app')

@section('content')
{{--@dd($departmentsUser)--}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Редактирование пользователя</div>

                    <div class="card-body">
                        <form method="POST" action="/users/add/successEditResult/">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Пользователь</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $departmentsUser->name }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" name="updatedUser" value="{{ $departmentsUser->id }}">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail адрес пользователя</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $departmentsUser->email }}" >

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Должность и Отдел пользователя</label>

                                <div class="col-md-6">

                                    <select  id="department"  multiple class="form-select @error('email') is-invalid @enderror" name="department[]"  required >
                                        @foreach($departsAll as $idDepartsAll => $valDepartsAll)
{{--                                            тут почемуто не находит должность первую а начиная с второй находит --}}
                                            @if(array_search( $valDepartsAll->depart_code , $departMessCurrentUser))
                                                <option value="{{$valDepartsAll->id}} " selected="selected" >{{$valDepartsAll->department}}  {{$valDepartsAll->position}}</option>
                                            @else
                                                <option value="{{$valDepartsAll->id}} "  >{{$valDepartsAll->department}}  {{$valDepartsAll->position}}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @error('department')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Роль пользователя</label>

                                <div class="col-md-6">

                                    <select  id="statusUser"  class="form-select @error('email') is-invalid @enderror" name="statusUser"  required >
                                            <option value="user" >Пользователь</option>

                                            <option value="moderation" >Модератор</option>


                                    </select>

                                    @error('statusUser')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>




                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                       Обновить данные  пользователя
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
