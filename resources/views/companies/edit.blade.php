@extends('layouts/app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit a company</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('companies.update', $company) }}"
                          enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name"
                                       value="@if(old('name')) {{ old('name') }} @else {{$company->name}} @endif"
                                       required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email"
                                   class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email"
                                       value="@if(old('email')) {{ old('email') }} @else {{$company->email}} @endif"
                                       required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="logo" class="col-md-4 col-form-label text-md-end">{{ __('Logo') }}</label>
                            <div class="col-md-6">
                                <input id="logo" type="file"
                                       class="form-control @error('logo') is-invalid @enderror" name="logo"
                                       value="@if(old('logo')) {{ old('logo') }} @else {{asset('storage/'.$company->logo)}} @endif"
                                       autocomplete="logo">

                                @error('logo')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address"
                                   class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <input id="suggest" type="text"
                                       class="form-control @error('address') is-invalid @enderror"
                                       name="address"
                                       value="@if(old('address')) {{ old('address') }} @else {{$company->address}} @endif"
                                       required autocomplete="address">

                                <p id="notice">Address not found</p>
                                <div id="map" class="map-create-edit"></div>

                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
