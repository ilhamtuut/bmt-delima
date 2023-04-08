@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-account-settings.css') }}?v={{ time() }}" />
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Bank Account</h4>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('layouts.partials.alert')
            <div class="card mb-4">
                <h5 class="card-header">Add Bank Account</h5>
                <div class="card-body">
                    <form id="formAccountSettings" method="POST" action="{{ route('profile.updateBank') }}" class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                        @csrf
                        <input class="form-control d-none" type="text" id="bank_code" name="bank_code">
                        <div class="row">
                            <div class="mb-4 col-md-6 fv-plugins-icon-container">
                                <div class="form-floating form-floating-outline">
                                    <select id="bank_name" name="bank_name" class="select2 form-select">
                                      <option value="">Select Bank</option>
                                      @foreach ($banks as $value)
                                        <option value="{{ $value->name }}" data-code="{{ $value->code }}">{{ $value->name }}</option>
                                      @endforeach
                                    </select>
                                    <label for="bank_name">Nama Bank</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-md-6  fv-plugins-icon-container">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="account_name" name="account_name">
                                    <label for="account_name">Nama Akun</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-md-6  fv-plugins-icon-container">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="account_number" name="account_number">
                                    <label for="account_number">Nomor Rekening</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2 waves-effect waves-light">Save
                                changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/pages-account-settings-account.js') }}?v={{ time() }}"></script>
@endsection

@section('customjs')
    <script>
        $('#bank_name').change(function (e) {
            var code = $(this).find("option:selected").data('code');
            console.log(code);
            $('#bank_code').val(code);
        })
    </script>
@endsection
