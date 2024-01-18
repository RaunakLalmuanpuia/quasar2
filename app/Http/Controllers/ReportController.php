<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Report;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReportVerified;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasRole('employee')) {
            $SEARCH = $request->get('search');
            $employee_id = auth()->user()->id;


            // Without getting employer and manager name directly
            // $employee_reports = Report::where('employee_id', $employee_id)
            //     ->where(function ($query) use ($SEARCH) {
            //         $query->when($SEARCH, fn (Builder $builder) => $builder)
            //             ->where('name', 'LIKE', "%$SEARCH%")
            //             ->orWhere('employer_status', 'LIKE', "%$SEARCH%")
            //             ->orWhere('employer_id', 'LIKE', "%$SEARCH%")
            //             ->orWhere('employer_feedback', 'LIKE', "%$SEARCH%");
            //     })->latest()->paginate(10);

        
            // This Works with quasar table
            $employee_reports =  Report::leftJoin('users as employers', 'employers.id', '=', 'reports.employer_id')
                ->leftJoin('users as employees', 'employees.id', '=', 'reports.employee_id')
                ->leftJoin('users as managers', 'managers.id', '=', 'reports.manager_id')
                ->select('reports.*', 'employers.name as employer_name', 'employees.name as employee_name', 'managers.name as manager_name')
                ->where('reports.employee_id', $employee_id)
                ->where(function ($query) use ($SEARCH) {
                    $query->when($SEARCH, fn (Builder $builder) => $builder)
                        ->where('reports.name', 'LIKE', "%$SEARCH%")
                        ->orWhere('reports.employer_status', 'LIKE', "%$SEARCH%")
                        ->orWhere('reports.employer_id', 'LIKE', "%$SEARCH%")
                        ->orWhere('employers.name', 'LIKE', "%$SEARCH%")
                        ->orWhere('reports.employer_feedback', 'LIKE', "%$SEARCH%")
                        ->orWhere('managers.name', 'LIKE', "%$SEARCH%")
                        ->orWhere('reports.manager_feedback', 'LIKE', "%$SEARCH%")
                        ->orWhere('reports.manager_status', 'LIKE', "%$SEARCH%");
                })->latest()->paginate(10);

            //Using relationship but did not try with quasar table 
            // $employee_reports = Report::with(['employer', 'employee', 'manager'])
            //     ->where('employee_id', $employee_id)
            //     ->where(function ($query) use ($SEARCH) {
            //         $query->when($SEARCH, function ($builder) use ($SEARCH) {
            //             $builder->where('reports.name', 'LIKE', "%$SEARCH%")
            //                 ->orWhere('reports.employer_status', 'LIKE', "%$SEARCH%")
            //                 ->orWhere('reports.employer_id', 'LIKE', "%$SEARCH%")
            //                 ->orWhere('employers.name', 'LIKE', "%$SEARCH%") // Assuming 'employers' is the table name for employers
            //                 ->orWhere('reports.employer_feedback', 'LIKE', "%$SEARCH%")
            //                 ->orWhere('managers.name', 'LIKE', "%$SEARCH%") // Assuming 'managers' is the table name for managers
            //                 ->orWhere('reports.manager_feedback', 'LIKE', "%$SEARCH%")
            //                 ->orWhere('reports.manager_status', 'LIKE', "%$SEARCH%");
            //         });
            //     })
            //     ->latest()
            //     ->paginate(10);


            // dd($employee_reports);

            return Inertia::render('Report/Employee/Index', [
                'employee_reports' => $employee_reports,
                'search' => $SEARCH
            ]);
        }
        if (Auth::user()->hasRole('employer')) {
            $manager = Role::where('name', 'manager')->first()->users; // put a condition that the value can be null

            $employerId = auth()->user()->id; //  get the ID of the currently authenticated user

            $employerPendingFiles =  Report::leftJoin('users as employers', 'employers.id', '=', 'reports.employer_id')
                ->leftJoin('users as employees', 'employees.id', '=', 'reports.employee_id')
                ->leftJoin('users as managers', 'managers.id', '=', 'reports.manager_id')
                ->select('reports.*', 'employers.name as employer_name', 'employees.name as employee_name', 'managers.name as manager_name')
                ->whereIn('employer_status', ['pending', ''])
                ->where('employer_id', $employerId)->latest()->paginate(3); //filters the Report records based on the condition that the employer_id column should match the ID of the authenticated user.

            return Inertia::render(
                'Report/Employer/Index',
                [
                    'employerPendingFiles' => $employerPendingFiles,
                    'manager' => $manager
                ]
            );
        }

        if (Auth::user()->hasRole('manager')) {
            $manager_id = auth()->user()->id;

            $managerPendingFiles = Report::with(['employee', 'employer'])
                ->where('manager_id', $manager_id)
                ->where('employer_status', 'accepted') // only employer approved file is shown to manager
                ->whereIn('manager_status', ['pending', ''])
                ->latest()->paginate(4);




            return Inertia::render('Report/Manager/Index', [
                'managerPendingFiles' => $managerPendingFiles
            ]);
        }
        return abort(401, 'Unauthorized');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if (Auth::user()->hasRole('employee')) {
            $employer = Role::where('name', 'employer')->first()->users;
            return Inertia::render('Report/Employee/Create', [
                'employer' => $employer,
            ]);
        }
        if (Auth::user()->hasRole('employer')) {
            $employerId = auth()->user()->id; //  get the ID of the currently authenticated user

            $employerPendingFiles =  Report::leftJoin('users as employers', 'employers.id', '=', 'reports.employer_id')
                ->leftJoin('users as employees', 'employees.id', '=', 'reports.employee_id')
                ->select('reports.*', 'employers.name as employer_name', 'employees.name as employee_name')
                ->whereIn('employer_status', ['pending', ''])
                ->where('employer_id', $employerId)->get(); //filters the Report records based on the condition that the employer_id column should match the ID of the authenticated user.
            return Inertia::render('Report/Employer/Index', [
                'employerPendingFiles' => $employerPendingFiles,
            ]);
        }
        if (Auth::user()->hasRole('manager')) {

            return abort(404, 'Not Found');
        }
        return abort(401, 'Unauthorized');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->hasRole('employee')) {
            $request->validate([
                'filename' => 'required|string',
                'filepath' => 'required|file',
                'employer' => 'required'

            ]);
            $file = $request->file('filepath');
            $filepath = $file->store('public/reports');
            $fileRecord = new Report([
                'employee_id' => auth()->user()->id,
                'name' => $request->filename,
                'employee_file' => $filepath,
                'employer_id' => $request->employer['value'], // employer user_id
                'movement' => Carbon::now()->setTimezone('Asia/Kolkata')->format('y-m-d')
            ]);

           
            $fileRecord->save();

            //Notify Employer 
            User::where('id', $request->employer['value'])->first()->notify(new ReportVerified($fileRecord));
            return redirect()->route('dashboard')->with('message', 'Report Submitted Successfully!');
        }

        if (Auth::user()->hasRole('employer')) {
            $request->validate([
                'status' => 'required',
            ]);
            if ($request->manager !== null && is_array($request->manager) && array_key_exists('value', $request->manager)) {
                Report::where('id', $request->selectedReport)
                ->update([
                    'manager_id' => $request->manager['value'], // manager user_id
                ]);
                }
            if($request->hasFile('filepath')){
                $file = $request->file('filepath');
                $filepath = $file->store('public/reports');
                Report::where('id', $request->selectedReport)
                ->update([
                    'employer_file' => $filepath, //manager user_id
                ]);
            }
            Report::where('id', $request->selectedReport)
            ->update([
                'employer_status' => $request->status,    
                'employer_feedback' => $request->feedback,
            ]);
           
            
            //notify the Manager and the employer
            //if status is rejected notify the employee and if status 
            //is accepted notify both the employer and manager

            if ($request->status == 'Rejected') {
                $fileRecord = Report::where('id', $request->selectedReport)->first();
                $employee = Report::where('id', $request->selectedReport)->first(['employee_id']);
                User::find($employee->employee_id)->notify(new ReportVerified($fileRecord));
            } elseif ($request->status == 'Accepted') {
                $fileRecord = Report::where('id', $request->selectedReport)->first();
                $employee = Report::where('id', $request->selectedReport)->first(['employee_id']);
                User::find($employee->employee_id)->notify(new ReportVerified($fileRecord)); // Notify Emoloyee
                User::where('id', $request->manager['value'])->first()->notify(new ReportVerified($fileRecord)); // Notify Manager
            }
            return redirect()->route('dashboard')->with('message', 'Report Submitted Successfully!');
        }

        if (Auth::user()->hasRole('manager')) {
            // dd($request->id);
            $request->validate([
                'status' => 'required',
            ]);
         
            if($request->hasFile('filepath')){
                $file = $request->file('filepath');
                $filepath = $file->store('public/reports');
                Report::where('id', $request->id)
                ->update([
                     'manager_file' => $filepath, //manager user_id
                ]);
                }

            Report::where('id', $request->id)
            ->update([
                'manager_status' => $request->status,
                'manager_feedback' => $request->feedback,
            ]);

            //notify the employee and the employer    
            // If manager approves notify both the employee and emoployer and 
            // if manager rejects notify the employer

            if ($request->status == 'Rejected') {
                $fileRecord = Report::where('id', $request->id)->first();
                $employer = Report::where('id', $request->id)->first(['employer_id']);
                
                User::find($employer->employer_id)->notify(new ReportVerified($fileRecord));
            } elseif ($request->status == 'Accepted') {
                $fileRecord = Report::where('id', $request->id)->first();
                $employee = Report::where('id', $request->id)->first(['employee_id']);
                $employer = Report::where('id', $request->id)->first(['employer_id']);
           
                User::find($employee->employee_id)->notify(new ReportVerified($fileRecord)); // Notify Emoloyee
                User::find($employer->employer_id)->notify(new ReportVerified($fileRecord)); // Notify Emoloyer

            }

            return redirect()->route('report.index')->with('message', 'Report Verified by Manager Successfully!');
        }
        return abort(401, 'Unauthorized');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        // dd($report);
        if (Auth::user()->hasRole('manager')) {
            $report->load(['employee', 'employer']);
            // dd($report);
            return Inertia::render('Report/Manager/Show', [
                'report' => $report
            ]);
        }
        // $filepath = $report->employee_file;
        // $fileExtension = pathinfo($filepath, PATHINFO_EXTENSION);
        // if (in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png', 'gif'])) {
        //     // View the file
        //     return response()->file(storage_path('app/' . $filepath));
        // } else {
        //     // Download the file with the extension name
        //     return Storage::download($filepath, $report->name . '.' . $fileExtension);
        // }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        
        // dd($report->load('employee', 'employer', 'manager'));
        // dd($report->with(['employee', 'employer', 'manager'])->get());
        if (Auth::user()->hasRole('employee')) {
            $employer = Role::where('name', 'employer')->first()->users;
            return Inertia::render('Report/Employee/Edit', [
                'report' => $report->load('employer'),
                'employer' => $employer
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {

        // dd($request);
        if (Auth::user()->hasRole('employee')) {
            // dd($request->file_name);
            // dd($request->filepath);
            $report->name = $request->file_name;
            $file = $request->file('filepath');
            $filepath = $file->store('public/reports');
            $report->employee_file = $filepath;
           
            if ($request->employer_id !== null && is_array($request->employer_id) && array_key_exists('value', $request->employer_id)) {
                $report->employer_id = $request->employer_id['value'];
            }
            $report->save();
            return redirect()->route('report.index')->with('message', 'Report Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
    }
    public function viewEmployee(Report $report)
    {
        $filepath = $report->employee_file;
        $fileExtension = pathinfo($filepath, PATHINFO_EXTENSION);
        if (in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png', 'gif'])) {
            // View the file
            return response()->file(storage_path('app/' . $filepath));
        } else {
            // Download the file with the extension name
            return Storage::download($filepath, $report->name . '.' . $fileExtension);
        }
    }
    public function viewEmployer(Report $report)
    {
        $filepath = $report->employer_file;
        $fileExtension = pathinfo($filepath, PATHINFO_EXTENSION);
        if (in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png', 'gif'])) {
            // View the file
            return response()->file(storage_path('app/' . $filepath));
        } else {
            // Download the file with the extension name
            return Storage::download($filepath, $report->name . '.' . $fileExtension);
        }
    }
    public function viewManager(Report $report)
    {
        $filepath = $report->manager_file;
        $fileExtension = pathinfo($filepath, PATHINFO_EXTENSION);
        if (in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png', 'gif'])) {
            // View the file
            return response()->file(storage_path('app/' . $filepath));
        } else {
            // Download the file with the extension name
            return Storage::download($filepath, $report->name . '.' . $fileExtension);
        }
    }

    public function update_employee(Request $request, Report $report){
        
        if (Auth::user()->hasRole('employee')) {
            $report->name = $request->file_name;
            // $file = $request->file('filepath');
            // $filepath = $file->store('public/reports');
            // $report->employee_file = $filepath;
            if($request->hasFile('filepath')){
                $file = $request->file('filepath');
                $filepath = $file->store('public/reports');
                Report::where('id', $request->id)
                ->update([
                     'employee_file' => $filepath, //employeer
                ]);
                }

            if ($request->employer_id !== null && is_array($request->employer_id) && array_key_exists('value', $request->employer_id)) {
                $report->employer_id = $request->employer_id['value'];
                $report->employer_status ='pending';
                $report->employer_feedback ='';
            }
            $report->save();
            return redirect()->route('report.index')->with('message', 'Report Updated Successfully');
        }
    }
}
