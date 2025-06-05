<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $levels = Level::where('id_level', '>', 1)->get();
        return view('auth.register', compact('levels'));
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    // protected function validator(array $data)
    // {
    //     return Validator::make($data, [
    //         'nama' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //         'id_level' => ['required', 'exists:level,id_level'],
    //         'agree_terms_and_conditions' => ['required'],
    //     ]);
    // }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'id_level' => ['required', 'exists:level,id_level'],
            'agree_terms_and_conditions' => ['required', 'accepted'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Simpan user tanpa login
        $user = $this->create($request->all());

        // Simpan user tanpa login
        return response()->json([
            'message' => 'Akun berhasil dibuat! Silakan login setelah disetujui.'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Validasi tambahan untuk memastikan id_level > 1
        if ($data['id_level'] <= 1) {
            abort(403, 'Level tidak valid');
        }

        return User::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'id_level' => $data['id_level'],
            'password' => Hash::make($data['password']),
            'akses' => 0,
        ]);
    }
}
