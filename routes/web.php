<?php

use App\Events\SendingNotification;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgencyFleetController;
use App\Http\Controllers\AgencyFleetPermanentController;
use App\Http\Controllers\AgencyPriceController;
use App\Http\Controllers\AgencyRouteController;
use App\Http\Controllers\AgencyRoutePermanentController;
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
use App\Http\Controllers\MembershipHistoryController;
use App\Http\Controllers\MembershipPointController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\RestaurantBarcodeController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SketchLogController;
use App\Http\Controllers\SouvenirController;
use App\Http\Controllers\SouvenirRedeemController;
use App\Http\Controllers\TimeChangeRouteController;
use App\Jobs\Notification\TicketExchangedJob;
use App\Jobs\PaymentAcceptedNotificationJob;
use App\Models\Admin;
use App\Models\FoodRedeemHistory;
use App\Models\Notification;
use App\Models\Order;
use App\Services\OrderService;
use App\Utils\CheckPassword;
use App\Utils\NotificationMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

Route::get('/test/emial', function () {
    // return env('MAIL_USERNAME');
    Mail::send('_emails.test', [], function($message) {
        $message->to('satriotol69@gmail.com', 'Satrio')->subject('Berhasil Menukarkan Tiket Test');
        $message->from(env('MAIL_USERNAME'), 'Bagu');
    });
    return 'Alhamdulillah iso ngirim email';
});

Route::get('/check-password', function ()
{
    $check = CheckPassword::checkPassword(request()->password);
    if($check){
        return $check;
    }else{
        return response([
            'code' => 1,
            'message' => 'Password Benar'
        ]);
    }
});

Route::get('_/privacy_policy', [LoginController::class, 'privacyPolicy'])->name('_privacy_policy');
Route::post('admin/store/fcm_token', [LoginController::class, 'storeFcmToken']);

