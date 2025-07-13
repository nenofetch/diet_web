@extends('layouts.backend.main')

@section('title', 'Edit Riwayat Edukasi')

@section('css')
<link rel="stylesheet" href="{{ asset('backend') }}/libs/sweetalert2/sweetalert2.min.css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="layout-specing">
        <div class="d-md-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Riwayat Edukasi</h5>

            <nav aria-label="breadcrumb" class="d-inline-block">
                <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                    <li class="breadcrumb-item text-capitalize"><a
                            href="{{ route('histories.educations-history.index') }}">Riwayat
                            Edukasi</a></li>
                    <li class="breadcrumb-item text-capitalize active" aria-current="page">Edit Data</li>
                </ul>
            </nav>
        </div>

        <a href="{{ route('histories.educations-history.index') }}" class="btn btn-warning btn-sm mt-4"><i
                class="fa-solid fa-arrow-left"></i> Kembali</a>

        <div class="col-lg-12 mt-4">
            <div class="card">
                <div class="container">
                    <div class="card-body">
                        <form action="{{ route('histories.educations-history.update', $educationHistoryActivity->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Edukasi <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('education_name') is-invalid @enderror"
                                            placeholder="Nama Edukasi" name="education_name"
                                            value="{{ $educationHistoryActivity->education_name }}"
                                            autocomplete="education_name">
                                        @error('education_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Input <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('tgl_input') is-invalid @enderror"
                                            name="tgl_input" value="{{ $educationHistoryActivity->tgl_input }}"
                                            autocomplete="tgl_input">
                                        @error('tgl_input')
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
                window.location.href = "{{ route('histories.educations-history.index') }}";
            }
        });
    @endif
</script>
@endsection
