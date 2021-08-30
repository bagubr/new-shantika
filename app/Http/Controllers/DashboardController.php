<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Fleet;
use App\Models\Order;
use App\Models\OrderPriceDistribution;
use App\Models\Route;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $status_order_selesai = ['PAID', 'EXCHANGED', 'FINISHED'];
        $data_statistic = ['weekly' => 'Harian', 'monthly' => 'Bulan', 'yearly' => 'Tahun'];
        if ($request->statistic) {
            if ($request->statistic == 'yearly') {
                $data = $this->tiket_tahunan();
            } elseif ($request->statistic == 'monthly') {
                $data = $this->tiket_bulanan();
            } else {
                $data = $this->tiket_harian();
            }
        } else {
            $data = $this->tiket_harian();
        }
        // AGENCY
        $agencies = Agency::all();
        $fleets = Fleet::get(['id', 'name']);
        $routes = Route::get(['id', 'name']);
        $orders = Order::query();
        $fleet = $request->fleet;
        if (!empty($request->agency)) {
            $orders = $orders->where('user_id', $request->agency);
        }
        if (!empty($request->route)) {
            $orders = $orders->where('route_id', $request->route);
        }
        if (!empty($request->fleet)) {
            $orders = $orders->whereHas('route', function ($q) use ($fleet) {
                $q->where('fleet_id', $fleet);
            });
        }

        $orders = $orders->orderBy('id', 'desc')->paginate(7);
        $order_count = Order::all()->count();
        $test = $request->flash();
        $users = User::all();
        $count_user = User::doesntHave('agencies')->count();
        $orders_money = Order::whereIn('status', $status_order_selesai)->sum('price');
        session()->flash('Success', 'Berhasil Memuat Halaman');
        return view('dashboard2', compact('users', 'orders', 'order_count', 'count_user', 'orders_money', 'agencies', 'fleets', 'routes', 'data', 'data_statistic'));
    }

    // START OF TIKET

    public function tiket_harian()
    {
        $params = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
        $status_order_selesai = ['PAID', 'EXCHANGED', 'FINISHED'];

        for ($i = 0; $i < 7; $i++) {
            $startOfLastWeek  = Carbon::now()->startOfWeek()->addDay($i);
            $order_jawa[] = Order::whereHas('fleet_route.route', function ($q) {
                $q->whereHas('checkpoints.agency', function ($sq) {
                    $sq->whereHas('city', function ($smq) {
                        $smq->where('area_id', 1);
                    });
                });
            })->whereDate('reserve_at', '=', $startOfLastWeek)->whereIn('status', $status_order_selesai)->get()->count();
            $order_jabodetabek[] = Order::whereHas('fleet_route.route', function ($q) {
                $q->whereHas('checkpoints.agency', function ($sq) {
                    $sq->whereHas('city', function ($smq) {
                        $smq->where('area_id', 2);
                    });
                });
            })->whereDate('reserve_at', '=', $startOfLastWeek)->whereIn('status', $status_order_selesai)->get()->count();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;

        $data = [
            'params' => $params,
            'weekly' => $weekly,
            'weekly2' => $weekly2
        ];
        return $data;
    }
    public function tiket_bulanan()
    {
        $params = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "August", "September", "October", "November", "Desember"];
        $status_order_selesai = ['PAID', 'EXCHANGED', 'FINISHED'];

        for ($i = 0; $i < 12; $i++) {
            $start    =  Carbon::now()->startOfYear()->addMonth($i)->format('Y-m-d');
            $end      =  Carbon::now()->startOfYear()->endOfMonth()->addMonth($i)->format('Y-m-d');
            $order_jawa[] = Order::whereHas('fleet_route.route', function ($q) {
                $q->whereHas('checkpoints.agency', function ($sq) {
                    $sq->whereHas('city', function ($smq) {
                        $smq->where('area_id', 1);
                    });
                });
            })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->whereIn('status', $status_order_selesai)->get()->count();
            $order_jabodetabek[] = Order::whereHas('fleet_route.route', function ($q) {
                $q->whereHas('checkpoints.agency', function ($sq) {
                    $sq->whereHas('city', function ($smq) {
                        $smq->where('area_id', 2);
                    });
                });
            })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->whereIn('status', $status_order_selesai)->get()->count();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;

        $data = [
            'params' => $params,
            'weekly' => $weekly,
            'weekly2' => $weekly2
        ];
        return $data;
    }
    public function tiket_tahunan()
    {
        for ($i = 0; $i < 10; $i++) {
            $year[] = Carbon::now()->startOfDecade()->addYear($i)->format('Y');
        }
        $status_order_selesai = ['PAID', 'EXCHANGED', 'FINISHED'];
        for ($i = 0; $i < 10; $i++) {
            $start          = Carbon::now()->startOfDecade()->addYear($i)->format('Y');
            $order_jawa[]   = Order::whereHas('fleet_route.route', function ($q) {
                $q->whereHas('checkpoints.agency', function ($sq) {
                    $sq->whereHas('city', function ($smq) {
                        $smq->where('area_id', 1);
                    });
                });
            })->whereYear('reserve_at', '=', $start)->whereIn('status', $status_order_selesai)->get()->count();
            $order_jabodetabek[]   = Order::whereHas('fleet_route.route', function ($q) {
                $q->whereHas('checkpoints.agency', function ($sq) {
                    $sq->whereHas('city', function ($smq) {
                        $smq->where('area_id', 2);
                    });
                });
            })->whereYear('reserve_at', '=', $start)->whereIn('status', $status_order_selesai)->get()->count();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;

        $data = [
            'params' => $year,
            'weekly' => $weekly,
            'weekly2' => $weekly2
        ];
        return $data;
    }

    // END OF TIKET

    // public function index(Request $request)
    // {
    //     $data_statistic = ['weekly' => 'Harian', 'monthly' => 'Bulan', 'yearly' => 'Tahun'];
    //     if ($request->statistic) {
    //         if ($request->statistic == 'yearly') {
    //             $data = $this->yearly();
    //         } elseif ($request->statistic == 'monthly') {
    //             $data = $this->monthly();
    //         } else {
    //             $data = $this->weekly();
    //         }
    //     } else {
    //         $data = $this->weekly();
    //     }
    //     if ($request->tiket) {
    //         if ($request->tiket == 'yearly') {
    //             $data_tiket = $this->tiket_tahun();
    //         } elseif ($request->tiket == 'monthly') {
    //             $data_tiket = $this->pendapatan_monthly();
    //         } else {
    //             $data_tiket = $this->tiket_weekly();
    //         }
    //     } else {
    //         $data_tiket = $this->tiket_weekly();
    //     }
    //     if ($request->pendapatan) {
    //         if ($request->pendapatan == 'yearly') {
    //             $data_week = $this->pendapatan_yearly();
    //         } elseif ($request->pendapatan == 'monthly') {
    //             $data_week = $this->pendapatan_monthly();
    //         } else {
    //             $data_week = $this->pendapatan_weekly();
    //         }
    //     } else {
    //         $data_week = $this->pendapatan_weekly();
    //     }
    //     // AGENCY
    //     $agencies = Agency::all();
    //     $fleets = Fleet::get(['id', 'name']);
    //     $routes = Route::get(['id', 'name']);
    //     $orders = Order::query();
    //     $fleet = $request->fleet;
    //     if (!empty($request->agency)) {
    //         $orders = $orders->where('user_id', $request->agency);
    //     }
    //     if (!empty($request->route)) {
    //         $orders = $orders->where('route_id', $request->route);
    //     }
    //     if (!empty($request->fleet)) {
    //         $orders = $orders->whereHas('route', function ($q) use ($fleet) {
    //             $q->where('fleet_id', $fleet);
    //         });
    //     }

    //     $orders = $orders->orderBy('id', 'desc')->paginate(7);
    //     $order_count = Order::all()->count();
    //     $test = $request->flash();
    //     $users = User::all();
    //     $count_user = User::doesntHave('agencies')->count();
    //     $orders_money = Order::has('route')->sum('price');
    //     session()->flash('Success', 'Berhasil Memuat Halaman');
    //     return view('dashboard2', compact('users', 'orders', 'order_count', 'count_user', 'orders_money', 'agencies', 'fleets', 'routes', 'data', 'data_statistic', 'data_week', 'data_tiket'));
    // }

    public function tiket_tahun()
    {
        $params = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "August", "September", "October", "November", "Desember"];
        $thisYear  = Carbon::now()->startOfYear()->format('Y');
        $lastYear  = Carbon::now()->subYear()->startOfYear()->format('Y');

        for ($i = 0; $i < 12; $i++) {
            $start    =  Carbon::now()->startOfYear()->addMonth($i);
            $last    =  Carbon::now()->subYear()->startOfYear()->addMonth($i);
            $order_jawa[] = Order::whereYear('reserve_at', '=', $start)->whereMonth('reserve_at', '=', $start)->where('status', 'PAID')->whereHas('route', function ($q) {
                $q->where('area_id', 1);
            })->get()->pluck('price')->sum();
            $order_jawa_last[] = Order::whereYear('reserve_at', '=', $last)->whereMonth('reserve_at', '=', $last)->where('status', 'PAID')->whereHas('route', function ($q) {
                $q->where('area_id', 1);
            })->get()->pluck('price')->sum();
            $order_jabodetabek[] = Order::whereYear('reserve_at', '=', $start)->whereMonth('reserve_at', '=', $start)->where('status', 'PAID')->whereHas('route', function ($q) {
                $q->where('area_id', 2);
            })->get()->pluck('price')->sum();
            $order_jabodetabek_last[] = Order::whereYear('reserve_at', '=', $last)->whereMonth('reserve_at', '=', $last)->where('status', 'PAID')->whereHas('route', function ($q) {
                $q->where('area_id', 2);
            })->get()->pluck('price')->sum();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;
        $weekly_last[] = $order_jawa_last;
        $weekly_last2[] = $order_jabodetabek_last;

        $data_tiket = [
            'last_week' => "$lastYear",
            'this_week' => "$thisYear",
            'params' => $params,
            'weekly' => $weekly,
            'weekly2' => $weekly2,
            'weekly_last' => $weekly_last,
            'weekly_last2' => $weekly_last2,
        ];
        return $data_tiket;
    }
    public function tiket_weekly()
    {
        $params = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
        $startOfThisWeek  = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfThisWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');
        for ($i = 0; $i < 7; $i++) {
            $startOfWeek  = Carbon::now()->startOfWeek()->addDay($i);
            $startOfLastWeeks = Carbon::now()->subWeek()->startOfWeek()->addDay($i);
            $order_jawa[] = Order::where('reserve_at', '=', $startOfWeek)->where('status', 'PAID')->whereHas('route', function ($q) {
                $q->where('area_id', 1);
            })->get()->pluck('price')->sum();
            $order_jawa_last[] = Order::where('reserve_at', '=', $startOfLastWeeks)->where('status', 'PAID')->whereHas('route', function ($q) {
                $q->where('area_id', 1);
            })->get()->pluck('price')->sum();

            $order_jabodetabek[] = Order::where('reserve_at', '=', $startOfWeek)->where('status', 'PAID')->whereHas('route', function ($q) {
                $q->where('area_id', 2);
            })->get()->pluck('price')->sum();
            $order_jabodetabek_last[] = Order::where('reserve_at', '=', $startOfLastWeeks)->where('status', 'PAID')->whereHas('route', function ($q) {
                $q->where('area_id', 2);
            })->get()->pluck('price')->sum();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;
        $weekly_last[] = $order_jawa_last;
        $weekly_last2[] = $order_jabodetabek_last;

        $data_tiket = [
            'params' => $params,
            'this_week' => "$startOfThisWeek - $endOfThisWeek",
            'last_week' => "$startOfLastWeek - $endOfLastWeek",
            'weekly' => $weekly,
            'weekly2' => $weekly2,
            'weekly_last' => $weekly_last,
            'weekly_last2' => $weekly_last2,
        ];
        return $data_tiket;
    }

    public function pendapatan_yearly()
    {
        $params = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "August", "September", "October", "November", "Desember"];
        $thisYear  = Carbon::now()->startOfYear()->format('Y');
        $lastYear  = Carbon::now()->subYear()->startOfYear()->format('Y');

        for ($i = 0; $i < 12; $i++) {
            $start    =  Carbon::now()->startOfYear()->addMonth($i);
            $last    =  Carbon::now()->subYear()->startOfYear()->addMonth($i);
            $order_jawa[]       = OrderPriceDistribution::whereHas('order', function ($q) use ($start) {
                $q->whereYear('reserve_at', '=', $start)->whereMonth('reserve_at', '=', $start)->whereHas('route', function ($y) {
                    $y->where('area_id', 1);
                });
            })->get()->pluck('for_owner')->sum();
            $order_jawa_last[]  = OrderPriceDistribution::whereHas('order', function ($q) use ($last) {
                $q->whereYear('reserve_at', '=', $last)->whereMonth('reserve_at', '=', $last)->whereHas('route', function ($y) {
                    $y->where('area_id', 1);
                });
            })->get()->pluck('for_owner')->sum();
            $order_jabodetabek[]       = OrderPriceDistribution::whereHas('order', function ($q) use ($start) {
                $q->whereYear('reserve_at', '=', $start)->whereMonth('reserve_at', '=', $start)->whereHas('route', function ($y) {
                    $y->where('area_id', 2);
                });
            })->get()->pluck('for_owner')->sum();
            $order_jabodetabek_last[]  = OrderPriceDistribution::whereHas('order', function ($q) use ($last) {
                $q->whereYear('reserve_at', '=', $last)->whereMonth('reserve_at', '=', $last)->whereHas('route', function ($y) {
                    $y->where('area_id', 2);
                });
            })->get()->pluck('for_owner')->sum();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;
        $weekly_last[] = $order_jawa_last;
        $weekly_last2[] = $order_jabodetabek_last;

        $data_week = [
            'last_week' => "$thisYear",
            'this_week' => "$lastYear",
            'params' => $params,
            'weekly' => $weekly,
            'weekly2' => $weekly2,
            'weekly_last' => $weekly_last,
            'weekly_last2' => $weekly_last2,
        ];
        return $data_week;
    }
    public function pendapatan_monthly()
    {
        for ($i = 0; $i < 31; $i++) {
            $params[] = $i + 1;
        }
        $period = CarbonPeriod::create(Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d'));
        $period_last = CarbonPeriod::create(Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'), Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'));
        $dates = $period->count();
        $dates_last = $period_last->count();

        $thisMonth  = Carbon::now()->startOfMonth()->format('F Y');
        $lastMonth  = Carbon::now()->subMonth()->startOfMonth()->format('F Y');
        for ($i = 0; $i < $dates; $i++) {
            $startOfWeek        = Carbon::now()->startOfMonth()->addDay($i);
            $order_jawa[]       = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfWeek) {
                $q->where('reserve_at', '=', $startOfWeek)->whereHas('route', function ($y) {
                    $y->where('area_id', 1);
                });
            })->get()->pluck('for_owner')->sum();
            $order_jabodetabek[]       = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfWeek) {
                $q->where('reserve_at', '=', $startOfWeek)->whereHas('route', function ($y) {
                    $y->where('area_id', 2);
                });
            })->get()->pluck('for_owner')->sum();
        }
        for ($i = 0; $i < $dates_last; $i++) {
            $startOfLastWeek = Carbon::now()->subMonth()->startOfMonth()->addDay($i);
            $order_jawa_last[] = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfLastWeek) {
                $q->where('reserve_at', '=', $startOfLastWeek)->whereHas('route', function ($y) {
                    $y->where('area_id', 1);
                });
            })->get()->pluck('for_owner')->sum();
            $order_jabodetabek_last[]       = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfWeek) {
                $q->where('reserve_at', '=', $startOfWeek)->whereHas('route', function ($y) {
                    $y->where('area_id', 2);
                });
            })->get()->pluck('for_owner')->sum();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;
        $weekly_last[] = $order_jawa_last;
        $weekly_last2[] = $order_jabodetabek_last;

        $data_week = [
            'last_week' => "$lastMonth",
            'this_week' => "$thisMonth",
            'params' => $params,
            'weekly' => $weekly,
            'weekly2' => $weekly2,
            'weekly_last' => $weekly_last,
            'weekly_last2' => $weekly_last2,
        ];
        return $data_week;
    }
    public function pendapatan_weekly()
    {
        $params = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
        $startOfThisWeek  = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfThisWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');
        for ($i = 0; $i < 7; $i++) {
            $startOfWeek  = Carbon::now()->startOfWeek()->addDay($i);
            $startOfLastWeeks = Carbon::now()->subWeek()->startOfWeek()->addDay($i);
            $order_jawa[] = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfWeek) {
                $q->where('reserve_at', '=', $startOfWeek)->whereHas('route', function ($y) {
                    $y->where('area_id', 1);
                });
            })->get()->pluck('for_owner')->sum();
            $order_jawa_last[] = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfLastWeeks) {
                $q->where('reserve_at', '=', $startOfLastWeeks)->whereHas('route', function ($y) {
                    $y->where('area_id', 1);
                });
            })->get()->pluck('for_owner')->sum();
            $order_jabodetabek[] = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfWeek) {
                $q->where('reserve_at', '=', $startOfWeek)->whereHas('route', function ($y) {
                    $y->where('area_id', 2);
                });
            })->get()->pluck('for_owner')->sum();
            $order_jabodetabek_last[] = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfLastWeeks) {
                $q->where('reserve_at', '=', $startOfLastWeeks)->whereHas('route', function ($y) {
                    $y->where('area_id', 2);
                });
            })->get()->pluck('for_owner')->sum();
        }
        $weekly[] = $order_jawa;
        $weekly_last[] = $order_jawa_last;
        $weekly2[] = $order_jabodetabek;
        $weekly_last2[] = $order_jabodetabek_last;

        $data_week = [
            'params' => $params,
            'last_week' => "$startOfLastWeek - $endOfLastWeek",
            'this_week' => "$startOfThisWeek - $endOfThisWeek",
            'weekly' => $weekly,
            'weekly_last' => $weekly_last,
            'weekly2' => $weekly2,
            'weekly_last2' => $weekly_last2,
        ];
        return $data_week;
    }
    public function weekly()
    {
        $params = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
        // $currentDate = Carbon::now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $startOfLastWeek  = Carbon::now()->startOfWeek()->addDay($i);
            $order_jawa[] = Order::whereHas('route', function ($q) {
                $q->where('area_id', '1');
            })->whereDate('reserve_at', '=', $startOfLastWeek)->where('status', 'PAID')->get()->count();
            $order_jabodetabek[] = Order::whereHas('route', function ($q) {
                $q->where('area_id', '2');
            })->whereDate('reserve_at', '=', $startOfLastWeek)->where('status', 'PAID')->get()->count();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;

        $data = [
            'params' => $params,
            'weekly' => $weekly,
            'weekly2' => $weekly2
        ];
        return $data;
    }
    public function monthly()
    {
        $params = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "August", "September", "October", "November", "Desember"];

        for ($i = 0; $i < 12; $i++) {
            $start    =  Carbon::now()->startOfYear()->addMonth($i)->format('Y-m-d');
            $end      =  Carbon::now()->startOfYear()->endOfMonth()->addMonth($i)->format('Y-m-d');
            $order_jawa[] = Order::whereHas('route', function ($q) {
                $q->where('area_id', '1');
            })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->where('status', 'PAID')->get()->count();
            $order_jabodetabek[] = Order::whereHas('route', function ($q) {
                $q->where('area_id', '2');
            })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->where('status', 'PAID')->get()->count();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;

        $data = [
            'params' => $params,
            'weekly' => $weekly,
            'weekly2' => $weekly2
        ];
        return $data;
    }
    public function yearly()
    {
        for ($i = 0; $i < 10; $i++) {
            $year[] = Carbon::now()->startOfDecade()->addYear($i)->format('Y');
        }

        for ($i = 0; $i < 10; $i++) {
            $start          = Carbon::now()->startOfDecade()->addYear($i)->format('Y');
            $order_jawa[]   = Order::whereHas('route', function ($q) {
                $q->where('area_id', '1');
            })->whereYear('reserve_at', '=', $start)->where('status', 'PAID')->get()->count();
            $order_jabodetabek[]   = Order::whereHas('route', function ($q) {
                $q->where('area_id', '2');
            })->whereYear('reserve_at', '=', $start)->where('status', 'PAID')->get()->count();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;

        $data = [
            'params' => $year,
            'weekly' => $weekly,
            'weekly2' => $weekly2
        ];
        return $data;
    }
}
