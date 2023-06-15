<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Perawat;
use App\Models\ReviewRatingHomecare;
use Illuminate\Http\Request;

class HomecareController extends Controller
{
    public function index()
    {
        $homecare = Layanan::orderBy('created_at', 'asc')->take(8)->get();
        $perawat = Perawat::with('user')->where('status', '=', '0')->orderBy('created_at', 'asc')->paginate(9);
        $averageRatings = [];
        foreach ($perawat as $row) {
            $averageRating = ReviewRatingHomecare::where('perawat_id', $row->id)->avg('rating');
            $averageRatings[$row->id] = round($averageRating, 1);
        }

        $reviewRatings = ReviewRatingHomecare::with('user')
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();
        return view('frontend.homecare.index', compact('homecare', 'perawat', 'averageRatings', 'reviewRatings'));
    }

    public function detail($id)
    {
        $perawat = Perawat::with('user')->find($id);
        $averageRatings = ReviewRatingHomecare::where('perawat_id', $perawat->id)->avg('rating');
        return view('frontend.homecare.detail', compact('perawat', 'averageRatings'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Cek apakah pengguna telah memberikan rating sebelumnya
        $existingRating = ReviewRatingHomecare::where('user_id', $user->id)
            ->where('perawat_id', $request->perawat_id)
            ->first();

        if ($existingRating) {
            // Pengguna telah memberikan rating sebelumnya
            return redirect()->back()->with('error', 'Anda telah memberikan rating sebelumnya.');
        }

        // Simpan rating baru ke dalam database
        $ratingHomecare = new ReviewRatingHomecare();
        $ratingHomecare->perawat_id = $request->perawat_id;
        $ratingHomecare->user_id = $user->id;
        $ratingHomecare->rating = $request->rating;
        $ratingHomecare->komen = $request->komen;
        $ratingHomecare->save();

        return redirect()->back()->with('success', 'Rating telah berhasil ditambahkan.');
    }
}
