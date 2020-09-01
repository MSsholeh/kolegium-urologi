<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $data = [
            'user' => Auth::user(),
        ];

        return view('web.profile.index', $data);
    }

    public function update(Request $request)
    {
        $rules = [];
        $message = [];
        $request->validate([
            'nik' => ['required','string','min:16','max:16',Rule::unique('users')->ignore($request->id)],
            'npa' => ['required','string','max:16',Rule::unique('users')->ignore($request->id)],
            'name' => 'required|string|max:255',
            'university' => 'required|string|max:100',
            'pob' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        $user = User::find($request->id);

        $user->nik = $request->nik;
        $user->npa = $request->npa;
        $user->name = $request->name;
        $user->university = $request->university;
        $user->pob = $request->pob;
        $user->dob = $request->dob;
        $user->address = $request->address;
        $user->phone = $request->phone;

        $user->save();

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }
}
