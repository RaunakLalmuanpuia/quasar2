<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Attendence;
use App\Models\User;

class MarkAttendence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mark-attendence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark users as absent if they have not scanned the QR code on a particular date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all users
        $users = User::all();

        // Get the current date
        // $currentDate = Carbon::now()->toDateString();

        $previousDate = Carbon::now()->subDay()->setTimezone('Asia/Kolkata')->toDateString();



        foreach ($users as $user) {
            $user_id = $user->id;

            // Check if attendance is already marked for the current date and user
            $existingAttendance = Attendence::where('user_id', $user_id)
                ->where('date', $previousDate)
                ->exists();

            if (!$existingAttendance) {
                // If no attendance record for the user and date, mark as absent
                $attendanceStatus = 'Absent';

                $attendance = new Attendence([
                    'user_id' => $user_id,
                    'date' => $previousDate,
                    'time' => Carbon::now()->setTimezone('Asia/Kolkata')->setTime(17, 0, 0)->toTimeString(),
                    'attendence' => $attendanceStatus,
                ]);

                $attendance->save();
            }
        }
        info('Absent users marked successfully.');
    }
}
