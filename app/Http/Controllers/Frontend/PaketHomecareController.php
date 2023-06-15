<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Homecare;
use App\Models\ReviewRatingPaketHomecare;
use App\Models\User;
use Illuminate\Http\Request;

class PaketHomecareController extends Controller
{
    public function index()
    {
        $user = User::where('type', 3)
            ->join('dokter', 'users.id', '=', 'dokter.user_id')
            ->where('dokter.status', '=', '0')
            ->orderBy('users.created_at', 'asc')
            ->paginate(9, ['users.*']);
        $paketHomecare = Homecare::orderBy('created_at', 'asc')->take(8)->get();

        $averageRatings = [];
        foreach ($user as $row) {
            $averageRating = ReviewRatingPaketHomecare::where('dokter_id', $row->id)->avg('rating');
            $averageRatings[$row->id] = round($averageRating, 1);
        }

        $reviewRatings = ReviewRatingPaketHomecare::with('user')
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();

        return view('frontend.paketHomecare.index', compact('user', 'paketHomecare', 'averageRatings', 'reviewRatings'));
    }

    public function detail($id)
    {
        $user = User::with('dokter')->find($id);
        $averageRatings = ReviewRatingPaketHomecare::where('dokter_id', $user->id)->avg('rating');
        return view('frontend.paketHomecare.detail', compact('user', 'averageRatings'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Cek apakah pengguna telah memberikan rating sebelumnya
        $existingRating = ReviewRatingPaketHomecare::where('user_id', $user->id)
            ->where('dokter_id', $request->dokter_id)
            ->first();

        if ($existingRating) {
            // Pengguna telah memberikan rating sebelumnya
            return redirect()->back()->with('error', 'Anda telah memberikan rating sebelumnya.');
        }

        // Simpan rating baru ke dalam database
        $ratingPaketHomecare = new ReviewRatingPaketHomecare();
        $ratingPaketHomecare->dokter_id = $request->dokter_id;
        $ratingPaketHomecare->user_id = $user->id;
        $ratingPaketHomecare->rating = $request->rating;
        $ratingPaketHomecare->komen = $request->komen;
        $ratingPaketHomecare->save();

        return redirect()->back()->with('success', 'Rating telah berhasil ditambahkan.');
    }
}
