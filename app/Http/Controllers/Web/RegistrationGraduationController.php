<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RequirementGraduation;
use App\Models\RegistrantGraduation;
use App\Models\Registrant;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationGraduationController extends Controller
{
    public function register(RequirementGraduation $requirement)
    {
        $data = [
            'title' => 'Pendaftaran Ujian Nasional',
            'requirement' => $requirement,
            'breadcrumbs' => ['Home', 'Jadwal' => route('web.registration-graduation.index'), 'Pendaftaran Kelulusan']
        ];

        return view('web.registration-graduation.register', $data);
    }

    public function store(Request $request, RequirementGraduation $requirement)
    {
        $rules = [];
        $message = [];
        $requirements = $requirement->items;
        foreach ($requirements as $item)
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

        $university = Registrant::where('user_id',$user->id)->first();

        $registrant = RegistrantGraduation::create([
            'user_id' => $user->id,
            'university_id' => $university->university_id,
            'requirement_graduation_id' => $requirement->id,
            'status' => 'Request'
        ]);

        $items = [];
        foreach ($requirements as $item)
        {
            $field = 'requirement_'.$item->id;

            if (($item->type === 'File') && $request->hasFile($field)) {

                $value = $request->file('requirement_'.$item->id)->store('requirement/'.$university->university_id.'/'.$user->id, 'local');

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
