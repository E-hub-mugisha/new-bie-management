<?php

namespace App\Http\Controllers;

use App\Mail\VisitNotification;
use App\Models\User;
use App\Models\Visitor;
use App\Models\VisitorHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        // Get total visitors, check-ins, and visits
        $totalVisitors = Visitor::count();
        $totalCheckins = VisitorHistory::whereNotNull('check_in')->count();
        $totalVisits = VisitorHistory::count();

        // Get recent visitor history (You can adjust this query to limit data)
        $recentHistory = VisitorHistory::with('visitor')->latest()->take(5)->get();

        return view('visitors.dashboard', compact('totalVisitors', 'totalCheckins', 'totalVisits', 'recentHistory'));
    }
    public function visitors()
    {
        $visitors = Visitor::all();
        return view('visitors.index', compact('visitors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        $visitor_number = 'VST' . now()->format('Ymd') . str_pad(($lastVisitor?->id ?? 0) + 1, 3, '0', STR_PAD_LEFT);

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

        // Generate the QR code with a link to the check-in page (you can add the visitor number or ID to the URL)
        $qrCode = QrCode::size(200)->generate(route('visitor.checkin', $visitor->visitor_number));

        // Save the QR code as an image (optional)
        $qrCodePath = public_path('qr_codes/' . $visitor->visitor_number . '.png');
        file_put_contents($qrCodePath, $qrCode);

        // Send Email Notification
        Mail::to($visitor->email)->send(new VisitNotification($visitor));

        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'Visitor Checked In Successfully! A confirmation email has been sent.'
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Visitor $visitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visitor $visitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function newStore(Request $request, $id)
    {
        // Find the existing visitor by ID
        $existingVisitor = Visitor::findOrFail($id);

        // Create a new visitor history record
        $visitorHistory = new VisitorHistory();
        $visitorHistory->visitor_id = $existingVisitor->id;
        $visitorHistory->purpose = $request->purpose;
        $visitorHistory->check_in = now();
        $visitorHistory->status = 'Pending';
        $visitorHistory->save();

        // Generate the QR code with a link to the check-in page, using the visitor's unique number
        $qrCode = QrCode::size(200)->generate(route('visitor.checkin', $existingVisitor->visitor_number));

        // Generate the path for the QR code image
        $qrCodePath = public_path('qr_codes/' . $existingVisitor->visitor_number . '.png');

        // Ensure the directory exists before saving the image
        if (!file_exists(public_path('qr_codes'))) {
            mkdir(public_path('qr_codes'), 0755, true); // Create the directory if it doesn't exist
        }

        // Save the QR code image
        file_put_contents($qrCodePath, $qrCode);

        // Redirect back with a success message
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'Visitor Checked In Successfully and QR Code Generated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visitor $visitor)
    {
        //
    }
    public function checkout($id)
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
    public function showProfile($id)
    {
        $visitor = Visitor::findOrFail($id); // Get visitor by ID
        $history = VisitorHistory::where('visitor_id', $id)->get(); // Assuming a history model exists

        return view('visitors.profile', compact('visitor', 'history'));
    }
    public function history()
    {
        $histories = VisitorHistory::all();
        return view('visitors.history', compact('histories'));
    }
    public function VisitorHistory($id)
    {
        $visitor = Visitor::findOrFail($id);
        $histories = VisitorHistory::where('visitor_id', $id)->get();
        return view('visitors.show-history', compact('histories', 'visitor'));
    }
    public function checkIn($visitor_number)
    {
        // Find the visitor based on the QR code (visitor_number)
        $visitor = Visitor::where('visitor_number', $visitor_number)->first();

        if (!$visitor) {
            return redirect()->route('home')->with([
                'alert_type' => 'error',
                'alert_message' => 'Visitor not found!'
            ]);
        }

        // Find the visitor's history and update the status to "Checked In"
        $visitorHistory = VisitorHistory::where('visitor_id', $visitor->id)->first();
        $visitorHistory->status = 'Checked In';
        $visitorHistory->save();

        return view('visitor.checkin_success', compact('visitor'));
    }

    public function showVisitorPage()
    {
        $visitor_number = 'V123456'; // Example — ideally fetched from DB or session
        return view('welcome', compact('visitor_number'));
    }
}
