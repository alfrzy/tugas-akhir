<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $mySubjects = auth()->user()->enrolledSubjects()->with('user')->get();
        return view('mahasiswa.subjects.index', compact('mySubjects'));
    }

    public function available(Request $request)
    {
        $query = Subject::whereNotNull('user_id')
            ->whereDoesntHave('students', function($q) {
                $q->where('users.id', auth()->id());
            });

        if ($request->has('search')) {
            $query->where('subject_name', 'like', '%' . $request->search . '%')
                  ->orWhere('subject_code', 'like', '%' . $request->search . '%');
        }

        $subjects = $query->with('user')->get();
        return view('mahasiswa.subjects.available', compact('subjects'));
    }

    public function join(Subject $subject)
    {
        auth()->user()->enrolledSubjects()->attach($subject->id);
        return redirect()->route('dashboard')->with('success', 'Berhasil bergabung ke kelas ' . $subject->subject_name);
    }
}