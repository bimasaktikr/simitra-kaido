<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionQr;
use App\Models\Review;
use Illuminate\Http\Request;

class PublicTransactionController extends Controller
{
    public function show(string $uuid)
    {
        $qr = TransactionQr::with(['transaction.mitra', 'transaction.survey.masterSurvey'])
            ->where('uuid', $uuid)
            ->first();

        if (! $qr || ! $qr->transaction) {
            abort(404);
        }

        $tx     = $qr->transaction;
        $mitra  = $tx->mitra;
        $survey = $tx->survey;

        // Get rating dari nilai1s
        $nilai1 = $tx->nilai;
        $baseRatings = [];
        if ($nilai1) {
            $baseRatings = [$nilai1->aspek1, $nilai1->aspek2, $nilai1->aspek3];
        }
        
        // Get rating dari review
        $reviewRatings = Review::where('transaction_id', $tx->id)->pluck('rating')->toArray();
        
        // Gabungkan dan hitung rerata
        $allRatings = array_merge($baseRatings, $reviewRatings);
        $avgRating = count($allRatings) > 0 ? array_sum($allRatings) / count($allRatings) : 0;

        $fullStars = floor($avgRating);
        $halfStar = ($avgRating - $fullStars) >= 0.5 ? 1 : 0;
        $emptyStars = 5 - $fullStars - $halfStar;

        // no masking: pass raw mitra data to view

        $qrPath = $tx->qr?->qr_path ? asset('storage/' . $tx->qr->qr_path) : null;

        return view('mitra.check-transaction.transaction', [
            'transaction'   => $tx,
            'mitra'         => $mitra,
            'survey'        => $survey,
            'qrUrl'         => $qrPath,
            'avgRating'     => $avgRating,
            'fullStars'     => $fullStars,
            'halfStar'      => $halfStar,
            'emptyStars'    => $emptyStars,
        ]);
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create($request->only(['transaction_id', 'email', 'rating', 'comment']));

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }
}
