<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamParticipant;
use App\Models\ExamSchedule;
use App\Models\Registrant;
use App\Models\RegistrantGraduation;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ExamParticipantController extends Controller
{
    private $title;
    private $route;

    function __construct()
    {
        $this->title = 'Peserta Ujian';
        $this->route = 'admin.exam.participants';
    }

    public function index(ExamSchedule $exam)
    {
        $data = [
            'title' => 'Peserta',
            'breadcrumbs' => [
                'Ujian',
                $exam->name => route('admin.exam.show', $exam->id),
                'Peserta Ujian',

            ],
            'route' => $this->route,
            'exam' => $exam
        ];

        return view($this->route.'.index', $data);
    }

    public function table(DataTables $datatables, ExamSchedule $exam, ExamParticipant $participant)
    {
        $query = ExamParticipant::select('exam_participants.*')->with('registrant.user')->where('exam_schedule_id', $exam->id);

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', static function ($data) {
                return Daster::tanggal($data->created_at, 2, true);
            })
            ->editColumn('graduate', static function ($data) {
                return $data->graduate;
            })
            ->addColumn('status', static function ($data) {
                if($data->graduate=="Lulus"){
                    $status = '<span class="badge badge-success">Lulus</span>';
                }else if($data->graduate=="Tidak Lulus"){
                    $status = '<span class="badge badge-danger">Tidak lulus</span>';
                }else{
                    $status = '<span class="badge badge-warning">Menunggu Konfirmasi</span>';
                }
                return $status;
            })
            ->addColumn('name', static function ($data) {
                return $data->registrant_graduation->user->name ?? '';
            })
            ->addColumn('email', static function ($data) {
                return $data->registrant_graduation->user->email ?? '';
            })
            ->addColumn('nik', static function ($data) {
                return $data->registrant_graduation->user->nik ?? '';
            })
            ->addColumn('action', function ($data) use($exam) {
                return ' <a href="' . route($this->route . '.edit', [$exam->id, $data->id]) . '" class="btn btn-label-info btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Set Kelulusan" data-boundary="window"><i class="la la-graduation-cap"></i></a>'.
                ' <a href="' . route($this->route . '.destroy', [$exam->id, $data->id]) . '" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    public function create(ExamSchedule $exam)
    {
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'exam' => $exam
        ];

        return view($this->route.'.create', $data);
    }

    public function store(Request $request, ExamSchedule $exam)
    {
        $request->validate([
           'registrant_graduation_id' => 'required',
        ]);

        $registrant_graduation = RegistrantGraduation::where('id',$request->registrant_id)->first();
        $resgistrant = Registrant::where('user_id',$registrant_graduation->user_id)->first();

        $exam->participants()->create([
            'registrant_id' => $resgistrant->id,
            'registrant_graduation_id' => $request->registrant_graduation_id,
            'description' => $request->description
        ]);

        return response()->json(['success' => true, 'message' => 'Berhasil disimpan']);
    }

    public function edit(ExamSchedule $exam, ExamParticipant $participant)
    {
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'exam' => $exam,
            'value' => $participant
        ];

        return view($this->route.'.edit', $data);
    }

    public function update(Request $request, ExamSchedule $exam, ExamParticipant $participant)
    {
        $request->validate([
            'graduate' => 'required',
        ]);

        $participant->update([
            'graduate' => $request->graduate,
        ]);

        return response()->json(['success' => true, 'message' => 'Berhasil disimpan']);
    }

    public function destroy($exam, ExamParticipant $participant)
    {
        $participant->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
