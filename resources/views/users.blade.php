@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card ">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="text-left title-empl">Список сотрудников</h4>
                    </div>
                    @if(Auth::user()->statusUser == "admin" )
                        <div class="col-md-6">
                            <a href="/users/add">
                               <div class="btn-pos text-right add-user ">
                                   <button type="button" class="btn btn-info">Добавить пользователя</button>
                               </div>
                            </a>
                        </div>
                    @endif
                </div>




                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Поьзователь</th>
                        <th scope="col">Отдел</th>
                        <th scope="col">Должность</th>
                        <th scope="col">Управление</th>
                    </tr>
                    </thead>
                    <th>
                        @foreach ($employees as $id =>  $employee)
                            <tr>
                                <th scope="row">{{ $id }}</th>
                                <td>{{ $employee->name }}</td>

                                @foreach ($employee->departments as $IdDepartment  => $department)


                                        @if($IdDepartment == 0)
                                            <td>{{ $department->department }}</td>
                                            <td>{{ $department->position }}</td>
                                        @else
                                            </tr>
                                            <th scope="row"></th>
                                                        <td></td>
                                                        <td>{{ $department->department }}</td>
                                                        <td>{{ $department->position }}</td>
                                        @endif
                                            @if($IdDepartment == 0)

                                                @if(Auth::user()->statusUser == "admin" || Auth::user()->statusUser == "moderation")
                                                    @if(Auth::user()->id !=  $employee->id)
                                                        <td>
                                                            <a href="/users/edit/{{$employee->id}}/">
                                                                  <button type="button" class="btn btn-warning">Редактировать</button>
                                                            </a>
                                                        </td>
                                                     @endif
                                                @endif
                                                @if(Auth::user()->statusUser == "admin")
                                                        @if(Auth::user()->id !=  $employee->id)
                                                            <td>
                                                                <a href="/users/del/{{$employee->id}}/">
                                                                     <button type="button" class="btn btn-danger">Удалить</button>
                                                                </a>
                                                            </td>
                                                        @endif
                                                @endif
                                            @endif
                                    </th>

                                @endforeach


                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
