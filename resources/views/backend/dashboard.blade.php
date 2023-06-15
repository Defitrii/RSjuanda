@extends('layouts.backend_main')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard</h4>
                </div>

                <div class="card">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </symbol>
                        </svg>


                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                aria-label="Warning:">
                                <use xlink:href="#exclamation-triangle-fill" />
                            </svg>
                            <div>
                                Kami sangat menyarankan agar Anda melengkapi <strong>data profil</strong> Anda dan mengubah
                                <strong> password default</strong>
                                Anda secepat mungkin untuk menghindari kerentanan keamanan pada akun Anda.
                            </div>
                        </div>

                        <table class="table table-striped">
                            <tr>
                                <th colspan="2">No Rekening</th>
                            </tr>
                            <tr>
                                <td>BCA</td>
                                <td>12345689</td>
                            </tr>
                            <tr>
                                <td>BRI</td>
                                <td>12345689</td>
                            </tr>
                            <tr>
                                <td>BJB</td>
                                <td>12345689</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
@endsection
