@extends('layouts.backend.main')

@section('title', 'Edit Riwayat BMR')

@section('css')
<link rel="stylesheet" href="{{ asset('backend') }}/libs/sweetalert2/sweetalert2.min.css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="layout-specing">
        <div class="d-md-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Riwayat BMR</h5>

            <nav aria-label="breadcrumb" class="d-inline-block">
                <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                    <li class="breadcrumb-item text-capitalize"><a href="{{ route('histories.bmr') }}">Riwayat BMR</a>
                    </li>
                    <li class="breadcrumb-item text-capitalize active" aria-current="page">Edit Data</li>
                </ul>
            </nav>
        </div>

        <a href="{{ route('histories.bmr') }}" class="btn btn-warning btn-sm mt-4"><i
                class="fa-solid fa-arrow-left"></i> Kembali</a>

        <div class="col-lg-12 mt-4">
            <div class="card">
                <div class="container">
                    <div class="card-body">
                        <form action="{{ route('histories.bmr.update', $bmr->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Nama" name="name" value="{{ $bmr->name }}" autocomplete="name">
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
                                            name="tgl_input" value="{{ $bmr->tgl_input }}" autocomplete="tgl_input">
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
                                        <label class="form-label">Tinggi (cm) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('height') is-invalid @enderror"
                                            placeholder="Tinggi" name="height" value="{{ $bmr->height }}"
                                            autocomplete="height">
                                        @error('height')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('weight') is-invalid @enderror"
                                            placeholder="Berat" name="weight" value="{{ $bmr->weight }}"
                                            autocomplete="weight">
                                        @error('weight')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nilai BMR <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('result_bmr') is-invalid @enderror"
                                            placeholder="Nilai BMR" name="result_bmr" value="{{ $bmr->result_bmr }}"
                                            autocomplete="result_bmr">
                                        @error('result_bmr')
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
                window.location.href = "{{ route('histories.bmr') }}";
            }
        });
    @endif
</script>
@endsection
