<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

    public function getReports(Request $request)
    {
        $perPage = 20;

        $page = $request->input('page', 1);

        $reports = Report::with('user.role')->paginate($perPage, ['*'], 'page', $page);

        return response()->json($reports);
    }

    public function getReportDesc(Request $request)
    {
        $report = Report::where('id', $request->input('id'))->first();
        return response()->json(['data' => json_decode($report->items)]);
    }


}
