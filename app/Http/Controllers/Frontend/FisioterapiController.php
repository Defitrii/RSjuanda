<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Fisioterapi;
use App\Models\Perawat;
use App\Models\ReviewRatingFisioterapi;
use App\Models\User;
use Illuminate\Http\Request;

class FisioterapiController extends Controller
{
    public function index()
    {
        $fisioterapi = Fisioterapi::orderBy('created_at', 'asc')->take(9)->get();
        $perawat = Perawat::with('user')->where('status', '=', '0')->orderBy('created_at', 'asc')->paginate(9);

        $averageRatings = [];
        foreach ($perawat as $row) {
            $averageRating = ReviewRatingFisioterapi::where('perawat_id', $row->id)->avg('rating');
            $averageRatings[$row->id] = round($averageRating, 1);
        }

        $reviewRatings = ReviewRatingFisioterapi::with('user')
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();
        return view('frontend.fisioterapi.index', compact('fisioterapi', 'perawat', 'averageRatings', 'reviewRatings'));
    }

    public function detail($id)
    {
        $perawat = Perawat::with('user')->find($id);
        $averageRatings = ReviewRatingFisioterapi::where('perawat_id', $perawat->id)->avg('rating');
        return view('frontend.fisioterapi.detail', compact('perawat', 'averageRatings'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Cek apakah pengguna telah memberikan rating sebelumnya
        $existingRating = ReviewRatingFisioterapi::where('user_id', $user->id)
            ->where('perawat_id', $request->perawat_id)
            ->first();

        if ($existingRating) {
            // Pengguna telah memberikan rating sebelumnya
            return redirect()->back()->with('error', 'Anda telah memberikan rating sebelumnya.');
        }

        // Simpan rating baru ke dalam database
        $ratingFisioterapi = new ReviewRatingFisioterapi();
        $ratingFisioterapi->perawat_id = $request->perawat_id;
        $ratingFisioterapi->user_id = $user->id;
        $ratingFisioterapi->rating = $request->rating;
        $ratingFisioterapi->komen = $request->komen;
        $ratingFisioterapi->save();

        return redirect()->back()->with('success', 'Rating telah berhasil ditambahkan.');
    }
}
