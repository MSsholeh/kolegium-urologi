<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamParticipant;
use App\Models\ExamSchedule;
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
//            ->editColumn('status', static function($data) {
//                return '<span class="kt-badge kt-badge--inline kt-badge--'.config('constant.validation_status.badge.'.$data->status).'">'.$data->status.'</span>';
//            })
            ->addColumn('name', static function ($data) {
                return $data->registrant->user->name ?? '';
            })
            ->addColumn('email', static function ($data) {
                return $data->registrant->user->email ?? '';
            })
            ->addColumn('nik', static function ($data) {
                return $data->registrant->user->nik ?? '';
            })
            ->addColumn('action', function ($data) use($exam) {
                return ' <a href="' . route($this->route . '.edit', [$exam->id, $data->id]) . '" class="btn btn-label-info btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Set Kelulusan" data-boundary="window"><i class="la la-graduation-cap"></i></a>'.
                ' <a href="' . route($this->route . '.destroy', [$exam->id, $data->id]) . '" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['action'])
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
           'registrant_id' => 'required',
        ]);

        $exam->participants()->create([
            'registrant_id' => $request->registrant_id,
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
