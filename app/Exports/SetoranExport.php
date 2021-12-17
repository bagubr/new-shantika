<?php

namespace App\Exports;

use App\Models\OrderPriceDistribution;
use App\Models\OutcomeDetail;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SetoranExport implements FromView, ShouldAutoSize
{
    public function __construct(Request $request)
    {
        $this->request  = $request;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);
        $fleet_detail_id = $queries['fleet_detail_id'];
        $date_search = $queries['date_search'];
        $agency_id = $queries['agency_id'];

        $order_price_distributions = OrderPriceDistribution::query();

        if (!empty($fleet_detail_id)) {
            $order_price_distributions = $order_price_distributions->whereHas('order', function ($q) use ($fleet_detail_id) {
                $q->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED'])->whereHas('fleet_route', function ($sq) use ($fleet_detail_id) {
                    $sq->where('fleet_detail_id', $fleet_detail_id);
                });
            });
        }
        if (!empty($date_search)) {
            $order_price_distributions = $order_price_distributions->whereHas('order', function ($q) use ($date_search) {
                $q->where('reserve_at', $date_search)->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED']);
            });
        }

        if (!empty($agency_id)) {
            $order_price_distributions = $order_price_distributions->whereHas('order', function ($q) use ($agency_id) {
                $q->where('departure_agency_id', $agency_id)->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED']);
            });
        }
        $order_price_distributions = $order_price_distributions->get();
        return view('excel_export.setoran', [
            'order_price_distributions' => $order_price_distributions,
        ]);
    }
}
