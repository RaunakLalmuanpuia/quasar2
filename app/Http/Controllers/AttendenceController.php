<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Crypt;
use App\Models\Attendence;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;


class AttendenceController extends Controller
{
    
    public function showQr()
    {
        // Home lat long ['latitude' => 23.75502617646347, 'longitude' => 92.72983056645494];
        // Msegs 23.724467856537363, 92.71939653576747
        $currentLocation = ['latitude' => 23.724467856537363, 'longitude' => 92.72983056645494]; // home
        $locationData = "Latitude: {$currentLocation['latitude']}, Longitude: {$currentLocation['longitude']}";

        // Prepare data (current date, name, and number)
        $currentDate = Carbon::now('Asia/Kolkata')->toDateString();
        $name = auth()->user()->name; // Replace this with the desired name
        $user_id = auth()->user()->id;
        $number = '12345'; // Replace this with the desired number
        
        // Combine the data into a single string
        $data = "Date: $currentDate\nUserId: $user_id\nName: $name\nNumber: $number\nLocation: $locationData";
        
        $password = auth()->user()->password; 
        // $data = "Date: $currentDate\nName: $name\nNumber: $number\nPassword: $password\nLocation: $locationData";
       
        // Create Qrcode using SimpleSoftwareIO

        return QrCode::size(200)
        ->backgroundColor(255, 255, 0)
        ->color(0, 0, 255)
        ->margin(1)
        ->generate(
            $data ,
        );
       
       
    }

    public function scanQr()
    {
        // Check if attendance is already marked for the current date and user
        $user_id = auth()->user()->id;
        $currentDate = Carbon::now('Asia/Kolkata')->toDateString();
        $existingAttendance = Attendence::where('user_id', $user_id)
            ->where('date', $currentDate)
            ->exists();

        if ($existingAttendance) {
            // Attendance already marked for the current date
            return redirect()->route('dashboard')->with('message', 'Attendance already marked for today.');
        }

        // Check if the current time is within the allowed period (8 am to 9:30 am)
        // $currentTime = Carbon::now('Asia/Kolkata');
        // $allowedStartTime = Carbon::now('Asia/Kolkata')->setHour(8)->setMinute(0)->setSecond(0);
        // $allowedEndTime = Carbon::now('Asia/Kolkata')->setHour(9)->setMinute(30)->setSecond(0);
        
        // if ($currentTime < $allowedStartTime || $currentTime > $allowedEndTime) {
        //         // If the current time is outside the allowed period, show a message or take appropriate action
        //     return redirect()->route('dashboard')->with('message', 'QR code scanning is allowed only between 8 am and 9:30 am.');
        // }

        // Render the QR code scanning page
        return Inertia::render('Qrcode/ScanQr');
    }

    public function markAttendence(Request $request)
    {
        // dd($request);
        $request->validate([
            'date' => 'required|string',
            'time' => 'required|string',     
        ]);
        $date = Carbon::parse($request->input('date'));
        $time = Carbon::parse($request->input('time'));
        $user_id = auth()->user()->id;
        
        //see status of attendence
        $attendanceStatus = $this->calculateAttendanceStatus($date, $time);

        // dd($attendanceStatus);
        // Store the attendance information in your database or perform other actions
       
        $attendence = new Attendence([
            'user_id' => $user_id,
            'date' => $request->date,
            'time' => $request->time,
            'attendence' => $attendanceStatus,
            

        ]);
        $attendence->save();
        return redirect()->route('dashboard')->with('message', 'Attendance marked successfully');
    }
    //calculate attendence status
    private function calculateAttendanceStatus(Carbon $date, Carbon $time)
    {
        $nineThirtyAM = $date->copy()->setTime(9, 30, 0);
        $fivePM = $date->copy()->setTime(17, 0, 0); // 5 PM
    
        if ($time->lt($nineThirtyAM)) {
            return 'Present';
        } elseif ($time->lt($fivePM)) {
            return 'Half Day';
        } else {
            return 'Absent';
        }
    }


    public function verifyPassword(Request $request){

        $userId = $request->input('userId');
        $enteredPassword = $request->input('enteredPassword');
    
        // Retrieve the user's hashed password from the database
        $user = User::findOrFail($userId);
        $hashedPassword = $user->password;
    
        // Compare the hashed passwords
        $passwordVerified = Hash::check($enteredPassword, $hashedPassword);
    
         // Check if the password is verified
        if ($passwordVerified) {
            // Password is correct, redirect with a success message
            return redirect()->route('scanQr')->with('message', 'Password verification successful');
        } else {
            // Password is incorrect, redirect with an error message
            return redirect()->route('scanQr')->with('message','Incorrect password.');
           
        }
    }

}
