@extends('layouts.app', [
    'class' => 'register-page',
    'backgroundImagePath' => 'img/bg/jan-sendereks.jpg'
])

@section('content')
<div class="wrapper">
    <!-- Background Video-->
    <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
        <source src="{{ asset('mp4/bg-video.mp4') }}" type="video/mp4" />
    </video>
    
    
    <!-- Gradient Overlay -->
        <div class="gradient-overlay"></div>    

        <div class="container-login">
            <div class="row">
                <div class="col-lg-7 col-md-5 ml-auto">
                    <h2 class="description info-title mb-2">Syarat dan Ketentuan</h2>
                    <div class="terms info-horizontal">
                        <div class="description">
                            <h5 class="info-title mb-0">1. Akses dan penggunaan</h5>
                            <p class="description">
                                <ul>
                                    <li>Sistem ini hanya dapat digunakan oleh civitas akademika Politeknik Negeri Malang, termasuk mahasiswa, dosen, dan staf.</li>
                                    <li>Pengguna wajib menjaga kerahasiaan kredensial.</li>
                                    <li>Penyalahgunaan sistem untuk laporan palsu, spam, atau informasi tidak relevan akan dikenakan sanksi sesuai ketentuan kampus.</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    <div class="terms info-horizontal">
                        <div class="description">
                            <h5 class="info-title mb-0">2. Tanggung Jawab Pengguna</h5>
                            <p class="description">
                                <ul>
                                    <li>Pengguna bertanggung jawab atas kebenaran dan keakuratan informasi yang dilaporkan.</li>
                                    <li>Setiap laporan yang dikirim akan melalui proses validasi dan dapat ditindaklanjuti oleh Tim Sarana dan Prasarana (Sarpras) kampus.</li>
                                    <li>Pengguna dilarang mengunggah konten yang bersifat ofensif, diskriminatif, atau melanggar hukum.</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    <div class="terms info-horizontal">
                        <div class="description">
                            <h5 class="info-title mb-0">3. Hak dan Batasan</h5>
                            <p class="description">
                                <ul>
                                    <li>Tim pengembang berhak memperbarui sistem, memperbaiki bug, atau menambahkan fitur baru tanpa pemberitahuan terlebih dahulu.</li>
                                    <li>Pengembang tidak bertanggung jawab atas kerugian akibat penggunaan sistem yang tidak sesuai dengan ketentuan ini.</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    <div class="terms info-horizontal">
                        <p class="mt-2 description text-end"><small>Terakhir diperbarui: {{ now()->format('d F Y') }}</small></p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mr-auto">
                    <div class="card card-signup text-center">
                        <div class="card-header ">
                            <h4 class="card-title">{{ __('Register') }}</h4>
                        </div>
                        <div class="card-body ">
                            <form class="form" method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="input-group{{ $errors->has('nama') ? ' has-danger' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-single-02"></i>
                                        </span>
                                    </div>
                                    <input name="nama" type="text" class="form-control" placeholder="Nama Lengkap" value="{{ old('nama') }}" required autofocus>
                                    @if ($errors->has('nama'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('nama') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-email-85"></i>
                                        </span>
                                    </div>
                                    <input name="email" type="email" class="form-control" placeholder="Email" required value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-key-25"></i>
                                        </span>
                                    </div>
                                    <input name="password" type="password" class="form-control" placeholder="Password" required>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-key-25"></i>
                                        </span>
                                    </div>
                                    <input name="password_confirmation" type="password" class="form-control" placeholder="Konfirmasi Password" required>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-check text-left">
                                    <label class="form-check-label">
                                        <input class="form-check-input" name="agree_terms_and_conditions" type="checkbox">
                                        <span class="form-check-sign"></span>
                                            {{ __('Saya setuju dengan') }}
                                        <a href="#lihatkiriformmas">{{ __('syarat dan ketentuan') }}</a>.
                                    </label>
                                    @if ($errors->has('agree_terms_and_conditions'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('agree_terms_and_conditions') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="card-footer ">
                                    <button type="submit" class="btn btn-info btn-round">{{ __('Get Started') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>
    
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            demo.checkFullPageBackgroundImage();
        });
    </script>
@endpush
