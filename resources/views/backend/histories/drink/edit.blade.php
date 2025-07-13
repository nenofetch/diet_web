@extends('layouts.backend.main')

@section('title', 'Edit Riwayat Minuman')

@section('css')
<link rel="stylesheet" href="{{ asset('backend') }}/libs/sweetalert2/sweetalert2.min.css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="layout-specing">
        <div class="d-md-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Riwayat Minuman</h5>

            <nav aria-label="breadcrumb" class="d-inline-block">
                <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                    <li class="breadcrumb-item text-capitalize"><a href="{{ route('histories.drink') }}">Riwayat
                            Minuman</a></li>
                    <li class="breadcrumb-item text-capitalize active" aria-current="page">Edit Data</li>
                </ul>
            </nav>
        </div>

        <a href="{{ route('histories.drink') }}" class="btn btn-warning btn-sm mt-4"><i
                class="fa-solid fa-arrow-left"></i> Kembali</a>

        <div class="col-lg-12 mt-4">
            <div class="card">
                <div class="container">
                    <div class="card-body">
                        <form action="{{ route('histories.drink.update', $drink->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Minuman <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Nama Minuman" name="name" value="{{ $drink->name }}"
                                            autocomplete="name">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Input <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('tgl_input') is-invalid @enderror"
                                            name="tgl_input" value="{{ $drink->tgl_input }}" autocomplete="tgl_input">
                                        @error('tgl_input')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kalori <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('calories') is-invalid @enderror"
                                            placeholder="Kalori" name="calories" value="{{ $drink->calories }}"
                                            autocomplete="calories">
                                        @error('calories')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Karbohidrat <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('carbohydrates') is-invalid @enderror"
                                            placeholder="Karbohidrat" name="carbohydrates"
                                            value="{{ $drink->carbohydrates }}" autocomplete="carbohydrates">
                                        @error('carbohydrates')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Protein <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('protein') is-invalid @enderror"
                                            placeholder="Protein" name="protein" value="{{ $drink->protein }}"
                                            autocomplete="protein">
                                        @error('protein')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Lemak <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('fat') is-invalid @enderror" placeholder="Lemak"
                                            name="fat" value="{{ $drink->fat }}" autocomplete="fat">
                                        @error('fat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="submit" id="submit" name="send" class="btn btn-primary" value="Simpan">
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                        <!--end form-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
</div>
<!--end container-->
@endsection

@section('javascript')
<script src="{{ asset('backend') }}/libs/sweetalert2/sweetalert2.min.js"></script>
<script>
    // show dialog success
    @if (Session::has('message'))
        swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "{{ Session::get('message') }}",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('histories.drink') }}";
            }
        });
    @endif
</script>
@endsection
