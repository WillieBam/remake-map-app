<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Report;

class ReportController extends Controller
{
    public function createReport(Request $request, $country_id)
    {
        // Get the authenticated user who reported the message
        $user_id = $request->user()->user_id;

        // Validate the request data
        $request->validate([
            'message_id' => 'required|exists:messages,message_id',
        ]);
        
        // Create new report
        $report = Report::create($request->input());
        $report->user_id = $user_id;
        $report->save();

        // Redirect back to previous page
        return redirect('/countries/' . $country_id);
    }

    public function viewReports()
    {
        // Summarize all reports based on message_id
        $allReports = Report::all();
        $filteredReports = [];

        foreach ($allReports as $report)
        {
            if (!array_key_exists($report->message_id, $filteredReports))
            {
                $filteredReports[$report->message_id] = [
                    'message' => $report->message,
                    'reports' => []
                ];
            }

            $filteredReports[$report->message_id]['reports'][] = $report;
        }

        // Pass reduced reports to views
        return view('viewReports', ['reports' => $filteredReports, 'report' => $filteredReport]);
    }

    public function viewReportsWithId($message_id)
    {
        // Summarize reports based on message_id
        $selectedReports = Report::where('message_id', $message_id);
        $filteredReport = [];

        if ($selectedReports->count() > 0)
        {
            $filteredReport = [
                'message' => $selectedReports->first()->message,
                'reports' => $selectedReports->get()
            ];
        }

        // Summarize all reports based on message_id
        $allReports = Report::all();
        $filteredReports = [];

        foreach ($allReports as $report)
        {
            if (!array_key_exists($report->message_id, $filteredReports))
            {
                $filteredReports[$report->message_id] = [
                    'message' => $report->message,
                    'reports' => []
                ];
            }

            $filteredReports[$report->message_id]['reports'][] = $report;
        }

        // Pass reduced reports to views
        return view('viewReports', ['reports' => $filteredReports, 'report' => $filteredReport]);
    }

    public function searchReport($keyword, $order)
    {

    }
}
