<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RequirementGraduation;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationGraduationController extends Controller
{
    public function register(RequirementGraduation $requirement)
    {
        $data = [
            'title' => 'Pendaftaran Kelulusan',
            'requirement' => $requirement,
            'breadcrumbs' => ['Home', 'Jadwal' => route('web.registration-graduation.index'), 'Pendaftaran Kelulusan']
        ];

        return view('web.registration-graduation.register', $data);
    }

    public function store(Request $request, RequirementGraduation $requirement)
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

        $university = $requirement->university;

        $registrant = $university->registrant_graduation()->create([
            'user_id' => $user->id,
            'requirement_graduation_id' => $requirement->id,
            'status' => 'Request'
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
                    'requirement_graduation_item_id' => $item->id,
                    'value' => $value
                ];
            }
        }

        $registrant->requirements_graduation()->createMany(
            $items
        );

        return response()->json(['success' => true, 'message' => 'Pendaftaran Kelulusan berhasil.']);
    }
}
