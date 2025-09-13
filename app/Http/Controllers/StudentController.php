<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::paginate(15);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = \App\Models\ParentProfile::with('account')->get();
        $schools = \App\Models\School::with('account')->get();

        return view('admin.students.create', compact('parents', 'schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'required|exists:parents,parent_id',
            'school_id' => 'required|exists:schools,school_id',
            'given_name' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'identity_number' => 'nullable|string|max:20',
            'birth_date' => 'required|date',
            'grade' => 'required|string|max:10',
            'shift' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'phone_number' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,graduated,transferred',
            'has_transport' => 'boolean'
        ]);

        Student::create($validated);

        return redirect()->route('admin.students')->with('success', 'Estudiante creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load([
            'parentProfile.account',
            'school',
            'transportContracts.provider',
            'transportContracts.pickupRoute',
            'transportContracts.dropoffRoute',
            'transportContracts.subscription'
        ]);
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $parents = \App\Models\ParentProfile::with('account')->get();
        $schools = \App\Models\School::with('account')->get();

        return view('admin.students.edit', compact('student', 'parents', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'parent_id' => 'required|exists:parents,parent_id',
            'school_id' => 'required|exists:schools,school_id',
            'given_name' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'identity_number' => 'nullable|string|max:20',
            'birth_date' => 'required|date',
            'grade' => 'required|string|max:10',
            'shift' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'phone_number' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,graduated,transferred',
            'has_transport' => 'boolean'
        ]);

        $student->update($validated);

        return redirect()->route('admin.students')->with('success', 'Estudiante actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students')->with('success', 'Estudiante eliminado exitosamente');
    }

    /**
     * Activate student
     */
    public function activate(Student $student)
    {
        $student->update(['status' => 'active']);

        return redirect()->route('admin.students')->with('success', 'Estudiante activado exitosamente');
    }

    /**
     * Suspend student
     */
    public function suspend(Student $student)
    {
        $student->update(['status' => 'inactive']);

        return redirect()->route('admin.students')->with('success', 'Estudiante suspendido exitosamente');
    }

    /**
     * Upload documents
     */
    public function uploadDocuments(Request $request, Student $student)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'document_file' => 'required|file|max:10240'
        ]);

        $file = $request->file('document_file');
        $path = $file->store('student-documents', 'public');

        // Aquí podrías guardar la información del documento en la base de datos

        return redirect()->route('admin.students.show', $student)->with('success', 'Documento subido exitosamente');
    }

    /**
     * Bulk update students
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,student_id',
            'status' => 'required|in:active,inactive,graduated,transferred'
        ]);

        Student::whereIn('student_id', $request->student_ids)
            ->update(['status' => $request->status]);

        return redirect()->route('admin.students')->with('success', 'Estudiantes actualizados exitosamente');
    }

    /**
     * Export students
     */
    public function export()
    {
        $students = Student::with(['parentProfile.account', 'school'])->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="students.csv"',
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID', 'Nombre', 'Apellido', 'Cédula', 'Fecha Nacimiento', 'Grado', 'Turno', 'Dirección', 'Teléfono', 'Padre', 'Escuela',
                'Estado', 'Tiene Transporte', 'Fecha Creación'
            ]);

            // Data
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->student_id,
                    $student->given_name,
                    $student->family_name,
                    $student->identity_number ?? 'N/A',
                    $student->birth_date->format('Y-m-d'),
                    $student->grade ?? 'N/A',
                    $student->shift ?? 'N/A',
                    $student->address ?? 'N/A',
                    $student->phone_number ?? 'N/A',
                    $student->parentProfile->account->full_name ?? 'N/A',
                    $student->school->legal_name ?? 'N/A',
                    $student->status ?? 'active',
                    $student->has_transport ? 'Sí' : 'No',
                    $student->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

