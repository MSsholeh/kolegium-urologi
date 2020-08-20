<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamParticipant;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use function foo\func;

class ParticipantController extends Controller
{
    private $title;
    private $route;

    function __construct()
    {
        $this->title = 'Peserta Ujian';
        $this->route = 'admin.exam.schedule.participant';
    }

    public function index(Exam $exam, ExamSchedule $schedule)
    {
        $data = [
            'title' => 'Peserta',
            'breadcrumbs' => [
                'Ujian',
                $schedule->exam->name => route('admin.exam.show', $exam->id),
                'Peserta Ujian - '.Daster::tanggal($schedule->event_date),

            ],
            'route' => $this->route,
            'exam' => $exam,
            'schedule' => $schedule,
        ];

        return view($this->route.'.index', $data);
    }

    public function table(DataTables $datatables, Exam $exam, ExamSchedule $schedule)
    {
        $query = ExamParticipant::select('*')->with('user')->where('exam_schedule_id', $schedule->id);

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', static function ($data) {
                return Daster::tanggal($data->created_at, 2, true);
            })
            ->editColumn('graduate', static function ($data) {
                $graduate = $data->getGraduateStatus();
                return $graduate === 'Lulus' ? $graduate.' <a href="'.route('web.file', ['path' => $data->certified]).'" target="_blank" class="btn btn-twitter btn-sm btn-elevate btn-circle btn-icon"><i class="la la-certificate"></i></a>' : $graduate;
            })
            ->editColumn('status', static function($data) {
                return '<span class="kt-badge kt-badge--inline kt-badge--'.config('constant.validation_status.badge.'.$data->status).'">'.$data->status.'</span>';
            })
            ->addColumn('name', static function ($data) {
                return $data->user->name ?? '';
            })
            ->addColumn('action', function ($data) use($exam) {
                $graduate = $data->status === 'Diterima' ? ' <a href="'.route($this->route.'.graduate', [$exam->id, $data->exam_schedule_id, $data->id]).'" class="btn btn-label-success btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Kelulusan" data-boundary="window"><i class="la la-graduation-cap"></i></a>' : '';
                $validation = ' <a href="'.route($this->route.'.validation', [$exam->id, $data->exam_schedule_id, $data->id]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Validasi Persyaratan" data-boundary="window"><i class="la la-check-circle-o"></i></a>';
                return $graduate.$validation;
            })
            ->rawColumns(['graduate', 'status', 'action'])
            ->make(true);
    }

    public function validation(Exam $exam, ExamSchedule $schedule, ExamParticipant $participant)
    {
        $data = [
            'title' => 'Validasi Persyaratan',
            'route' => $this->route,
            'exam' => $exam,
            'schedule' => $schedule,
            'participant' => $participant,
            'requirements' => $participant->requirements()->with('requirement')->get(),
            'types' => ['Text', 'Checkbox', 'File']
        ];

        return view($this->route.'.validation', $data);
    }

    public function store(Request $request, Exam $exam, ExamSchedule $schedule, ExamParticipant $participant)
    {
        foreach ($participant->requirements as $requirement)
        {
            if ($request->has('validate_'.$requirement->id)) {
                $requirement->update([
                    'validation' => @$request->input('validate_' . $requirement->id)['checklist'] === 'on',
                    'note' => $request->input('validate_' . $requirement->id)['note'],
                    'validated_at' => now()
                ]);
            }
        }

        $participant->status = $request->result === 'on' ? 'Diterima' : 'Ditolak';
        $participant->save();

        return response()->json(['success' => true, 'message' => 'Berhasil disimpan']);
    }

    public function graduate(Exam $exam, ExamSchedule $schedule, ExamParticipant $participant)
    {
        $data = [
            'title' => 'Kelulusan',
            'route' => $this->route,
            'exam' => $exam,
            'schedule' => $schedule,
            'participant' => $participant
        ];

        return view($this->route.'.graduate', $data);
    }

    public function storeGraduate(Request $request, Exam $exam, ExamSchedule $schedule, ExamParticipant $participant)
    {
        $hasFile = $request->has('graduate') && $request->hasFile('certified');
        if ($hasFile) {
           Storage::disk('local')->delete($participant->certified);
        }

        $participant->graduate = $request->has('graduate');
        $participant->certified = $hasFile ? $request->file('certified')->store('participant/certified', 'local') : null;
        $participant->save();

        return response()->json(['success' => true, 'message' => 'Berhasil disimpan']);
    }
}
