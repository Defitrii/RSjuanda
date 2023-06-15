<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Regency;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Village;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'no_telepon' => ['required', 'min:11', 'max:15'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'provinsi' => ['required', 'string', 'max:255'],
            'kabupaten' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'desa' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'Silakan isi nama terlebih dahulu',
            'email.required' => 'Silakan isi email terlebih dahulu',
            'no_telepon.required' => 'Silakan isi no telepon terlebih dahulu',
            'no_telepon.min' => 'No telepon minimal 11 karakter',
            'no_telepon.max' => 'No telepon maksimal 11 karakter',
            'password.required' => 'Silakan isi password terlebih dahulu',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Kofirmasi password gagal',
            'provinsi.required' => 'Silakan pilih provinsi terlebih dahulu',
            'kabupaten.required' => 'Silakan pilih kabupaten terlebih dahulu',
            'kecamatan.required' => 'Silakan pilih kecamatan terlebih dahulu',
            'desa.required' => 'Silakan pilih desa terlebih dahulu',
            'gender.required' => 'Silakan pilih jenis kelamin terlebih dahulu',
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'no_telepon' => $data['no_telepon'],
            'type' => 0,
            'gender' => $data['gender'],
            'password' => Hash::make($data['password']),
            'provinsi_id' => $data['provinsi'],
            'kabupaten_id' => $data['kabupaten'],
            'kecamatan_id' => $data['kecamatan'],
            'desa_id' => $data['desa'],
        ]);
    }

    public function getKabupaten(Request $request)
    {
        $id_provinsi = $request->id_provinsi;
        $kabupaten = Regency::where('province_id', $id_provinsi)->get();
        foreach ($kabupaten as $row) {
            echo "<option value='" . $row->id . "'>" . $row->name . "</option>";
        }
    }

    public function getKecamatan(Request $request)
    {
        $id_kabupaten = $request->id_kabupaten;
        $kecamatan = District::where('regency_id', $id_kabupaten)->get();
        foreach ($kecamatan as $row) {
            echo "<option value='" . $row->id . "'>" . $row->name . "</option>";
        }
    }

    public function getDesa(Request $request)
    {
        $id_kecamatan = $request->id_kecamatan;
        $desa = Village::where('district_id', $id_kecamatan)->get();
        foreach ($desa as $row) {
            echo "<option value='" . $row->id . "'>" . $row->name . "</option>";
        }
    }
}
