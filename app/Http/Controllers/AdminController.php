<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\VisitorHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        // Get total visitors, check-ins, and visits
        $totalVisitors = Visitor::count();
        $totalCheckins = VisitorHistory::whereNotNull('check_in')->count();
        $totalVisits = VisitorHistory::count();

        // Get recent visitor history (You can adjust this query to limit data)
        $recentHistory = VisitorHistory::with('visitor')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalVisitors', 'totalCheckins', 'totalVisits', 'recentHistory'));
    }
    public function visitors()
    {
        $visitors = Visitor::all();
        return view('admin.visitors.index', compact('visitors'));
    }
    public function showVisitorProfile($id)
    {
        $visitor = Visitor::findOrFail($id); // Get visitor by ID
        $history = VisitorHistory::where('visitor_id', $id)->get(); // Assuming a history model exists

        return view('admin.visitors.profile', compact('visitor', 'history'));
    }
    public function visitorsHistory()
    {
        $histories = VisitorHistory::all();
        return view('admin.visitors.history', compact('histories'));
    }
    public function AdminVisitorHistory($id)
    {
        $visitor = Visitor::findOrFail($id);
        $histories = VisitorHistory::where('visitor_id', $id)->get();
        return view('admin.visitors.show-history', compact('histories', 'visitor'));
    }
    public function checkoutAdmin($id)
    {
        $visitorHistory = VisitorHistory::findOrFail($id);
        $visitorHistory->check_out = now();
        $visitorHistory->status = 'Completed';
        $visitorHistory->update();

        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'Visitor Checked Out Successfully!'
        ]);
    }
    public function newVisitStore(Request $request, $id)
    {
        $existingVisitor = Visitor::findOrFail($id);

        $visitorHistory = new VisitorHistory();
        $visitorHistory->visitor_id = $existingVisitor->id;
        $visitorHistory->purpose = $request->purpose;
        $visitorHistory->check_in = now();
        $visitorHistory->status = 'Pending';
        $visitorHistory->save();

        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'Visitor Checked In Successfully!'
        ]);
    }

    public function storeAdmin(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required',
            'identification_number' => 'required',
            'identification_type' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'purpose' => 'required',
        ]);

        // Check if a visitor already exists with the given identification number
        $existingVisitor = Visitor::where('identification_number', $request->identification_number)->first();

        if ($existingVisitor) {
            // If the visitor already exists, return an error message
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => 'Visitor Already exists!'
            ]);
        }

        // Generate a unique visitor number (e.g., prefix + timestamp or incrementing ID)
        $visitor_number = 'VST' . now()->format('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        $visitor = new Visitor();
        $visitor->identification_number = $request->identification_number;
        $visitor->identification_type = $request->identification_type;
        $visitor->user_id = Auth::user()->id;
        $visitor->visitor_number = $visitor_number;
        $visitor->phone = $request->phone;
        $visitor->name = $request->name;
        $visitor->address = $request->address;
        $visitor->email = $request->email;

        $visitor->save();

        $visitorHistory = new VisitorHistory();
        $visitorHistory->visitor_id = $visitor->id;
        $visitorHistory->purpose = $request->purpose;
        $visitorHistory->check_in = now();
        $visitorHistory->status = 'Pending';
        $visitorHistory->save();

        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'Visitor Checked In Successfully!'
        ]);
    }
}
