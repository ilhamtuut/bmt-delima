@if (session('status'))
    <div class="alert alert-solid-success alert-dismissible d-flex align-items-center" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <span aria-hidden="true"></span>
        </button>
        <i class="mdi mdi-check-circle-outline me-2"></i>
        {{ session('status') }}
    </div>
@endif

@if ($errors->has('status'))
    <div class="alert alert-solid-danger alert-dismissible d-flex align-items-center" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <span
                aria-hidden="true"></span> </button>
        <i class="mdi mdi-alert-circle-outline me-2"></i>
        {{ $errors->first('status') }}
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-solid-success alert-dismissible d-flex align-items-center" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <span
                aria-hidden="true"></span> </button>
        <i class="mdi mdi-check-circle-outline me-2"></i>
        {{ Session::get('success') }}
    </div>
@endif

@if (Session::has('failed'))
    <div class="alert alert-solid-danger alert-dismissible d-flex align-items-center" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <span
                aria-hidden="true"></span> </button>
        <i class="mdi mdi-alert-circle-outline me-2"></i>
        {{ Session::get('failed') }}
    </div>
@endif

@if (Session::has('info'))
    <div class="alert alert-solid-info alert-dismissible d-flex align-items-center" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <span
                aria-hidden="true"></span> </button>
        <i class="mdi mdi-chat-alert-outline me-2"></i>
        {{ Session::get('info') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-solid-danger alert-dismissible" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> <span
                aria-hidden="true"></span> </button>
        @foreach ($errors->all() as $error)
            <li style="list-style:none;">{{ $error }}</li>
        @endforeach
    </div>
@endif
