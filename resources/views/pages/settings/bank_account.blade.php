@extends('layouts.app', ['page' => 'settings', 'active' => 'bank_account'])
@section('css')
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Settings /</span> Akun Bank Deposito</h4>
@endsection

@section('content')
    <div class="col-md-12">
        @include('layouts.partials.alert')
        <div class="card">
            <div class="card-header">
                <span class="badge bg-label-primary me-1 cursor-pointer showModal">
                    <i class="mdi mdi-plus-outline me-1"></i>Tambah
                </span>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped mb-4">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th>Kode Bank</th>
                            <th>Nama Bank</th>
                            <th>Nama Akun Bank</th>
                            <th>Nomor Rekening Bank</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($data as $i => $value)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $value->bank_code }}</td>
                                <td>{{ $value->bank_name }}</td>
                                <td>{{ $value->bank_account_name }}</td>
                                <td>{{ $value->bank_account_number }}</td>
                                <td>
                                    <span class="badge bg-label-info me-1 cursor-pointer showModal"
                                        data-id="{{ $value->id }}" data-bank_code="{{ $value->bank_code }}"
                                        data-bank_name="{{ $value->bank_name }}"
                                        data-bank_account_name="{{ $value->bank_account_name }}"
                                        data-bank_account_number="{{ $value->bank_account_number }}">
                                        <i class="mdi mdi-pencil-outline me-1"></i>
                                        Edit</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data masih kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-md-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2 pb-1"> Akun Bank</h3>
                    </div>
                    <form id="addNewForm" class="row g-4" action="{{ route('setting.store_bank_account') }}"
                        method="POST">
                        @csrf
                        <input class="form-control d-none" type="hidden" name="id" id="store_id" />
                        <input class="form-control d-none" type="hidden" name="bank_code" id="bank_code" />
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <select id="bank_name" name="bank_name" class="select2 form-select">
                                    <option value="">Select Bank</option>
                                    @foreach ($banks as $value)
                                        <option value="{{ $value->name }}" data-code="{{ $value->code }}">
                                            {{ $value->name }}</option>
                                    @endforeach
                                </select>
                                <label for="bank_name">Nama Bank</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" name="bank_account_name"
                                    id="bank_account_name" />
                                <label for="bank_account_name">Nama Akun Bank</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control numbering" type="text" name="bank_account_number"
                                    id="bank_account_number" />
                                <label for="bank_account_number">Nomor Rekening Bank</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary btn-reset" data-bs-dismiss="modal"
                                aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $('.showModal').click(function(e) {
            $('#store_id').val($(this).data('id'));
            $('#bank_code').val($(this).data('bank_code'));
            $('#bank_name').val($(this).data('bank_name'));
            $('#bank_account_name').val($(this).data('bank_account_name'));
            $('#bank_account_number').val($(this).data('bank_account_number'));
            new bootstrap.Modal(document.getElementById('addNewModal')).show();
        });

        $('#bank_name').change(function(e) {
            var code = $(this).find("option:selected").data('code');
            $('#bank_code').val(code);
        });

        var validation = FormValidation.formValidation(document.getElementById('addNewForm'), {
            fields: {
                bank_name: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan nama bank'
                        }
                    }
                },
                bank_account_name: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan nama akun bank'
                        }
                    }
                },
                bank_account_number: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan nomor akun bank'
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    // Use this for enabling/changing valid/invalid class
                    // eleInvalidClass: '',
                    eleValidClass: '',
                    rowSelector: '.col-12'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                // Submit the form when all fields are valid
                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            }
        });

        validation.on('core.form.valid', function() {
            $.blockUI({
                message: null
            });
        });

        $('.btn-reset').click(function(e) {
            validation.resetForm();
        });
    </script>
@endsection
