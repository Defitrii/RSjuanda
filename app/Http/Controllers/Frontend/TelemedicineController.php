<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ReviewRatingTelemedicine;
use App\Models\User;
use Illuminate\Http\Request;

class TelemedicineController extends Controller
{
    public function index()
    {
        $user = User::where('type', 3)
            ->join('dokter', 'users.id', '=', 'dokter.user_id')
            ->where('dokter.status', '=', '0')
            ->orderBy('users.created_at', 'asc')
            ->paginate(9, ['users.*']);

        $averageRatings = [];
        foreach ($user as $row) {
            $averageRating = ReviewRatingTelemedicine::where('dokter_id', $row->id)->avg('rating');
            $averageRatings[$row->id] = round($averageRating, 1);
        }

        $reviewRatings = ReviewRatingTelemedicine::with('user')
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();

        return view('frontend.telemedicine.index', compact('user', 'averageRatings', 'reviewRatings'));
    }

    public function detail($id)
    {
        $user = User::with('dokter')->find($id);
        $averageRatings = ReviewRatingTelemedicine::where('dokter_id', $user->id)->avg('rating');
        return view('frontend.telemedicine.detail', compact('user', 'averageRatings'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Cek apakah pengguna telah memberikan rating sebelumnya
        $existingRating = ReviewRatingTelemedicine::where('user_id', $user->id)
            ->where('dokter_id', $request->dokter_id)
            ->first();

        if ($existingRating) {
            // Pengguna telah memberikan rating sebelumnya
            return redirect()->back()->with('error', 'Anda telah memberikan rating sebelumnya.');
        }

        // Simpan rating baru ke dalam database
        $ratingTelemedicine = new ReviewRatingTelemedicine();
        $ratingTelemedicine->dokter_id = $request->dokter_id;
        $ratingTelemedicine->user_id = $user->id;
        $ratingTelemedicine->rating = $request->rating;
        $ratingTelemedicine->komen = $request->komen;
        $ratingTelemedicine->save();

        return redirect()->back()->with('success', 'Rating telah berhasil ditambahkan.');
    }
}
