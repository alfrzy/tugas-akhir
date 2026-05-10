<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Question;

class ExamController extends Controller
{
  public function index()
    {
        $mySubjects = Subject::where('user_id', auth()->id())->get();
        $exams = Exam::whereIn('subject_id', $mySubjects->pluck('id'))->with('subject')->get();

        return view('dosen.exams.index', compact('mySubjects', 'exams'));
    }

    // 3. Form buat ujian baru
    public function create(Subject $subject)
    {
        // Proteksi agar dosen tidak bisa buat ujian di matkul orang lain
        if ($subject->user_id !== auth()->id()) abort(403);
        
        return view('dosen.exams.create', compact('subject'));
    }

    // 4. Proses Simpan Ujian & Soal
    public function store(Request $request)
{
    // Validasi bahwa minimal ada 1 soal
    $request->validate([
        'subject_id' => 'required',
        'title' => 'required',
        'duration'   => 'required|integer|min:1',
        'questions' => 'required|array|min:1', 
    ]);

    $exam = Exam::create([
        'subject_id' => $request->subject_id,
        'title' => $request->title,
        'duration'   => $request->duration,
    ]);

    foreach ($request->questions as $q) {
        Question::create([
            'exam_id' => $exam->id,
            'question_text' => $q['text'],
            'key_answer' => $q['key'],
        ]);
    }

    return redirect()->route('dosen.exams.index')->with('success', 'Berhasil membuat ujian BAB dengan beberapa soal.');
}

    public function show(Exam $exam)
{
    // Pastikan dosen hanya bisa lihat ujian dari matkul yang dia ampu
    if ($exam->subject->user_id !== auth()->id()) abort(403);

    // Ambil soal-soal di dalam ujian ini
    $exam->load('questions');
    
    return view('dosen.exams.show', compact('exam'));
}

public function destroy(Exam $exam)
{
    if ($exam->subject->user_id !== auth()->id()) abort(403);

    $exam->delete(); // Karena pakai onDelete('cascade') di migration, soal-soalnya ikut terhapus

    return redirect()->back()->with('success', 'Ujian berhasil dihapus.');
}

// app/Http/Controllers/Dosen/ExamController.php

public function edit(Exam $exam)
{
    // Proteksi akses
    if ($exam->subject->user_id !== auth()->id()) abort(403);

    // Load soal-soalnya
    $exam->load('questions');
    
    return view('dosen.exams.edit', compact('exam'));
}

public function update(Request $request, Exam $exam)
{
    if ($exam->subject->user_id !== auth()->id()) abort(403);

    $request->validate([
        'title' => 'required|string|max:255',
        'duration' => 'required|integer|min:1',
        'questions' => 'required|array|min:1',
    ]);

    // 1. Update Header Ujian
    $exam->update([
        'title' => $request->title,
        'duration' => $request->duration,
    ]);

    // 2. Update Soal (Cara paling aman: hapus soal lama, masukkan yang baru)
    $exam->questions()->delete();
    foreach ($request->questions as $q) {
        $exam->questions()->create([
            'question_text' => $q['text'],
            'key_answer' => $q['key'],
        ]);
    }

    return redirect()->route('dosen.exams.index')->with('success', 'Ujian berhasil diperbarui!');
}
}