<?php

namespace App\Http\Controllers;
use App\Models\ClassModel;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    public function index(){
        $classes = ClassModel::all()->where('status', 'active');
        $grades = Grade::all();
        $subjects = Subject::all();
        return view('admin.classes', compact('classes', 'grades', 'subjects'));
    }

    public function store(Request $request)
    {
        $class = new ClassModel();
        $validated = $request->validate([
            'name' => 'required',
            'grade_id' => 'required',
            'subject_id' => 'required',
            'teacher' => 'nullable',
            'new_old_status' => 'nullable',
        ]);
        $class->name = $validated['name'];
        $class->grade_id = $validated['grade_id'];
        $class->subject_id = $validated['subject_id'];
        $class->teacher = $validated['teacher'];
        $class->new_old_status = $validated['new_old_status'] ?? 'old';
        $class->save();
        return redirect()->back()->with('success', 'Class created successfully');
        
    }

    public function update(Request $request, $id)
    {
        $class = ClassModel::find($id);
        
        $class->name = $request['name'];
        $class->grade_id = $request['grade_id'];
        $class->subject_id = $request['subject_id'];
        $class->teacher = $request['teacher'];
        $class->new_old_status = $request['new_old_status'];
        $class->update();
        return redirect()->back()->with('success', 'Class updated successfully');
    }

    public function delete($id){
        $class = ClassModel::find($id);
        if($class){
            $class->status = 'inactive';
            $class->update();
            return redirect()->back()->with('success', 'Class deleted successfully');
        }
        return redirect()->back()->with('error', 'Class not found');
    }
}
