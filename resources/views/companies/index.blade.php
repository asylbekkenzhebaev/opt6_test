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
            <h3>Companies</h3>
        </div>
        @if (Auth::check())
            <div class="col-6">
                <a href="{{route('companies.create')}}" class="btn btn-success btn-lg float-end">Create a new company <i
                        class="bi bi-plus-square"></i></a>

            </div>
        @endif
    </div>

    <div class="row mt-2">
        <table id="companies-table" class="table table-stripped table-datatable">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>***</th>
            </tr>
            </thead>
            <tbody>
            @foreach($companies as $company)
                <tr>
                    <td>{{$company->id}}</td>
                    <td>{{$company->name}}</td>
                    <td>{{$company->email}}</td>
                    <td>{{$company->address}} </td>
                    <td class="action-edit-delete">
                        <a href="{{route('companies.show', ['company'=>$company])}}" class="btn btn-light btn-lg float-end" title="Show a company"><i class="bi bi-eye"></i></a>
                        @if (Auth::check())
                            <a href="{{route('companies.edit', ['company'=>$company])}}"
                               class="btn btn-light btn-lg float-end" title="Edit a company"><i
                                    class="bi bi-pencil-square"></i></a>
                            <form action="{{route('companies.destroy', $company)}}" method="POST" class="float-end"
                                  title="Delete a company">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light btn-lg"><i class="bi bi-trash3"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
