<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Requirement;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function register(Requirement $requirement)
    {
        $data = [
            'title' => 'Pendaftaran',
            'requirement' => $requirement,
            'breadcrumbs' => ['Home', 'Jadwal' => route('web.schedule.index'), 'Pendaftaran']
        ];

        return view('web.registration.register', $data);
    }

    public function store(Request $request, Requirement $requirement)
    {
        $rules = [];
        $message = [];
        $universityRequirements = $requirement->items;
        foreach ($universityRequirements as $item)
        {
            $rule = [];

            if ($item->required && $item->type !== 'Checkbox') {
                $rule[] = 'required';
                $message['requirement_' . $item->id.'.required'] = 'Kolom "'.$item->name.'" harus diisi.';
            } else {
                $rule[] = 'nullable';
            }

            if ($item->type === 'File') {
                $rule[] = 'file';
            }

            $rules['requirement_' . $item->id] = implode('|', $rule);
        }

        $request->validate(
            $rules, $message
        );

        $user = Auth::user();

        $submission = Auth::user()->countSubmission();
        $university = $requirement->university;

        $registrant = $university->registrant()->create([
            'user_id' => $user->id,
            'requirement_id' => $requirement->id,
            'status' => 'Request',
            'submission' => $submission + 1
        ]);

        $items = [];
        foreach ($universityRequirements as $item)
        {
            $field = 'requirement_'.$item->id;

            if (($item->type === 'File') && $request->hasFile($field)) {

                $value = $request->file('requirement_'.$item->id)->store('requirement/'.$university->id.'/'.$user->id, 'local');

            } else if ($item->type === 'Checkbox' && $request->has('requirement_'.$item->id)) {

                $value = $request->input('requirement_'.$item->id) ? true : false;

            } else {

                $value = $request->has('requirement_'.$item->id) ? $request->input('requirement_'.$item->id) : null;

            }

            if ($value) {
                $items[] = [
                    'requirement_item_id' => $item->id,
                    'value' => $value
                ];
            }
        }

        $registrant->requirements()->createMany(
            $items
        );

        return response()->json(['success' => true, 'message' => 'Pendaftaran berhasil.']);
    }
}