// restaurant
Route::get('restaurant_barcode/search/code_order', [RestaurantBarcodeController::class, 'getOrderId'])->name('restaurant_barcode.getOrderId');
// end of restaurant

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [Dashboard2Controller::class, 'index'])->name('dashboard');
    Route::post('dashboard/dashboard', [Dashboard2Controller::class, 'statistic'])->name('dashboard.statistic');
    Route::post('dashboard/agent', [Dashboard2Controller::class, 'statisticAgent'])->name('dashboard.agent');
    Route::post('dashboard/sum', [Dashboard2Controller::class, 'statisticSum'])->name('dashboard.sum');
    Route::get('schedule_not_operate/search', [ScheduleNotOperateController::class, 'search'])->name('schedule_not_operate.search');

    Route::put('order/update_jadwal/{order}', [OrderController::class, 'update_jadwal'])->name('order.update_jadwal');
    Route::get('order/update_price/{order}', [OrderController::class, 'update_price'])->name('order.update_price');
    Route::put('order/cancelation/{order_detail}', [OrderController::class, 'cancelation'])->name('order.cancelation');
    Route::get('order/search', [OrderController::class, 'search'])->name('order.search');
    Route::get('order/export', [OrderController::class, 'export'])->name('order.export');
    Route::get('order/find/{code_order}', [OrderController::class, 'showByCodeOrder'])->name('order.show.code_order');

    Route::get('notification/{id}', [AdminNotificationController::class, 'show']);

    Route::post('outcome/statistic', [OutcomeController::class, 'statistic'])->name('outcome_statistic');
    Route::get('outcome/search', [OutcomeController::class, 'search'])->name('outcome.search');

    Route::get('outcome/export/{id}', [OutcomeController::class, 'export'])->name('outcome.export');
    Route::get('outcome_type/create', [OutcomeController::class, 'createType'])->name('outcome_type.create');
    Route::get('outcome/export/{id}', [OutcomeController::class, 'export'])->name('outcome.export');
    Route::post('outcome_type', [OutcomeController::class, 'storeType'])->name('outcome_type.store');
    Route::delete('outcome_type/{id}', [OutcomeController::class, 'destroyType'])->name('outcome_type.destroy');

    Route::get('user_agent/search', [UserAgentController::class, 'search'])->name('user_agent.search');

    Route::get('agency/all', [AgencyController::class, 'get_agency'])->name('agency.get_agency');
    Route::get('agency/search', [AgencyController::class, 'search'])->name('agency.search');
    Route::put('agency/update_status/{agency}', [AgencyController::class, 'update_status'])->name('agency.update_status');
    Route::put('user_agent/update_status/{user_agent}', [UserAgentController::class, 'update_status'])->name('user_agent.update_status');
    Route::put('user/update_status/{user}', [UserController::class, 'update_status'])->name('user.update_status');

    Route::get('order_price_distribution/export', [OrderPriceDistributionController::class, 'export'])->name('order_price_distribution.export');

    Route::get('fleet_route_prices/search', [FleetRoutePriceController::class, 'search'])->name('fleet_route_prices.search');

    Route::get('status_penumpang/search', [StatusPenumpangController::class, 'search'])->name('status_penumpang.search');
    Route::get('status_penumpang/export/', [StatusPenumpangController::class, 'export'])->name('status_penumpang.export');

    Route::get('routes/search', [RoutesController::class, 'search'])->name('routes.search');

    Route::get('fleet_route/search', [FleetRouteController::class, 'search'])->name('fleet_route.search');
    Route::get('fleet_route/get-available-route', [FleetRouteController::class, 'getFleetRoutes'])->name('fleet_route.get_fleet_routes');
    Route::get('fleet_route/blocked_chair/{fleet_route}', [FleetRouteController::class, 'blockedChairs'])->name('fleet_route.blocked_chair');
    Route::put('fleet_route/block_chair/{fleet_route}/{layout_chair_id}', [FleetRouteController::class, 'updateBlockedChairs']);
    Route::put('fleet_route/update_status/{fleet_route}', [FleetRouteController::class, 'update_status'])->name('fleet_route.update_status');

    Route::get('sketch/orders', [SketchController::class, 'getDeparturingOrders']);
    Route::get('sketch/orders/detail', [SketchController::class, 'getAvailibilityChairs']);
    Route::get('sketch/export', [SketchController::class, 'export']);
    Route::get('sketch/log', [SketchLogController::class, 'index']);
    Route::get('sketch/log/notification', [SketchLogController::class, 'notification']);
    Route::get('sketch/log/export', [SketchLogController::class, 'export'])->name('sketch_log.export');
    Route::post('sketch/store', [SketchController::class, 'store']);
    Route::delete('sketch/destroy', [SketchController::class, 'destroy']);

    Route::post('routes/fleet/store/', [RoutesController::class, 'store_fleet'])->name('route.fleet.store');

    Route::post('member/import', [MemberController::class, 'import'])->name('member.import');
    Route::get('member/search', [MemberController::class, 'search'])->name('member.search');
    // History Membership
    Route::get('membership_histories', [MembershipHistoryController::class, 'index'])->name('membership_histories.index');
    Route::post('membership_histories/export', [MembershipHistoryController::class, 'export'])->name('membership_histories.export');

    // DASHBOARD
    Route::get('first_bulan', [DashboardController::class, 'first_bulan'])->name('first_bulan');
    // END OF DASHBOARD

    // restaurant
    Route::post('restaurant/assign', [RestaurantController::class, 'assign_user'])->name('restaurant.assign_user');
    Route::delete('restaurant/admin/delete/{restaurant_admin}', [RestaurantController::class, 'destroy_admin'])->name('restaurant.destroy_admin');
    Route::get('restaurant/history/all', [RestaurantController::class, 'history_restaurant'])->name('r.history_restaurant');
    Route::get('restaurant/history/all/search', [RestaurantController::class, 'history_restaurant_search'])->name('r.history_restaurant_search');
    // end of restaurant
    Route::get('routes/duplicate/{route}', [RoutesController::class, 'duplicate'])->name('routes.duplicate');

    Route::resources([
        'agency_route_permanent' => AgencyRoutePermanentController::class,
        'agency_route' => AgencyRouteController::class,
        'agency_fleet' => AgencyFleetController::class,
        'agency_fleet_permanent' => AgencyFleetPermanentController::class,
        'time_change_route' => TimeChangeRouteController::class,
        'fleet_detail' => FleetDetailController::class,
        'fleets' => FleetController::class,
        'fleetclass' => FleetClassController::class,
        'fleet_class_price' => FleetClassPriceController::class,
        'fleet_route_prices' => FleetRoutePriceController::class,
        'layouts' => LayoutController::class,
        'routes' => RoutesController::class,
        'agency' => AgencyController::class,
        'checkpoint' => CheckpointController::class,
        'user' => UserController::class,
        'user_agent'  => UserAgentController::class,
        'time_classification' => TimeClassificationController::class,
        'payment' => PaymentController::class,
        'payment_type' => PaymentTypeController::class,
        // 'schedule_not_operate' => ScheduleNotOperateController::class,
        // 'schedule_unavailable_booking' => ScheduleUnavailableBookingController::class,
        'area' => AreaController::class,
        'province' => ProvinceController::class,
        'city' => CityController::class,
        'order' => OrderController::class,
        'order_detail' => OrderDetailController::class,
        'member' => MemberController::class,
        'order_price_distribution' => OrderPriceDistributionController::class,
        'outcome' => OutcomeController::class,
        'souvenir_redeem' => SouvenirRedeemController::class,
        'promo' => PromoController::class,
        'sketch' => SketchController::class,
        'sketch_log' => SketchLogController::class,
        'fleet_route' => FleetRouteController::class,
        'status_penumpang' => StatusPenumpangController::class,
        'agency_price' => AgencyPriceController::class,
        'membership_point' => MembershipPointController::class
    ]);
    Route::post('route_price', [AgencyPriceController::class, 'storeRoute'])->name('route_price.store');
    Route::delete('route_price/{id}', [AgencyPriceController::class, 'destroyRoute'])->name('route_price.destroy');

    Route::resource('souvenir',SouvenirController::class)->parameter('souvenir', 'id');

    // Lainnya

    Route::group(['middleware' => ['role:superadmin']], function () {
        Route::resources([
            'information' => InformationController::class,
            'faq' => FaqController::class,
            'privacy_policy' => PrivacyPolicyController::class,
            'chat' => ChatController::class,
            'facility' => FacilityController::class,
            'customer_menu' => CustomerMenuController::class,
            'slider' => SliderController::class,
            'testimoni' => TestimoniController::class,
            'article' => ArticleController::class,
            'about' => AboutController::class,
            'social_media' => SocialMediaController::class,
            'terms_condition' => TermsAndConditionController::class,
            'review' => ReviewController::class,
            'config_setting' => ConfigSettingController::class,
            'bank_account' => BankAccountController::class,
            'admin' => AdminController::class,
            'role' => RoleController::class,
            'restaurant' => RestaurantController::class,
        ]);
    });
    Route::group(['middleware' => ['role:superadmin|restaurant']], function () {
        Route::resources([
            'restaurant_barcode' => RestaurantBarcodeController::class,
        ]);
        Route::get('restaurant/detail/user', [RestaurantController::class, 'show_restaurant_detail'])->name('restaurant.show_restaurant_detail');
        Route::get('restaurant/history/user', [RestaurantController::class, 'history_restaurant_detail'])->name('restaurant.history_restaurant_detail');
        Route::get('restaurant/history/user/search', [RestaurantController::class, 'history_restaurant_detail_search'])->name('restaurant.history_restaurant_detail_search');
    });
});