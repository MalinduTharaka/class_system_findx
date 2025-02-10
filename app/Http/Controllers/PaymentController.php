<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Attendance;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    public function payClassFee(Request $request)
    {

        $attendance = new Attendance();
        $attendance->class_id = $request->class_id;
        $attendance->student_id = $request->student_id;
        $attendance->attendance_date = Carbon::today();
        $attendance->status = 'attended';
        $attendance->save();

        if($request->paid_amount)
        {
            $payment = new Payment();
            $payment->student_id = $request->student_id;
            $payment->class_id = $request->class_id;
            $payment->total = $request->total;
            $payment->paid_amount = $request->paid_amount;
            $payment->month = $request->month;
            $payment->save();
            return redirect()->route('assign-student', $request->class_id)->with('success', 'Paid and attendence successfully!');
        }
        
        return redirect()->route('assign-student', $request->class_id)->with('success', 'Attendence saved successfully!');

    }
}
