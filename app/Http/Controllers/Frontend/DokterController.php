<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index()
    {
        $user = User::where('type', 3)
            ->join('dokter', 'users.id', '=', 'dokter.user_id')
            ->where('dokter.status', '=', '0')
            ->orderBy('users.created_at', 'asc')
            ->paginate(9, ['users.*']);

        return view('frontend.dokter', compact('user'));
    }

    public function detail($id)
    {
        $user = User::with('dokter')->find($id);
        return view('frontend.dokter_detail', compact('user'));
    }
}
