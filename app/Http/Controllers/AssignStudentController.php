<?php

namespace App\Http\Controllers;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\AssignToClass;
use App\Models\Grade;
use Illuminate\Http\Request;


class AssignStudentController extends Controller
{
    public function index($id)
    {
        // Use the $class_id to retrieve the class and pass it to the view
        $class = ClassModel::find($id);
        $students = Student::all()->where('status', 'active');
        $assigns = AssignToClass::all()->where('class_id', $id);
        $grades = Grade::all();
        return view('admin.assign-student', compact('class', 'students','assigns'));
    }

    public function store(Request $request)
    {
        // Retrieve the class and student from the request
        $assign = AssignToClass::create([
            'class_id' => $request->class_id,
            'student_id' => $request->student_id,
            'added_year' => $request->added_year,
            'added_datetime' => now(),
            'status' => 'active',
            'new_old_status' => $request->new_old_status,
            'deactivate_date' => null,
            'deactivate_reason' => null,
        ]);
        return redirect()->route('assign-student', $assign->class_id)->with('success','Student assigned successfully');
    }

    public function update(Request $request, $id)
    {
        $assign = AssignToClass::find($id);

        if($assign){
            $assign->update([
                'added_year' => $request->added_year,
                'new_old_status' => $request->new_old_status,
            ]);   
            return redirect()->route('assign-student', $assign->class_id)->with('success','Student updated successfully');
        }
    }

    public function deactivate(Request $request, $id)
    {
        $assign = AssignToClass::find($id);
        if ($assign) {
            $assign->update([
                'status' => 'inactive',
                'deactivate_date' => now(),
                'deactivate_reason' => $request->deactivate_reason,
            ]);
            return redirect()->route('assign-student', $assign->class_id)->with('success', 'Student deactivated successfully');
        }
    }

    public function upgrade(Request $request, $id)
{
    $currentClass = ClassModel::find($id);

    if (!$currentClass) {
        return redirect()->back()->with('error', 'Class not found.');
    }

    // Validate grade
    $currentGrade = $currentClass->grade->name;
    if (in_array($currentGrade, ['A/L', 'O/L'])) {
        return redirect()->back()->with('error', 'Cannot upgrade A/L or O/L classes.');
    }

    // Get upper-grade class
    $upperGradeClass = ClassModel::where('grade_id', $currentClass->grade_id + 1)->first();

    if (!$upperGradeClass) {
        return redirect()->back()->with('error', 'No upper grade class found.');
    }

    // Check if any students in the upper grade class are active
    $activeStudentsInUpperGrade = AssignToClass::where('class_id', $upperGradeClass->id)
        ->where('status', 'active')
        ->exists();

    if ($activeStudentsInUpperGrade) {
        return redirect()->back()->with('error', 'First upgrade students in the upper grade class.');
    }

    // Upgrade students
    $studentsToUpgrade = AssignToClass::where('class_id', $currentClass->id)->get();

    foreach ($studentsToUpgrade as $student) {
        $student->update([
            'class_id' => $upperGradeClass->id,
            'added_year' => $student->added_year + 1,
        ]);
    }

    return redirect()->route('assign-student', $upperGradeClass->id)
        ->with('success', 'Class upgraded successfully.');
}


}
