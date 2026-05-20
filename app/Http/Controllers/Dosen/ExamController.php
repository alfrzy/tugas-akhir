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

    public function downloadTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_soal.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Pertanyaan', 'Kunci Jawaban'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Contoh isi template
            fputcsv($file, ['Jelaskan apa itu OOP?', 'Object Oriented Programming adalah paradigma pemrograman berbasis objek.']);
            fputcsv($file, ['Sebutkan fungsi sistem operasi.', 'Sistem operasi berfungsi sebagai jembatan antara perangkat keras dan pengguna.']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 4. Proses Simpan Ujian & Soal
    public function store(Request $request)
    {
        // Validasi, pastikan minimal ada soal manual ATAU ada file CSV
        $request->validate([
            'subject_id' => 'required',
            'title' => 'required',
            'duration'   => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
            'questions'  => 'required_without:csv_file|array', 
            'csv_file'   => 'nullable|file|mimes:csv,txt'
        ]);

        $exam = Exam::create([
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'duration'   => $request->duration,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);

        // 1. Simpan soal dari form manual (jika ada)
        if ($request->has('questions')) {
            foreach ($request->questions as $q) {
                if (!empty($q['text']) && !empty($q['key'])) {
                    Question::create([
                        'exam_id' => $exam->id,
                        'question_text' => $q['text'],
                        'key_answer' => $q['key'],
                    ]);
                }
            }
        }

        // 2. Simpan soal dari file CSV (jika ada upload)
        if ($request->hasFile('csv_file')) {
            $path = $request->file('csv_file')->getRealPath();
            $data = array_map('str_getcsv', file($path));
            
            // Hilangkan header jika ada (Mendeteksi dari kolom pertama berbunyi 'Pertanyaan' / 'pertanyaan')
            if (count($data) > 0 && strtolower(trim($data[0][0])) === 'pertanyaan') {
                array_shift($data);
            }

            foreach ($data as $row) {
                // Pastikan ada setidaknya 2 kolom yang tidak kosong (Pertanyaan & Kunci)
                if (count($row) >= 2 && !empty(trim($row[0])) && !empty(trim($row[1]))) {
                    Question::create([
                        'exam_id' => $exam->id,
                        'question_text' => trim($row[0]),
                        'key_answer' => trim($row[1]),
                    ]);
                }
            }
        }

        return redirect()->route('dosen.exams.index')->with('success', 'Berhasil membuat ujian beserta soal-soalnya.');
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
        'start_time' => 'required|date',
        'end_time'   => 'required|date|after:start_time',
    ]);

    // 1. Update Header Ujian
    $exam->update([
        'title' => $request->title,
        'duration' => $request->duration,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
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