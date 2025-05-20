<?php

namespace App\Http\Controllers;

use App\Mail\VisitNotification;
use App\Models\Visitor;
use App\Models\VisitorHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard(Request $request)
    {
        $filter = $request->get('filter', 'month');

        $query = VisitorHistory::with('visitor');

        if ($filter == 'week') {
            $query->where('check_in', '>=', now()->startOfWeek());
        } else {
            $query->where('check_in', '>=', now()->startOfMonth());
        }

        $recentHistory = $query->latest()->take(10)->get();

        // For line chart
        $visitsByDate = DB::table('visitor_histories')
            ->select(DB::raw('DATE(check_in) as visit_date'), 'purpose', DB::raw('COUNT(*) as count'))
            ->where('check_in', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(check_in)'), 'purpose')
            ->orderBy(DB::raw('DATE(check_in)'), 'asc')
            ->limit(10)
            ->get();

        $labels = $visitsByDate->pluck('date');
        $data = $visitsByDate->pluck('count');

        // For pie chart (Purpose & Status)
        $purposeCounts = $query->select('purpose', DB::raw('count(*) as count'))
            ->groupBy('purpose')
            ->pluck('count', 'purpose');

        $statusCounts = $query->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.dashboard', [
            'totalVisitors' => Visitor::count(),
            'totalCheckins' => Visitor::count(),
            'totalVisits' => Visitor::count(),
            'recentHistory' => $recentHistory,
            'labels' => $labels,
            'data' => $data,
            'filter' => $filter,
            'purposeCounts' => $purposeCounts,
            'statusCounts' => $statusCounts,
        ]);
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
        $request->validate([
            'name' => 'required',
            'identification_number' => 'required',
            'identification_type' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'purpose' => 'required',
        ]);

        $existingVisitor = Visitor::where('identification_number', $request->identification_number)->first();
        if ($existingVisitor) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => 'Visitor Already exists!'
            ]);
        }

        $lastVisitor = Visitor::latest()->first();
        $visitor_number = 'VST' . now()->format('Ymd') . str_pad(($lastVisitor?->id ?? 0) + 1, 3, '0', STR_PAD_LEFT);

        $visitor = new Visitor();
        $visitor->identification_number = $request->identification_number;
        $visitor->identification_type = $request->identification_type;
        $visitor->user_id = Auth::id();
        $visitor->visitor_number = $visitor_number;
        $visitor->phone = $request->phone;
        $visitor->name = $request->name;
        $visitor->address = $request->address;
        $visitor->email = $request->email;

        // Generate QR code (SVG) with your style
        $qrDir = public_path('qr_codes');
        $qrPath = $qrDir . '/' . $visitor_number . '.svg';

        if (!file_exists($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        if (!file_exists($qrPath)) {
            $qrImage = QrCode::format('svg')
                ->size(200)
                ->generate(route('visitor.checkin', $visitor_number));
            file_put_contents($qrPath, $qrImage);
        }

        $qrUrl = asset('qr_codes/' . $visitor_number . '.svg');

        $visitor->qr_code = $qrUrl;
        $visitor->save();

        // Save visit history
        $visitorHistory = new VisitorHistory();
        $visitorHistory->visitor_id = $visitor->id;
        $visitorHistory->purpose = $request->purpose;
        $visitorHistory->check_in = now();
        $visitorHistory->status = 'Pending';
        $visitorHistory->save();

        // Send email
        Mail::to($visitor->email)->send(new VisitNotification($visitor));

        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'Visitor Checked In Successfully! A confirmation email has been sent.'
        ]);
    }
    public function generateQR($id)
    {
        $visitor = Visitor::findOrFail($id);

        $visitor_number = $visitor->visitor_number;
        $qrDir = public_path('qr_codes');
        $qrPath = $qrDir . '/' . $visitor_number . '.svg';

        if (!file_exists($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        if (!file_exists($qrPath)) {
            $qrImage = QrCode::format('svg')->size(200)->generate(route('visitor.checkin', $visitor_number));
            file_put_contents($qrPath, $qrImage);
            $visitor->qr_code = asset('qr_codes/' . $visitor_number . '.svg');
            $visitor->save();
        }

        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'QR Code generated successfully!'
        ]);
    }
    public function checkInQr($id)
    {
        $visitor = Visitor::findOrFail($id);

        // Auto-log a visit
        VisitorHistory::create([
            'visitor_id' => $visitor->id,
            'purpose' => 'Auto check-in via QR code',
            'check_in' => now(),
            'status' => 'Pending'
        ]);

        // Optionally show a success screen
        return view('admin.visitors.checkin-success', compact('visitor'));
    }
    public function destroyVisitor($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->delete();
        return redirect()->back()->with('success', 'Visitor deleted successfully.');
    }
}
