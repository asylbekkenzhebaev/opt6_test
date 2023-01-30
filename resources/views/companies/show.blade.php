@extends('layouts/app')

@section('content')

    @if(session('status'))
        <div class="row justify-content-center">
            <div class="col-6 text-center">
                <div class="alert alert-{{session('status')['color']}}" role="alert">
                    {{session('status')['text']}}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-6">
            <p class="cpName">
                <b>Name: </b> <span>{{$company->name}}</span>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <p class="cpEmail">
                <b>Email: </b> <span>{{$company->email}}</span>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <p class="cpAddress">
                <b>Address: </b> <span id="addressMap">{{$company->address}}</span>
            </p>
        </div>
    </div>

    @if( $company->logo )
        <div class="row text-center wp-100 mb-2 ms-1">
            <img src="{{asset('storage/'.$company->logo)}}" class="img-thumbnail rounded cpLogo" alt="logo company">
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <h3>Employees</h3>
        </div>
        <div class="col-md-4">
            @if (Auth::check())
                <a href="{{route('employees.create', ['company'=>$company])}}"
                   class="btn btn-success btn-lg me-1 float-end" title="Create a new employee">Create a new employee <i
                        class="bi bi-personi bi-person-add"></i> </a>
            @endif
        </div>
    </div>

    <div class="row mt-3">
        <table id="employees-table" class="table table-stripped table-datatable">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>***</th>
            </tr>
            </thead>
            <tbody>
            @foreach($company['employees'] as $employee)
                <tr>
                    <td>{{$employee->id}}</td>
                    <td>{{$employee->name}}</td>
                    <td>{{$employee->email}}</td>
                    <td>{{$employee->phone}}</td>
                    <td class="action-edit-delete float-end">
                        @if (Auth::check())
                            <a href="{{route('employees.edit', $employee)}}"
                               class="btn btn-light btn-lg me-1 text-decoration-none">
                                <i class="bi bi-personi bi-person-gear"></i>
                            </a>
                            <form action="{{route('employees.destroy', $employee)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light btn-lg float-end"><i
                                        class="bi bi-personi bi-person-x"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <p id="notice">Address not found</p>
            <div id="map" class="map-show"></div>
        </div>
    </div>

@endsection
