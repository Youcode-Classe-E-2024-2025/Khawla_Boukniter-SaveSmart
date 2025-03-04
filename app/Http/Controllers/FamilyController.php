<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Family;
use App\Models\User;

class FamilyController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $family_members = User::where('family_id', $user->family_id)->get();

        return view('family.index', [
            'familyMembers' => $family_members
        ]);
    }

    public function create()
    {
        return view('family.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'family_name' => 'required|string',
        ]);

        $family = Family::create([
            'name' => $request->family_name,
            'owner_id' => Auth::id(),
            'invitation_code' => Family::generateCode(),
        ]);

        // Auth::update(['family_id' => $family->id]);

        $user = Auth::user();
        $user->family_id = $family->id;
        $user->save();

        return redirect()->route('family.index')->with([
            'success' => 'family created successfully',
            'invitation_code' => $family->invitation_code
        ]);
    }
}
