<?php

use App\Events\SendingNotification;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AgencyController;
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
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FleetClassController;
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
use App\Jobs\Notification\TicketExchangedJob;
use App\Jobs\PaymentAcceptedNotificationJob;
use App\Models\Admin;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderPriceDistribution;
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

Route::post('admin/store/fcm_token', [LoginController::class, 'storeFcmToken']);

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('schedule_not_operate/search', [ScheduleNotOperateController::class, 'search'])->name('schedule_not_operate.search');
    Route::get('order/search', [OrderController::class, 'search'])->name('order.search');

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

    Route::get('order_price_distribution/search', [OrderPriceDistributionController::class, 'search'])->name('order_price_distribution.search');

    Route::get('status_penumpang/search', [StatusPenumpangController::class, 'search'])->name('status_penumpang.search');
    Route::get('status_penumpang/export/', [StatusPenumpangController::class, 'export'])->name('status_penumpang.export');

    Route::get('routes/search', [RoutesController::class, 'search'])->name('routes.search');

    Route::get('fleet_route/search', [FleetRouteController::class, 'search'])->name('fleet_route.search');
    Route::put('fleet_route/update_status/{fleet_route}', [FleetRouteController::class, 'update_status'])->name('fleet_route.update_status');

    Route::get('sketch/orders', [SketchController::class, 'getDeparturingOrders']);
    Route::get('sketch/orders/detail', [SketchController::class, 'getAvailibilityChairs']);
    Route::post('sketch/store', [SketchController::class, 'store']);

    Route::post('routes/fleet/store/', [RoutesController::class, 'store_fleet'])->name('route.fleet.store');

    // DASHBOARD
    route::get('first_bulan', [DashboardController::class, 'first_bulan'])->name('first_bulan');
    // END OF DASHBOARD

    Route::resources([
        'fleet_detail' => FleetDetailController::class,
        'fleets' => FleetController::class,
        'fleetclass' => FleetClassController::class,
        'information' => InformationController::class,
        'faq' => FaqController::class,
        'privacy_policy' => PrivacyPolicyController::class,
        'layouts' => LayoutController::class,
        'routes' => RoutesController::class,
        'agency' => AgencyController::class,
        'chat' => ChatController::class,
        'checkpoint' => CheckpointController::class,
        'facility' => FacilityController::class,
        'customer_menu' => CustomerMenuController::class,
        'slider' => SliderController::class,
        'user' => UserController::class,
        'user_agent'  => UserAgentController::class,
        'testimoni' => TestimoniController::class,
        'article' => ArticleController::class,
        'about' => AboutController::class,
        'time_classification' => TimeClassificationController::class,
        'social_media' => SocialMediaController::class,
        'terms_condition' => TermsAndConditionController::class,
        'payment' => PaymentController::class,
        'payment_type' => PaymentTypeController::class,
        'schedule_not_operate' => ScheduleNotOperateController::class,
        'schedule_unavailable_booking' => ScheduleUnavailableBookingController::class,
        'area' => AreaController::class,
        'province' => ProvinceController::class,
        'city' => CityController::class,
        'order' => OrderController::class,
        'member' => MemberController::class,
        'review' => ReviewController::class,
        'order_price_distribution' => OrderPriceDistributionController::class,
        'config_setting' => ConfigSettingController::class,
        'bank_account' => BankAccountController::class,
        'outcome' => OutcomeController::class,
        'sketch' => SketchController::class,
        'fleet_route' => FleetRouteController::class,
        'status_penumpang' => StatusPenumpangController::class
    ]);
});
