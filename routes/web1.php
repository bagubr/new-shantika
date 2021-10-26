<?php

use App\Events\SendingNotification;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgencyPriceController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckpointController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ConfigSettingController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dashboard2Controller;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FleetClassController;
use App\Http\Controllers\FleetClassPriceController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\FleetRouteController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPriceDistributionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\ScheduleNotOperateController;
use App\Http\Controllers\ScheduleUnavailableBookingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\TermsAndConditionController;
use App\Http\Controllers\TestimoniController;
use App\Http\Controllers\TimeClassificationController;
use App\Http\Controllers\UserAgentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OutcomeController;
use App\Http\Controllers\SketchController;
use App\Http\Controllers\StatusPenumpangController;
use App\Http\Controllers\FleetDetailController;
use App\Http\Controllers\FleetRoutePriceController;
use App\Http\Controllers\RestaurantBarcodeController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SketchLogController;
use App\Jobs\Notification\TicketExchangedJob;
use App\Jobs\PaymentAcceptedNotificationJob;
use App\Models\Admin;
use App\Models\FoodRedeemHistory;
use App\Models\Notification;
use App\Models\Order;
use App\Services\OrderService;
use App\Utils\NotificationMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

Route::get('/test', function () {
    $order_id = Order::find(231);
    $payload = NotificationMessage::paymentSuccess($order_id->code_order);
    $notification = Notification::build(
        $payload[0],
        $payload[1],
        Notification::TYPE1,
        $order_id->id,
        $order_id->user_id
    );
    PaymentAcceptedNotificationJob::dispatchAfterResponse($notification, $order_id->user?->fcm_token, true);
});

Route::get('_/privacy_policy', [LoginController::class, 'privacyPolicy'])->name('_privacy_policy');
Route::post('admin/store/fcm_token', [LoginController::class, 'storeFcmToken']);

// restaurant
Route::get('restaurant_barcode/search/code_order', [RestaurantBarcodeController::class, 'getOrderId'])->name('restaurant_barcode.getOrderId');
// end of restaurant

