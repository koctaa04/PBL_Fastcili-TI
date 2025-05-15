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
                                <div class="input-group{{ $errors->has('id_level') ? ' has-danger' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-badge"></i>
                                        </span>
                                    </div>
                                    <select name="id_level" class="form-control" required>
                                        <option value="" disabled selected>Pilih Level</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->id_level }}" {{ old('id_level') == $level->id_level ? 'selected' : '' }}>
                                                {{ $level->nama_level }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('id_level'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('id_level') }}</strong>
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
    <!-- Social Icons -->
    <div class="social-icons">
        {{-- Tombol untuk menampilkan Kontak --}}
        <a class="social-btn" target="_blank" href="https://api.whatsapp.com/send/?phone=6285105120605"><i class="fab fa-whatsapp"></i></a>
        <a class="social-btn" target="_blank" href="https://www.notion.so/Daily-Task-Fastcili-TI-Project-1e9218774ea6800fa360d1f6de6b05bd?pvs=4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                <path d="M44.62 13.13c-.23-.21-.52-.33-.83-.33-.02 0-.05.01-.08.01l-29.86 1.92c-.63.04-1.13.58-1.13 1.21v28.75c0 .34.14.65.38.88.25.23.57.35.91.33l29.86-1.93C44.51 43.93 45 43.4 45 42.76V14.02C45 13.68 44.87 13.36 44.62 13.13zM38.11 20.92c-.6.19-.79.2-.79.2v17.24c-1.02.55-1.86.81-2.74.81-1.07 0-1.68-.24-2.5-1.5-1.74-2.69-7.41-11.81-7.41-11.81v11.45l2.23.47c0 0-.06 1.3-2.01 1.45-1.71.13-5.44.32-5.44.32 0-.47.1-1.12.84-1.31.35-.09 1.4-.37 1.4-.37V22.42h-2.24c0-1.03.3-1.83 1.38-1.91l5.79-.33 7.73 11.92V21.49l-2.24-.19c0-.93.9-1.5 1.67-1.58l5.04-.28C38.82 20.09 38.79 20.7 38.11 20.92zM4.98 8.54l5.74 5.74v29.54L5.6 37.66c-.41-.58-.62-1.25-.62-1.96V8.54zM42.72 10.91l-29.06 1.83c-.99.07-1.95-.3-2.65-.99L6.24 6.97l27.19-1.89c.81-.07 1.62.17 2.28.66L42.72 10.91z"></path>
            </svg>
        </a>
        <a class="social-btn" href="https://github.com/koctaa04/PBL_Fastcili-TI"><i class="fab fa-github"></i></a>
    </div>
    
@endsection
