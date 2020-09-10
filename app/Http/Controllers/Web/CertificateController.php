<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\RequirementCertificate;
use App\Models\RegistrantCertificate;
use App\Models\Registrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = [
            'registered' => $user->registrants_certificate()->where('status', 'Approve')->first(),
            'progress' => $user->registrants_certificate()->where('status', 'Request')->first(),
            'rejected' => $user->registrants_certificate()->where('status', 'Reject')->get(),
            'certificates' => $user->registrants_certificate()->with('requirement_certificate')->get(),
            'requirement_baru' => RequirementCertificate::where(['status' => 'Active', 'type' => 'Baru'])->first(),
            'requirement_ulang' => RequirementCertificate::where(['status' => 'Active', 'type' => 'Ulang'])->first(),
        ];

        return view('web.certificate.index', $data);
    }

    public function register(Request $request)
    {
        $user = Auth::user();

        if($request->certificate_type == 1){
            $type = "Baru";
        }elseif($request->certificate_type == 2){
            $type = "Ulang";
        }

        $check = RegistrantCertificate::where('user_id',$user->id)->orderBy('created_at', 'desc')->first();

        if($request->certificate_type == 1 && !empty($user->no_sertifikat)){
            return redirect()->back()->withErrors('Anda sudah pernah melakukan pengajuan sertifikat, silahkan melakukan pengajuan ulang!');
        }

        if($check->status == "Request"){
            return redirect()->back()->withErrors('Anda memiliki pengajuan masih dalam proses, Anda belum bisa melakukan pengajuan sampai proses selesai');
        }

        $requirement = RequirementCertificate::where(['status' => 'Active', 'type' => $type])->first();

        $data = [
            'requirement' => $requirement,
        ];

        return view('web.certificate.register', $data);
    }

    public function store(Request $request, RequirementCertificate $requirement)
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

        $registrant = RegistrantCertificate::create([
            'user_id' => $user->id,
            'requirement_certificate_id' => $requirement->id,
            'status' => 'Request'
        ]);

        $items = [];
        foreach ($requirements as $item)
        {
            $field = 'requirement_'.$item->id;

            if (($item->type === 'File') && $request->hasFile($field)) {

                $value = $request->file('requirement_'.$item->id)->store('requirement/certificate/'.$user->id, 'local');

            } else if ($item->type === 'Checkbox' && $request->has('requirement_'.$item->id)) {

                $value = $request->input('requirement_'.$item->id) ? true : false;

            } else {

                $value = $request->has('requirement_'.$item->id) ? $request->input('requirement_'.$item->id) : null;

            }

            if ($value) {
                $items[] = [
                    'requirement_certificate_item_id' => $item->id,
                    'value' => $value
                ];
            }
        }

        $registrant->requirements_certificate()->createMany(
            $items
        );

        return response()->json(['success' => true, 'message' => 'Pengajuan Sertifikat Berhasil.']);
    }
}