Route::group(['middleware' => ['auth']], function () {
    //dashboard
    Route::get('/', [Dashboard2Controller::class, 'index'])->name('dashboard');
    Route::post('dashboard/dashboard', [Dashboard2Controller::class, 'statistic'])->name('dashboard.statistic');
    Route::post('dashboard/agent', [Dashboard2Controller::class, 'statisticAgent'])->name('dashboard.agent');
    Route::post('dashboard/sum', [Dashboard2Controller::class, 'statisticSum'])->name('dashboard.sum');
    // end of dashboard

    Route::get('notification/{id}', [AdminNotificationController::class, 'show']);

    // fleet
    Route::get('fleet', [FleetController::class, 'index'])->name('fleets.index');
    Route::post('fleet', [FleetController::class, 'store'])->name('fleets.store');
    Route::get('fleet/{fleet}', [FleetController::class, 'show'])->name('fleets.show');
    Route::put('fleet/{fleet}', [FleetController::class, 'update'])->name('fleets.update');
    // end of fleet

    // fleetclass
    Route::get('fleetclass', [FleetClassController::class, 'index'])->name('fleetclass.index');
    Route::post('fleetclass', [FleetClassController::class, 'store'])->name('fleetclass.store');
    Route::get('fleetclass/{fleetclass}', [FleetClassController::class, 'show'])->name('fleetclass.show');
    Route::put('fleetclass/{fleetclass}', [FleetClassController::class, 'update'])->name('fleetclass.update');
    // end of fleetclass

    //layouts
    Route::get('layouts', [LayoutController::class, 'index'])->name('layouts.index');
    Route::post('layouts', [LayoutController::class, 'store'])->name('layouts.store');
    Route::get('layouts/{layouts}', [LayoutController::class, 'show'])->name('layouts.show');
    Route::put('layouts/{layouts}', [LayoutController::class, 'update'])->name('layouts.update');
    //end of layouts

    //fleet_detail
    Route::put('fleet_detail/{fleet_detail}', [FleetDetailController::class, 'update'])->name('fleet_detail.update');
    Route::get('fleet_detail/{fleet_detail}/edit', [FleetDetailController::class, 'edit'])->name('fleet_detail.edit');
    Route::get('fleet_detail/create', [FleetDetailController::class, 'create'])->name('fleet_detail.create');
    Route::delete('fleet_detail/{fleet_detail}', [FleetDetailController::class, 'destroy'])->name('fleet_detail.destroy');
    Route::get('fleet_detail', [FleetDetailController::class, 'index'])->name('fleet_detail.index');
    Route::post('fleet_detail', [FleetDetailController::class, 'store'])->name('fleet_detail.store');
    Route::get('fleet_detail/{fleet_detail}', [FleetDetailController::class, 'show'])->name('fleet_detail.show');
    //end of fleet_detail

    //fleet_class_price
    Route::get('fleet_class_price', [FleetClassPriceController::class, 'index'])->name('fleet_class_price.index');
    Route::post('fleet_class_price', [FleetClassPriceController::class, 'store'])->name('fleet_class_price.store');
    Route::get('fleet_class_price/{fleet_class_price}', [FleetClassPriceController::class, 'show'])->name('fleet_class_price.show');
    Route::put('fleet_class_price/{fleet_class_price}', [FleetClassPriceController::class, 'update'])->name('fleet_class_price.update');
    //end of fleet_class_price

    //fleet_route_prices
    Route::get('fleet_route_prices', [FleetRoutePriceController::class, 'index'])->name('fleet_route_prices.index');
    Route::post('fleet_route_prices', [FleetRoutePriceController::class, 'store'])->name('fleet_route_prices.store');
    Route::get('fleet_route_prices/{fleet_route_prices}', [FleetRoutePriceController::class, 'show'])->name('fleet_route_prices.show');
    Route::put('fleet_route_prices/{fleet_route_prices}', [FleetRoutePriceController::class, 'update'])->name('fleet_route_prices.update');
    Route::get('fleet_route_prices/search', [FleetRoutePriceController::class, 'search'])->name('fleet_route_prices.search');
    //end of fleet_route_prices

    //routes
    Route::get('route', [RoutesController::class, 'index'])->name('routes.index');
    Route::post('route', [RoutesController::class, 'store'])->name('routes.store');
    Route::get('route/{route}', [RoutesController::class, 'show'])->name('routes.show');
    Route::put('route/{route}', [RoutesController::class, 'update'])->name('routes.update');
    Route::get('route/search', [RoutesController::class, 'search'])->name('routes.search');
    Route::post('route/fleet/store/', [RoutesController::class, 'store_fleet'])->name('route.fleet.store');
    //end of routes

    //agency
    Route::get('agency', [AgencyController::class, 'index'])->name('agency.index');
    Route::post('agency', [AgencyController::class, 'store'])->name('agency.store');
    Route::get('agency/{agency}', [AgencyController::class, 'show'])->name('agency.show');
    Route::put('agency/{agency}', [AgencyController::class, 'update'])->name('agency.update');
    Route::get('agency/all', [AgencyController::class, 'get_agency'])->name('agency.get_agency');
    Route::get('agency/search', [AgencyController::class, 'search'])->name('agency.search');
    Route::put('agency/update_status/{agency}', [AgencyController::class, 'update_status'])->name('agency.update_status');
    //end of agency

    //checkpoint

    Route::get('checkpoint', [CheckpointController::class, 'index'])->name('checkpoint.index');
    Route::post('checkpoint', [CheckpointController::class, 'store'])->name('checkpoint.store');
    Route::get('checkpoint/{checkpoint}', [CheckpointController::class, 'show'])->name('checkpoint.show');
    Route::put('checkpoint/{checkpoint}', [CheckpointController::class, 'update'])->name('checkpoint.update');
    //end of checkpoint

    //user
    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::post('user', [UserController::class, 'store'])->name('user.store');
    Route::get('user/{user}', [UserController::class, 'show'])->name('user.show');
    Route::put('user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::put('user/update_status/{user}', [UserController::class, 'update_status'])->name('user.update_status');
    //end of user

    //user_agent
    Route::get('user_agent', [UserAgentController::class, 'index'])->name('user_agent.index');
    Route::post('user_agent', [UserAgentController::class, 'store'])->name('user_agent.store');
    Route::get('user_agent/{user_agent}', [UserAgentController::class, 'show'])->name('user_agent.show');
    Route::put('user_agent/{user_agent}', [UserAgentController::class, 'update'])->name('user_agent.update');
    Route::get('user_agent/search', [UserAgentController::class, 'search'])->name('user_agent.search');
    Route::put('user_agent/update_status/{user_agent}', [UserAgentController::class, 'update_status'])->name('user_agent.update_status');
    //end of user_agent

    //time_classification
    Route::get('time_classification', [TimeClassificationController::class, 'index'])->name('time_classification.index');
    Route::post('time_classification', [TimeClassificationController::class, 'store'])->name('time_classification.store');
    Route::get('time_classification/{time_classification}', [TimeClassificationController::class, 'show'])->name('time_classification.show');
    Route::put('time_classification/{time_classification}', [TimeClassificationController::class, 'update'])->name('time_classification.update');
    //end of time_classification

    //payment

    Route::get('payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('payment', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('payment/{payment}', [PaymentController::class, 'show'])->name('payment.show');
    Route::put('payment/{payment}', [PaymentController::class, 'update'])->name('payment.update');
    //end of payment

    //payment_type
    Route::get('payment_type', [PaymentTypeController::class, 'index'])->name('payment_type.index');
    Route::post('payment_type', [PaymentTypeController::class, 'store'])->name('payment_type.store');
    Route::get('payment_type/{payment_type}', [PaymentTypeController::class, 'show'])->name('payment_type.show');
    Route::put('payment_type/{payment_type}', [PaymentTypeController::class, 'update'])->name('payment_type.update');
    //end of payment_type

    //area
    Route::get('area', [AreaController::class, 'index'])->name('area.index');
    Route::post('area', [AreaController::class, 'store'])->name('area.store');
    Route::get('area/{area}', [AreaController::class, 'show'])->name('area.show');
    Route::put('area/{area}', [AreaController::class, 'update'])->name('area.update');
    //end of area

    //province
    Route::get('province', [ProvinceController::class, 'index'])->name('province.index');
    Route::post('province', [ProvinceController::class, 'store'])->name('province.store');
    Route::get('province/{province}', [ProvinceController::class, 'show'])->name('province.show');
    Route::put('province/{province}', [ProvinceController::class, 'update'])->name('province.update');
    //end of province

    //city
    Route::get('city', [CityController::class, 'index'])->name('city.index');
    Route::post('city', [CityController::class, 'store'])->name('city.store');
    Route::get('city/{city}', [CityController::class, 'show'])->name('city.show');
    Route::put('city/{city}', [CityController::class, 'update'])->name('city.update');
    //end of city

    //order
    Route::get('order', [OrderController::class, 'index'])->name('order.index');
    Route::post('order', [OrderController::class, 'store'])->name('order.store');
    Route::get('order/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::put('order/{order}', [OrderController::class, 'update'])->name('order.update');

    Route::get('order/search', [OrderController::class, 'search'])->name('order.search');
    Route::get('order/find/{code_order}', [OrderController::class, 'showByCodeOrder'])->name('order.show.code_order');
    //end of order

    //member
    Route::get('member', [MemberController::class, 'index'])->name('member.index');
    Route::post('member', [MemberController::class, 'store'])->name('member.store');
    Route::get('member/{member}', [MemberController::class, 'show'])->name('member.show');
    Route::put('member/{member}', [MemberController::class, 'update'])->name('member.update');
    Route::get('member/search', [MemberController::class, 'search'])->name('member.search');
    //end of member

    //order_price_distribution

    Route::get('order_price_distribution', [OrderPriceDistributionController::class, 'index'])->name('order_price_distribution.index');
    Route::post('order_price_distribution', [OrderPriceDistributionController::class, 'store'])->name('order_price_distribution.store');
    Route::get('order_price_distribution/{order_price_distribution}', [OrderPriceDistributionController::class, 'show'])->name('order_price_distribution.show');
    Route::put('order_price_distribution/{order_price_distribution}', [OrderPriceDistributionController::class, 'update'])->name('order_price_distribution.update');
    Route::get('order_price_distribution/search', [OrderPriceDistributionController::class, 'search'])->name('order_price_distribution.search');
    Route::get('order_price_distribution/export', [OrderPriceDistributionController::class, 'export'])->name('order_price_distribution.export');
    //end of order_price_distribution

    //outcome
    Route::get('outcome', [OutcomeController::class, 'index'])->name('outcome.index');
    Route::post('outcome', [OutcomeController::class, 'store'])->name('outcome.store');
    Route::get('outcome/{outcome}', [OutcomeController::class, 'show'])->name('outcome.show');
    Route::put('outcome/{outcome}', [OutcomeController::class, 'update'])->name('outcome.update');
    Route::post('outcome/statistic', [OutcomeController::class, 'statistic'])->name('outcome_statistic');
    Route::get('outcome/search', [OutcomeController::class, 'search'])->name('outcome.search');
    Route::get('outcome/export/{id}', [OutcomeController::class, 'export'])->name('outcome.export');
    //end of outcome

    //sketch
    Route::get('sketch', [SketchController::class, 'index'])->name('sketch.index');
    Route::post('sketch', [SketchController::class, 'store'])->name('sketch.store');
    // Route::get('sketch/{sketch}', [SketchController::class, 'show'])->name('sketch.show');
    Route::put('sketch/{sketch}', [SketchController::class, 'update'])->name('sketch.update');
    Route::get('sketch/orders', [SketchController::class, 'getDeparturingOrders']);
    Route::get('sketch/orders/detail', [SketchController::class, 'getAvailibilityChairs']);
    Route::get('sketch/export', [SketchController::class, 'export']);
    Route::post('sketch/store', [SketchController::class, 'store']);
    Route::get('sketch/log', [SketchLogController::class, 'index']);
    //end of sketch

    //fleet_route

    Route::get('fleet_route', [FleetRouteController::class, 'index'])->name('fleet_route.index');
    Route::post('fleet_route', [FleetRouteController::class, 'store'])->name('fleet_route.store');
    Route::get('fleet_route/{fleet_route}', [FleetRouteController::class, 'show'])->name('fleet_route.show');
    Route::put('fleet_route/{fleet_route}', [FleetRouteController::class, 'update'])->name('fleet_route.update');
    Route::get('fleet_route/search', [FleetRouteController::class, 'search'])->name('fleet_route.search');
    Route::put('fleet_route/update_status/{fleet_route}', [FleetRouteController::class, 'update_status'])->name('fleet_route.update_status');
    //end of fleet_route

    //status_penumpang

    Route::get('status_penumpang', [StatusPenumpangController::class, 'index'])->name('status_penumpang.index');
    Route::post('status_penumpang', [StatusPenumpangController::class, 'store'])->name('status_penumpang.store');
    Route::get('status_penumpang/{status_penumpang}', [StatusPenumpangController::class, 'show'])->name('status_penumpang.show');
    Route::put('status_penumpang/{status_penumpang}', [StatusPenumpangController::class, 'update'])->name('status_penumpang.update');
    Route::get('status_penumpang/search', [StatusPenumpangController::class, 'search'])->name('status_penumpang.search');
    Route::get('status_penumpang/export/', [StatusPenumpangController::class, 'export'])->name('status_penumpang.export');
    //end of status_penumpang

    //agency_price
    Route::get('agency_price', [AgencyPriceController::class, 'index'])->name('agency_price.index');
    Route::post('agency_price', [AgencyPriceController::class, 'store'])->name('agency_price.store');
    Route::get('agency_price/{agency_price}', [AgencyPriceController::class, 'show'])->name('agency_price.show');
    Route::put('agency_price/{agency_price}', [AgencyPriceController::class, 'update'])->name('agency_price.update');
    //end of agency_price

    //restaurant
    Route::get('restaurant', [RestaurantController::class, 'index'])->name('restaurant.index');
    Route::get('restaurant/create', [RestaurantController::class, 'create'])->name('restaurant.create');
    Route::post('restaurant', [RestaurantController::class, 'store'])->name('restaurant.store');
    Route::get('restaurant/{restaurant}', [RestaurantController::class, 'show'])->name('restaurant.show');
    Route::get('restaurant/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('restaurant.edit');
    Route::put('restaurant/{restaurant}', [RestaurantController::class, 'update'])->name('restaurant.update');
    Route::delete('restaurant/{restaurant}', [RestaurantController::class, 'destroy'])->name('restaurant.destroy');
    Route::get('restaurant/history/all', [RestaurantController::class, 'history_restaurant'])->name('r.history_restaurant');
    Route::get('restaurant/history/all/search', [RestaurantController::class, 'history_restaurant_search'])->name('r.history_restaurant_search');
    Route::post('restaurant/assign', [RestaurantController::class, 'assign_user'])->name('restaurant.assign_user');
    Route::delete('restaurant/admin/delete/{restaurant_admin}', [RestaurantController::class, 'destroy_admin'])->name('restaurant.destroy_admin');
    //end of restaurant

    // crud
    Route::group(['middleware' => ['role:superadmin|keuangan|restaurant|ticketing']], function () {

        Route::get('fleet/create', [FleetController::class, 'create'])->name('fleets.create');
        Route::get('fleet/{fleet}/edit', [FleetController::class, 'edit'])->name('fleets.edit');
        Route::delete('fleet/{fleet}', [FleetController::class, 'destroy'])->name('fleets.destroy');

        Route::get('fleetclass/create', [FleetClassController::class, 'create'])->name('fleetclass.create');
        Route::get('fleetclass/{fleetclass}/edit', [FleetClassController::class, 'edit'])->name('fleetclass.edit');
        Route::delete('fleetclass/{fleetclass}', [FleetClassController::class, 'destroy'])->name('fleetclass.destroy');

        Route::get('layouts/create', [LayoutController::class, 'create'])->name('layouts.create');
        Route::get('layouts/{layouts}/edit', [LayoutController::class, 'edit'])->name('layouts.edit');
        Route::delete('layouts/{layouts}', [LayoutController::class, 'destroy'])->name('layouts.destroy');

        Route::get('fleet_class_price/create', [FleetClassPriceController::class, 'create'])->name('fleet_class_price.create');
        Route::get('fleet_class_price/{fleet_class_price}/edit', [FleetClassPriceController::class, 'edit'])->name('fleet_class_price.edit');
        Route::delete('fleet_class_price/{fleet_class_price}', [FleetClassPriceController::class, 'destroy'])->name('fleet_class_price.destroy');
        Route::get('fleet_route_prices/create', [FleetRoutePriceController::class, 'create'])->name('fleet_route_prices.create');
        Route::get('fleet_route_prices/{fleet_route_prices}/edit', [FleetRoutePriceController::class, 'edit'])->name('fleet_route_prices.edit');
        Route::delete('fleet_route_prices/{fleet_route_prices}', [FleetRoutePriceController::class, 'destroy'])->name('fleet_route_prices.destroy');
        Route::get('route/create', [RoutesController::class, 'create'])->name('routes.create');
        Route::get('route/{route}/edit', [RoutesController::class, 'edit'])->name('routes.edit');
        Route::delete('route/{route}', [RoutesController::class, 'destroy'])->name('routes.destroy');
        Route::get('agency/create', [AgencyController::class, 'create'])->name('agency.create');
        Route::get('agency/{agency}/edit', [AgencyController::class, 'edit'])->name('agency.edit');
        Route::delete('agency/{agency}', [AgencyController::class, 'destroy'])->name('agency.destroy');
        Route::get('checkpoint/create', [CheckpointController::class, 'create'])->name('checkpoint.create');
        Route::get('checkpoint/{checkpoint}/edit', [CheckpointController::class, 'edit'])->name('checkpoint.edit');
        Route::delete('checkpoint/{checkpoint}', [CheckpointController::class, 'destroy'])->name('checkpoint.destroy');
        Route::get('agency_price/create', [AgencyPriceController::class, 'create'])->name('agency_price.create');
        Route::get('agency_price/{agency_price}/edit', [AgencyPriceController::class, 'edit'])->name('agency_price.edit');
        Route::delete('agency_price/{agency_price}', [AgencyPriceController::class, 'destroy'])->name('agency_price.destroy');
        Route::get('status_penumpang/create', [StatusPenumpangController::class, 'create'])->name('status_penumpang.create');
        Route::get('status_penumpang/{status_penumpang}/edit', [StatusPenumpangController::class, 'edit'])->name('status_penumpang.edit');
        Route::delete('status_penumpang/{status_penumpang}', [StatusPenumpangController::class, 'destroy'])->name('status_penumpang.destroy');
        Route::get('fleet_route/create', [FleetRouteController::class, 'create'])->name('fleet_route.create');
        Route::get('fleet_route/{fleet_route}/edit', [FleetRouteController::class, 'edit'])->name('fleet_route.edit');
        Route::delete('fleet_route/{fleet_route}', [FleetRouteController::class, 'destroy'])->name('fleet_route.destroy');
        Route::get('fleet_route/blocked_chair/{fleet_route}', [FleetRouteController::class, 'blockedChairs'])->name('fleet_route.blocked_chair');
        Route::put('fleet_route/block_chair/{fleet_route}/{layout_chair_id}', [FleetRouteController::class, 'updateBlockedChairs']);
        Route::get('sketch/create', [SketchController::class, 'create'])->name('sketch.create');
        Route::get('sketch/{sketch}/edit', [SketchController::class, 'edit'])->name('sketch.edit');
        Route::delete('sketch/{sketch}', [SketchController::class, 'destroy'])->name('sketch.destroy');
        Route::get('outcome/create', [OutcomeController::class, 'create'])->name('outcome.create');
        Route::get('outcome_type/create', [OutcomeController::class, 'createType'])->name('outcome_type.create');
        Route::get('outcome/{outcome}/edit', [OutcomeController::class, 'edit'])->name('outcome.edit');
        Route::delete('outcome/{outcome}', [OutcomeController::class, 'destroy'])->name('outcome.destroy');
        Route::post('outcome_type', [OutcomeController::class, 'storeType'])->name('outcome_type.store');
        Route::delete('outcome_type/{id}', [OutcomeController::class, 'destroyType'])->name('outcome_type.destroy');
        Route::get('order_price_distribution/create', [OrderPriceDistributionController::class, 'create'])->name('order_price_distribution.create');
        Route::get('order_price_distribution/{order_price_distribution}/edit', [OrderPriceDistributionController::class, 'edit'])->name('order_price_distribution.edit');
        Route::delete('order_price_distribution/{order_price_distribution}', [OrderPriceDistributionController::class, 'destroy'])->name('order_price_distribution.destroy');
        Route::get('member/create', [MemberController::class, 'create'])->name('member.create');
        Route::get('member/{member}/edit', [MemberController::class, 'edit'])->name('member.edit');
        Route::delete('member/{member}', [MemberController::class, 'destroy'])->name('member.destroy');
        Route::post('member/import', [MemberController::class, 'import'])->name('member.import');
        Route::get('order/create', [OrderController::class, 'create'])->name('order.create');
        Route::get('order/{order}/edit', [OrderController::class, 'edit'])->name('order.edit');
        Route::delete('order/{order}', [OrderController::class, 'destroy'])->name('order.destroy');
        Route::put('order/update_jadwal/{order}', [OrderController::class, 'update_jadwal'])->name('order.update_jadwal');
        Route::put('order/cancelation/{order_detail}', [OrderController::class, 'cancelation'])->name('order.cancelation');
        Route::get('time_classification/create', [TimeClassificationController::class, 'create'])->name('time_classification.create');
        Route::delete('time_classification/{time_classification}', [TimeClassificationController::class, 'destroy'])->name('time_classification.destroy');
        Route::get('time_classification/{time_classification}/edit', [TimeClassificationController::class, 'edit'])->name('time_classification.edit');
        Route::get('payment/create', [PaymentController::class, 'create'])->name('payment.create');
        Route::get('payment/{payment}/edit', [PaymentController::class, 'edit'])->name('payment.edit');
        Route::delete('payment/{payment}', [PaymentController::class, 'destroy'])->name('payment.destroy');
        Route::get('city/create', [CityController::class, 'create'])->name('city.create');
        Route::get('city/{city}/edit', [CityController::class, 'edit'])->name('city.edit');
        Route::delete('city/{city}', [CityController::class, 'destroy'])->name('city.destroy');

        Route::get('province/create', [ProvinceController::class, 'create'])->name('province.create');
        Route::get('province/{province}/edit', [ProvinceController::class, 'edit'])->name('province.edit');
        Route::delete('province/{province}', [ProvinceController::class, 'destroy'])->name('province.destroy');

        Route::get('area/create', [AreaController::class, 'create'])->name('area.create');
        Route::get('area/{area}/edit', [AreaController::class, 'edit'])->name('area.edit');
        Route::delete('area/{area}', [AreaController::class, 'destroy'])->name('area.destroy');
        Route::get('payment_type/create', [PaymentTypeController::class, 'create'])->name('payment_type.create');
        Route::get('payment_type/{payment_type}/edit', [PaymentTypeController::class, 'edit'])->name('payment_type.edit');
        Route::delete('payment_type/{payment_type}', [PaymentTypeController::class, 'destroy'])->name('payment_type.destroy');
        Route::get('user_agent/create', [UserAgentController::class, 'create'])->name('user_agent.create');
        Route::get('user_agent/{user_agent}/edit', [UserAgentController::class, 'edit'])->name('user_agent.edit');
        Route::delete('user_agent/{user_agent}', [UserAgentController::class, 'destroy'])->name('user_agent.destroy');
        Route::get('user/create', [UserController::class, 'create'])->name('user.create');
        Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::delete('user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    });
    // end of crud

    Route::group(['middleware' => ['role:superadmin']], function () {
        //information
        Route::get('information', [InformationController::class, 'index'])->name('information.index');
        Route::get('information/create', [InformationController::class, 'create'])->name('information.create');
        Route::post('information', [InformationController::class, 'store'])->name('information.store');
        Route::get('information/{information}', [InformationController::class, 'show'])->name('information.show');
        Route::get('information/{information}/edit', [InformationController::class, 'edit'])->name('information.edit');
        Route::put('information/{information}', [InformationController::class, 'update'])->name('information.update');
        Route::delete('information/{information}', [InformationController::class, 'destroy'])->name('information.destroy');
        //end of information

        //faq
        Route::get('faq', [FaqController::class, 'index'])->name('faq.index');
        Route::get('faq/create', [FaqController::class, 'create'])->name('faq.create');
        Route::post('faq', [FaqController::class, 'store'])->name('faq.store');
        Route::get('faq/{faq}', [FaqController::class, 'show'])->name('faq.show');
        Route::get('faq/{faq}/edit', [FaqController::class, 'edit'])->name('faq.edit');
        Route::put('faq/{faq}', [FaqController::class, 'update'])->name('faq.update');
        Route::delete('faq/{faq}', [FaqController::class, 'destroy'])->name('faq.destroy');
        //end of faq

        //privacy_policy
        Route::get('privacy_policy', [PrivacyPolicyController::class, 'index'])->name('privacy_policy.index');
        Route::get('privacy_policy/create', [PrivacyPolicyController::class, 'create'])->name('privacy_policy.create');
        Route::post('privacy_policy', [PrivacyPolicyController::class, 'store'])->name('privacy_policy.store');
        Route::get('privacy_policy/{privacy_policy}', [PrivacyPolicyController::class, 'show'])->name('privacy_policy.show');
        Route::get('privacy_policy/{privacy_policy}/edit', [PrivacyPolicyController::class, 'edit'])->name('privacy_policy.edit');
        Route::put('privacy_policy/{privacy_policy}', [PrivacyPolicyController::class, 'update'])->name('privacy_policy.update');
        Route::delete('privacy_policy/{privacy_policy}', [PrivacyPolicyController::class, 'destroy'])->name('privacy_policy.destroy');
        //end of privacy_policy


        //chat
        Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
        Route::get('chat/create', [ChatController::class, 'create'])->name('chat.create');
        Route::post('chat', [ChatController::class, 'store'])->name('chat.store');
        Route::get('chat/{chat}', [ChatController::class, 'show'])->name('chat.show');
        Route::get('chat/{chat}/edit', [ChatController::class, 'edit'])->name('chat.edit');
        Route::put('chat/{chat}', [ChatController::class, 'update'])->name('chat.update');
        Route::delete('chat/{chat}', [ChatController::class, 'destroy'])->name('chat.destroy');
        //end of chat

        //facility
        Route::get('facility', [FacilityController::class, 'index'])->name('facility.index');
        Route::get('facility/create', [FacilityController::class, 'create'])->name('facility.create');
        Route::post('facility', [FacilityController::class, 'store'])->name('facility.store');
        Route::get('facility/{facility}', [FacilityController::class, 'show'])->name('facility.show');
        Route::get('facility/{facility}/edit', [FacilityController::class, 'edit'])->name('facility.edit');
        Route::put('facility/{facility}', [FacilityController::class, 'update'])->name('facility.update');
        Route::delete('facility/{facility}', [FacilityController::class, 'destroy'])->name('facility.destroy');
        //end of facility

        //customer_menu
        Route::get('customer_menu', [CustomerMenuController::class, 'index'])->name('customer_menu.index');
        Route::get('customer_menu/create', [CustomerMenuController::class, 'create'])->name('customer_menu.create');
        Route::post('customer_menu', [CustomerMenuController::class, 'store'])->name('customer_menu.store');
        Route::get('customer_menu/{customer_menu}', [CustomerMenuController::class, 'show'])->name('customer_menu.show');
        Route::get('customer_menu/{customer_menu}/edit', [CustomerMenuController::class, 'edit'])->name('customer_menu.edit');
        Route::put('customer_menu/{customer_menu}', [CustomerMenuController::class, 'update'])->name('customer_menu.update');
        Route::delete('customer_menu/{customer_menu}', [CustomerMenuController::class, 'destroy'])->name('customer_menu.destroy');
        //end of customer_menu

        //slider
        Route::get('slider', [SliderController::class, 'index'])->name('slider.index');
        Route::get('slider/create', [SliderController::class, 'create'])->name('slider.create');
        Route::post('slider', [SliderController::class, 'store'])->name('slider.store');
        Route::get('slider/{slider}', [SliderController::class, 'show'])->name('slider.show');
        Route::get('slider/{slider}/edit', [SliderController::class, 'edit'])->name('slider.edit');
        Route::put('slider/{slider}', [SliderController::class, 'update'])->name('slider.update');
        Route::delete('slider/{slider}', [SliderController::class, 'destroy'])->name('slider.destroy');
        //end of slider

        //testimoni
        Route::get('testimoni', [TestimoniController::class, 'index'])->name('testimoni.index');
        Route::get('testimoni/create', [TestimoniController::class, 'create'])->name('testimoni.create');
        Route::post('testimoni', [TestimoniController::class, 'store'])->name('testimoni.store');
        Route::get('testimoni/{testimoni}', [TestimoniController::class, 'show'])->name('testimoni.show');
        Route::get('testimoni/{testimoni}/edit', [TestimoniController::class, 'edit'])->name('testimoni.edit');
        Route::put('testimoni/{testimoni}', [TestimoniController::class, 'update'])->name('testimoni.update');
        Route::delete('testimoni/{testimoni}', [TestimoniController::class, 'destroy'])->name('testimoni.destroy');
        //end of testimoni

        //article
        Route::get('article', [ArticleController::class, 'index'])->name('article.index');
        Route::get('article/create', [ArticleController::class, 'create'])->name('article.create');
        Route::post('article', [ArticleController::class, 'store'])->name('article.store');
        Route::get('article/{article}', [ArticleController::class, 'show'])->name('article.show');
        Route::get('article/{article}/edit', [ArticleController::class, 'edit'])->name('article.edit');
        Route::put('article/{article}', [ArticleController::class, 'update'])->name('article.update');
        Route::delete('article/{article}', [ArticleController::class, 'destroy'])->name('article.destroy');
        //end of article

        //about
        Route::get('about', [AboutController::class, 'index'])->name('about.index');
        Route::get('about/create', [AboutController::class, 'create'])->name('about.create');
        Route::post('about', [AboutController::class, 'store'])->name('about.store');
        Route::get('about/{about}', [AboutController::class, 'show'])->name('about.show');
        Route::get('about/{about}/edit', [AboutController::class, 'edit'])->name('about.edit');
        Route::put('about/{about}', [AboutController::class, 'update'])->name('about.update');
        Route::delete('about/{about}', [AboutController::class, 'destroy'])->name('about.destroy');
        //end of about

        //social_media
        Route::get('social_media', [SocialMediaController::class, 'index'])->name('social_media.index');
        Route::get('social_media/create', [SocialMediaController::class, 'create'])->name('social_media.create');
        Route::post('social_media', [SocialMediaController::class, 'store'])->name('social_media.store');
        Route::get('social_media/{social_media}', [SocialMediaController::class, 'show'])->name('social_media.show');
        Route::get('social_media/{social_media}/edit', [SocialMediaController::class, 'edit'])->name('social_media.edit');
        Route::put('social_media/{social_media}', [SocialMediaController::class, 'update'])->name('social_media.update');
        Route::delete('social_media/{social_media}', [SocialMediaController::class, 'destroy'])->name('social_media.destroy');
        //end of social_media

        //terms_condition
        Route::get('terms_condition', [TermsAndConditionController::class, 'index'])->name('terms_condition.index');
        Route::get('terms_condition/create', [TermsAndConditionController::class, 'create'])->name('terms_condition.create');
        Route::post('terms_condition', [TermsAndConditionController::class, 'store'])->name('terms_condition.store');
        Route::get('terms_condition/{terms_condition}', [TermsAndConditionController::class, 'show'])->name('terms_condition.show');
        Route::get('terms_condition/{terms_condition}/edit', [TermsAndConditionController::class, 'edit'])->name('terms_condition.edit');
        Route::put('terms_condition/{terms_condition}', [TermsAndConditionController::class, 'update'])->name('terms_condition.update');
        Route::delete('terms_condition/{terms_condition}', [TermsAndConditionController::class, 'destroy'])->name('terms_condition.destroy');
        //end of terms_condition

        //review
        Route::get('review', [ReviewController::class, 'index'])->name('review.index');
        Route::get('review/create', [ReviewController::class, 'create'])->name('review.create');
        Route::post('review', [ReviewController::class, 'store'])->name('review.store');
        Route::get('review/{review}', [ReviewController::class, 'show'])->name('review.show');
        Route::get('review/{review}/edit', [ReviewController::class, 'edit'])->name('review.edit');
        Route::put('review/{review}', [ReviewController::class, 'update'])->name('review.update');
        Route::delete('review/{review}', [ReviewController::class, 'destroy'])->name('review.destroy');
        //end of review

        //config_setting
        Route::get('config_setting', [ConfigSettingController::class, 'index'])->name('config_setting.index');
        Route::get('config_setting/create', [ConfigSettingController::class, 'create'])->name('config_setting.create');
        Route::post('config_setting', [ConfigSettingController::class, 'store'])->name('config_setting.store');
        Route::get('config_setting/{config_setting}', [ConfigSettingController::class, 'show'])->name('config_setting.show');
        Route::get('config_setting/{config_setting}/edit', [ConfigSettingController::class, 'edit'])->name('config_setting.edit');
        Route::put('config_setting/{config_setting}', [ConfigSettingController::class, 'update'])->name('config_setting.update');
        Route::delete('config_setting/{config_setting}', [ConfigSettingController::class, 'destroy'])->name('config_setting.destroy');
        //end of config_setting

        //bank_account
        Route::get('bank_account', [BankAccountController::class, 'index'])->name('bank_account.index');
        Route::get('bank_account/create', [BankAccountController::class, 'create'])->name('bank_account.create');
        Route::post('bank_account', [BankAccountController::class, 'store'])->name('bank_account.store');
        Route::get('bank_account/{bank_account}', [BankAccountController::class, 'show'])->name('bank_account.show');
        Route::get('bank_account/{bank_account}/edit', [BankAccountController::class, 'edit'])->name('bank_account.edit');
        Route::put('bank_account/{bank_account}', [BankAccountController::class, 'update'])->name('bank_account.update');
        Route::delete('bank_account/{bank_account}', [BankAccountController::class, 'destroy'])->name('bank_account.destroy');
        //end of bank_account

        //admin
        Route::get('admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('admin/create', [AdminController::class, 'create'])->name('admin.create');
        Route::post('admin', [AdminController::class, 'store'])->name('admin.store');
        Route::get('admin/{admin}', [AdminController::class, 'show'])->name('admin.show');
        Route::get('admin/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
        Route::put('admin/{admin}', [AdminController::class, 'update'])->name('admin.update');
        Route::delete('admin/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
        //end of admin

        //role
        Route::get('role', [RoleController::class, 'index'])->name('role.index');
        Route::get('role/create', [RoleController::class, 'create'])->name('role.create');
        Route::post('role', [RoleController::class, 'store'])->name('role.store');
        Route::get('role/{role}', [RoleController::class, 'show'])->name('role.show');
        Route::get('role/{role}/edit', [RoleController::class, 'edit'])->name('role.edit');
        Route::put('role/{role}', [RoleController::class, 'update'])->name('role.update');
        Route::delete('role/{role}', [RoleController::class, 'destroy'])->name('role.destroy');
        //end of role
    });



    Route::group(['middleware' => ['role:restaurant']], function () {
        //restaurant
        Route::get('restaurant/detail/user', [RestaurantController::class, 'show_restaurant_detail'])->name('restaurant.show_restaurant_detail');
        Route::get('restaurant/history/user', [RestaurantController::class, 'history_restaurant_detail'])->name('restaurant.history_restaurant_detail');
        Route::get('restaurant/history/user/search', [RestaurantController::class, 'history_restaurant_detail_search'])->name('restaurant.history_restaurant_detail_search');
        //end of restaurant

        //restaurant_barcode
        Route::get('restaurant_barcode', [RestaurantBarcodeController::class, 'index'])->name('restaurant_barcode.index');
        Route::get('restaurant_barcode/create', [RestaurantBarcodeController::class, 'create'])->name('restaurant_barcode.create');
        Route::post('restaurant_barcode', [RestaurantBarcodeController::class, 'store'])->name('restaurant_barcode.store');
        Route::get('restaurant_barcode/{restaurant_barcode}', [RestaurantBarcodeController::class, 'show'])->name('restaurant_barcode.show');
        Route::get('restaurant_barcode/{restaurant_barcode}/edit', [RestaurantBarcodeController::class, 'edit'])->name('restaurant_barcode.edit');
        Route::put('restaurant_barcode/{restaurant_barcode}', [RestaurantBarcodeController::class, 'update'])->name('restaurant_barcode.update');
        Route::delete('restaurant_barcode/{restaurant_barcode}', [RestaurantBarcodeController::class, 'destroy'])->name('restaurant_barcode.destroy');
        //end of restaurant_barcode
    });
});
