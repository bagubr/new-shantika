<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;


class OrdersExport implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);
        $name_search = $queries['name'];
        $code_order_search = $queries['code_order'];
        $status_search = $queries['status'];
        $status_agent = $queries['agent'];
        $fleet_detail_id = $queries['fleet_detail_id'];
        $agency_id = $queries['agency_id'];
        $date_from_search = $queries['date_from'];
        $date_from_to = $queries['date_to'];

        $orders = Order::query();

        if (!empty($name_search)) {
            $orders = $orders->whereHas('user', function ($q) use ($name_search) {
                $q->where('name', 'ilike', '%' . $name_search . '%');
            });
        }
        if (!empty($code_order_search)) {
            $orders = $orders->where('code_order', 'like', '%' . $code_order_search . '%');
        }
        if (!empty($status_search)) {
            $orders = $orders->where('status', $status_search);
        }
        if (!empty($status_agent)) {
            if ($status_agent == 'AGENT') {
                $orders = $orders->whereHas('user', function ($q) {
                    $q->has('agencies');
                });
            } elseif ($status_agent == 'UMUM') {
                $orders = $orders->whereHas('user', function ($y) {
                    $y->doesntHave('agencies');
                })->orWhereDoesntHave('user');
            }
        }
        if (!empty($agency_id)) {
            $orders = $orders->where('departure_agency_id', $agency_id);
        }
        if (!empty($fleet_detail_id)) {
            $orders = $orders->whereHas('fleet_route', function ($q) use ($fleet_detail_id) {
                $q->where('fleet_detail_id', $fleet_detail_id);
            });
        }
        if (!empty($date_from_search)) {
            if (!empty($date_from_to)) {
                $orders = $orders->where('reserve_at', '>=', $date_from_search)->where('reserve_at', '<=', $date_from_to);
            }
            $orders = $orders->where('reserve_at', '>=', $date_from_search);
        }
        $orders = $orders->orderBy('id', 'desc')->get();
        return view('excel_export.orders', [
            'orders' => $orders,
        ]);
    }
}
