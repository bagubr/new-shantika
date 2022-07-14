<?php

namespace App\Exports;

use App\Models\OrderPriceDistribution;
use App\Models\OutcomeDetail;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SetoranExport implements FromView, ShouldAutoSize
{
    public function __construct(Request $request)
    {
        $this->request  = $request;
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);
        $fleet_detail_id = $queries['fleet_detail_id']??null;
        $date_search = $queries['date_search']??null;
        $agency_id = $queries['agency_id']??null;
        $area_id = $queries['area_id']??null;

        $order_price_distributions  = OrderPriceDistribution::whereHas('order', function ($query)
        {
            $query->whereIn('status', ['PAID', 'EXCHANGED', 'FINSIHED']);
        })
        ->when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('order.fleet_route.route.checkpoints.agency.city', function ($query) use ($area_id)
            {
                $query->where('area_id', '!=', $area_id);
            });
        })
        ->when($fleet_detail_id, function ($query) use ($fleet_detail_id)
        {
            $query->whereHas('order', function ($q) use ($fleet_detail_id) {
                $q->whereHas('fleet_route', function ($sq) use ($fleet_detail_id) {
                    $sq->where('fleet_detail_id', $fleet_detail_id);
                });
            });
        })
        ->when($date_search, function ($query) use ($date_search)
        {
            $query->whereHas('order', function ($q) use ($date_search) {
                $q->whereDate('reserve_at', $date_search);
            });
        })
        ->when($agency_id, function ($query) use ($agency_id)
        {
            $query->whereHas('order', function ($q) use ($agency_id) {
                $q->where('departure_agency_id', $agency_id);
            });
        })->get();
        return view('excel_export.setoran', [
            'order_price_distributions' => $order_price_distributions,
        ]);
    }
}
