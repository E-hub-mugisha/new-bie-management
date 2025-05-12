<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\alert;

class ReceptionController extends Controller
{
    //
    public function index()
    {
        $receptions = User::where('role', 'reception')->get();
        return view('admin.receptions.index', compact('receptions'));
    }
    
    public function addReceptionist(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'Receptionist created successful!'
        ]);
    }
    public function deleteReception($id)
    {
        $reception = User::findOrFail($id);
        $reception->delete();
        return redirect()->back()->with([
            alert('success', 'Reception deleted successful!')
        ]);
    }
}
