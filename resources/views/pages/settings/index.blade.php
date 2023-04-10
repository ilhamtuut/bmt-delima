@extends('layouts.app',['page'=>'settings', 'active' => 'swithdrawal'])
@section('css')

@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Penarikan</h4>
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
                            <th>Nama</th>
                            <th>Nominal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($data as $i => $value)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $value->name }}</td>
                                <td>RP{{ number_format($value->value,0,',','.') }}</td>
                                <td>
                                    <span class="badge bg-label-info me-1 cursor-pointer showModal" data-id="{{ $value->id }}" data-nominal="{{ $value->value }}" data-name="{{ $value->name }}"><i
                                            class="mdi mdi-pencil-outline me-1"></i>
                                        Edit</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Data masih kosong</td>
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
                        <h3 class="mb-2 pb-1"> Penarikan</h3>
                    </div>
                    <form id="addNewForm" class="row g-4" action="{{ route('setting.update') }}" method="POST">
                        @csrf
                        <input class="form-control d-none" type="hidden" name="id" id="store_id" />
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="name" readonly/>
                                <label for="name">Nama</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="nominal" name="nominal" autofocus />
                                <label for="nominal">Nominal</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Simpan</button>
                            <button type="reset" class="btn btn-outline-secondary btn-reset" data-bs-dismiss="modal"
                                aria-label="Close">
                                Keluar
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
            $('#nominal').val($(this).data('nominal'));
            $('#name').val($(this).data('name'));
            new bootstrap.Modal(document.getElementById('addNewModal')).show();
        })

        var validation = FormValidation.formValidation(document.getElementById('addNewForm'), {
            fields: {
                nominal: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan nominal'
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
            $.blockUI({ message: null });
        });

        $('.btn-reset').click(function(e) {
            validation.resetForm();
        });

    </script>
@endsection
