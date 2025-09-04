<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function markSeen($id)
    {
        $recommendation = Recommendation::where('user_id', Auth::id())->findOrFail($id);
        $recommendation->update(['status' => 'seen']);
        
        return back()->with('success', 'Recommendation marked as seen');
    }

    public function markDone($id)
    {
        $recommendation = Recommendation::where('user_id', Auth::id())->findOrFail($id);
        $recommendation->update(['status' => 'done']);
        
        return back()->with('success', 'Recommendation marked as done');
    }

    public function dismiss($id)
    {
        $recommendation = Recommendation::where('user_id', Auth::id())->findOrFail($id);
        $recommendation->delete();
        
        return back()->with('success', 'Recommendation dismissed');
    }

    public function dismissAll()
    {
        Recommendation::where('user_id', Auth::id())->delete();
        
        return back()->with('success', 'All recommendations dismissed');
    }
}
