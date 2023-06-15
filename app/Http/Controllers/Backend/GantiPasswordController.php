<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GantiPasswordController extends Controller
{
    public function index()
    {
        return view('backend.gantiPassword.index');
    }

    public function update(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'old_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ],
            [
                'old_password.required' => 'Silakan isi password lama terlebih dahulu!',
                'password.required' => 'Silakan isi password baru terlebih dahulu!',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if (!Hash::check($request->old_password, auth()->user()->password)) {
                return response()->json(['error_password' => 'Kata sandi lama tidak cocok!']);
            } else {
                User::whereId(auth()->user()->id)->update([
                    'password' => Hash::make($request->password)
                ]);
                return response()->json(['success' => 'Kata sandi berhasil diubah']);
            }
        }
    }
}
