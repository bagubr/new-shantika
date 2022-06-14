<?php

namespace App\Http\Controllers;

use App\Http\Requests\FleetRoute\CreateFleetRouteRequest;
use App\Http\Requests\FleetRoute\UpdateFleetRouteRequest;
use App\Models\Agency;
use App\Models\Area;
use App\Models\BlockedChair;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\FleetRoute;
use App\Models\Layout;
use App\Models\Order;
use App\Models\Route;
use App\Repositories\FleetRepository;
use Illuminate\Http\Request;

class FleetRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $area_id = $request->area_id;
        $fleet_id = $request->fleet_id;

        $fleet_routes = FleetRoute::when($area_id, function ($query) use ($area_id)
        {
            $query->whereHas('route.checkpoints', function ($q) use ($area_id) {
                $q->whereHas('agency.city', function ($sq) use ($area_id) {
                    $sq->where('area_id', '!=', $area_id);
                });
            });
        })->when($fleet_id, function ($query) use ($fleet_id)
        {
            $query->whereHas('fleet_detail.fleet', function ($q) use ($fleet_id) {
                $q->where('fleet_id', $fleet_id);
            });
        })->withCount('blocked_chairs')->orderBy('id', 'desc')->paginate(10);
        
        $areas = Area::get();
        $statuses = Agency::status();
        $fleets = FleetRepository::all();

        return view('fleetroute.index', compact('fleet_routes', 'statuses', 'areas', 'fleets', 'area_id', 'fleet_id'));
    }

    public function search(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $routes = Route::all();
        $fleet_details = FleetDetail::all();
        $statuses = Agency::status();
        return view('fleetroute.create', compact('fleet_details', 'routes', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFleetRouteRequest $request)
    {
        $data = $request->all();
        FleetRoute::create($data);
        return redirect()->route('fleet_route.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FleetRoute $fleet_route)
    {
        $orders = Order::where('fleet_route_id', $fleet_route->id)->paginate(10);
        $statuses = Agency::status();
        return view('fleetroute.show', compact('fleet_route', 'statuses', 'orders'));
    }
    // public function search(Request $request)
    // {
    //     $date_from = $request->date_from;
    //     $date_to = $request->date_to;

    //     return view('fleetroute.show', compact('fleet_route', 'statuses', 'orders'));
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FleetRoute $fleet_route)
    {
        $statuses = Agency::status();
        return view('fleetroute.edit', compact('fleet_route', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFleetRouteRequest $request, FleetRoute $fleet_route)
    {
        $data = $request->all();
        $fleet_route->update($data);
        session()->flash('success', 'Rute Armada Berhasil Di Ubah');
        return redirect()->back();
    }
    public function update_status(Request $request, FleetRoute $fleet_route)
    {
        $fleet_route->update([
            'is_active' => $request->is_active,
        ]);
        session()->flash('success', 'Status Rute Armada Berhasil Diubah');
        return redirect()->back();
    }

    public function blockedChairs(FleetRoute $fleet_route)
    {
        $fleet_route->load('fleet_detail.fleet.layout.chairs');
        $data['blocked_chairs'] = BlockedChair::where('fleet_route_id', $fleet_route->id)->get();
        $data['layout'] = $fleet_route->fleet_detail?->fleet?->layout;
        $data['layout']->chairs = $data['layout']->chairs->map(function ($e) use ($data) {
            $e->is_blocked = in_array($e->id, $data['blocked_chairs']->pluck('layout_chair_id')->toArray());
            return $e;
        });
        $data['fleet_route'] = $fleet_route;
        return view('fleetroute.blocked_chairs', $data);
    }

    public function updateBlockedChairs(FleetRoute $fleet_route, int $layout_chair_id)
    {
        $block_chair = BlockedChair::where('fleet_route_id', $fleet_route->id)->where('layout_chair_id', $layout_chair_id)->first();

        if (empty($block_chair)) {
            BlockedChair::create([
                'fleet_route_id' => $fleet_route->id,
                'layout_chair_id' => $layout_chair_id
            ]);
        } else {
            $block_chair->delete();
        }

        $this->sendSuccessResponse([
            'is_blocked' => BlockedChair::where('fleet_route_id', $fleet_route->id)->where('layout_chair_id', $layout_chair_id)->exists()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FleetRoute $fleetRoute)
    {
        $fleetRoute->delete();
        session()->flash('success', 'Armada Rute berhasil dihapus');
        return redirect()->back();
    }

    public function getFleetRoutes(Request $request)
    {
        $fleet_route = FleetRoute::find(request()->fleet_route_id);
        $fleet_routes = FleetRoute::has('fleet_detail_without_trash')->with(['route', 'fleet_detail.fleet.fleetclass'])->where('route_id', $fleet_route->route_id)->get();

        return response(['data' => $fleet_routes, 'code' => 1], 200);

    }
}
