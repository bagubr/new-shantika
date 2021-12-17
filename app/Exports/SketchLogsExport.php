<?php

namespace App\Exports;

use App\Models\SketchLog;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SketchLogsExport implements FromView, ShouldAutoSize
{

    public function view(): View
    {
        parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);
        $admin_id = $queries['admin_id'];
        $agency_id = $queries['agency_id'];
        $from_fleet_id = $queries['from_fleet_id'];
        $to_fleet_id = $queries['to_fleet_id'];
        $from_date = $queries['from_date'];
        $to_date = $queries['to_date'];

        $logs = SketchLog::orderBy('created_at', 'desc')
            ->when($admin_id, function ($q) use ($admin_id) {
                $q->where('admin_id', $admin_id);
            })->when($agency_id, function ($q) use ($agency_id) {
                $q->whereHas('order', function ($sq) use ($agency_id) {
                    $sq->where('departure_agency_id', $agency_id);
                });
            })->when($from_fleet_id, function ($q) use ($from_fleet_id) {
                $q->whereHas('from_fleet_route.fleet_detail', function ($sq) use ($from_fleet_id) {
                    $sq->where('fleet_id', $from_fleet_id);
                });
            })->when($to_fleet_id, function ($q) use ($to_fleet_id) {
                $q->whereHas('from_fleet_route.fleet_detail', function ($sq) use ($to_fleet_id) {
                    $sq->where('fleet_id', $to_fleet_id);
                });
            })->when($from_date, function ($q) use ($from_date) {
                $q->where('from_date', '>=', $from_date);
            })->when($to_date, function ($q) use ($to_date) {
                $q->where('to_date', '<=', $to_date);
            })->get();

        return view('excel_export.sketch_logs', [
            'logs' => $logs
        ]);
    }
}
