@extends('layouts.app')

@section('content')

<section class="homedoctor-page">
    <div class="container">

        <!-- TÍTULO -->
        <h2 class="hd-title">Hola, Dra. Smith</h2>

        <div class="row g-4">

            <!-- PROXIMAS CITAS -->
            <div class="col-lg-7">
                <h5 class="hd-section-title">Próximas Citas</h5>

                <div class="card hd-card mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>John Doe</strong>
                            <p class="small text-muted mb-0">10:00 AM – 11:00 AM</p>
                        </div>
                        <button class="btn btn-sm hd-btn-outline">Confirmar</button>
                    </div>
                </div>

                <div class="card hd-card mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Jane Smith</strong>
                            <p class="small text-muted mb-0">1:00 PM – 2:00 PM</p>
                        </div>
                        <button class="btn btn-sm hd-btn-outline">Confirmar</button>
                    </div>
                </div>

                <div class="card hd-card mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Sam Wilson</strong>
                            <p class="small text-muted mb-0">3:00 PM – 4:00 PM</p>
                        </div>
                        <button class="btn btn-sm hd-btn-outline">Confirmar</button>
                    </div>
                </div>

                <button class="btn hd-btn-primary mt-3">
                    Ver todas las citas próximas
                </button>
            </div>

            <!-- TRATAMIENTOS RECIENTES -->
            <div class="col-lg-5">
                <h5 class="hd-section-title">Tratamientos Recientes</h5>

                <div class="card hd-card mb-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>Jane Smith</strong>
                            <p class="small text-muted mb-0">Dic 15, 2022</p>
                        </div>
                        <span class="fw-semibold">$150</span>
                    </div>
                </div>

                <div class="card hd-card mb-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>Sam Wilson</strong>
                            <p class="small text-muted mb-0">Dic 14, 2022</p>
                        </div>
                        <span class="fw-semibold">$300</span>
                    </div>
                </div>

                <div class="card hd-card mb-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>John Doe</strong>
                            <p class="small text-muted mb-0">Dic 13, 2022</p>
                        </div>
                        <span class="fw-semibold">$200</span>
                    </div>
                </div>

                <button class="btn hd-btn-primary mt-3 w-100">
                    Ver todos los tratamientos
                </button>
            </div>

        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="mt-5">
            <h5 class="hd-section-title">Acciones Rápidas</h5>

            <div class="hd-quick-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="hd-icon-circle">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <span>Registrar nuevo paciente</span>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
            </div>

            <div class="hd-quick-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="hd-icon-circle">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <span>Consultar historial</span>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/homedoctor.css') }}">
@endpush
