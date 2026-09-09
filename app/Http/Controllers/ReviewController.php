<?php

namespace App\Http\Controllers;

use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['vendor', 'project'])->orderBy('created_at', 'desc')->paginate(10);

        return view('reviews.index', compact('reviews'));
    }
}
