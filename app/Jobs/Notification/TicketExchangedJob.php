<?php

namespace App\Jobs\Notification;

use App\Events\SendingNotification;
use App\Http\Resources\CheckpointResource;
use App\Http\Resources\Mail\ExchangedTicketResource;
use App\Models\Notification;
use App\Models\Order;
use App\Utils\NotificationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TicketExchangedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Order $order) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payload = NotificationMessage::successfullySendingTicket();
        $notification = Notification::build($payload[0], $payload[1], $this->order->id, $this->order->user_id);

        SendingNotification::dispatch($notification, $this->order->user?->fcm_token, true, [
            'reference_id'=>(string) $this->order->id,
            'type'=>Notification::TYPE1
        ]);

        $agency = $this->order->agency;
        $checkpoint_end = $this->order->fleet_route->route->checkpoints()->where('agency_id', $this->order->destination_agency_id)->first();
        $data = [
            "fleet_name"=>$this->order->fleet_route?->fleet_detail?->fleet?->name,
            "fleet_class"=>$this->order->fleet_route?->fleet_detail?->fleet?->fleetclass?->name,
            "departure_at"=>$this->order->agency->agency_departure_times->where('time_classification_id', $this->order->time_classification_id)->first()->departure_at,
            "checkpoint_start"=>(object) [
                "agency_id"=>$agency?->id ?? "",
                "agency_name"=>$agency?->name ?? "",
                "agency_address"=>$agency?->address ?? "",
                "agency_phone"=>$agency?->phone ?? "",
                "agency_lat"=>$agency?->lat,
                "agency_lng"=>$agency?->lng,
                "city_name"=>$agency?->city?->name ?? "",
            ],
            "checkpoint_destination"=>(object) [
                "agency_id"=>$checkpoint_end->agency?->id ?? "",
                "agency_name"=>$checkpoint_end->agency?->name ?? "",
                "agency_address"=>$checkpoint_end->agency?->address ?? "",
                "agency_phone"=>$checkpoint_end->agency?->phone ?? "",
                "agency_lat"=>$checkpoint_end->agency?->lat,
                "agency_lng"=>$checkpoint_end->agency?->lng,
                "city_name"=>$checkpoint_end->agency?->city?->name ?? "",
                "arrived_at"=>$checkpoint_end->arrived_at,
            ],
            "code_order"=>$this->order->code_order,
            "order_detail"=>(object) [
                "customer_name"=>$this->order->order_detail[0]->name,
                "is_feed"=>$this->order->order_detail[0]->is_feed ? "Ya" : "Tidak",
                "is_travel"=>$this->order->order_detail[0]->is_travel ? "Ya" : "Tidak",
                "id_member"=>$this->order->id_member ? $this->order->id_member : "-"
            ],
            "distribution"=>$this->order->distribution,
            "seats"=>implode(", ", $this->order->order_detail->pluck('chair.name')->toArray()),
            "seats_count"=>$this->order->order_detail->count(),
            "total_price"=>$this->order->price
        ];

        Log::info($this->order->order_detail->pluck('chair.name')->toArray());
        
        $order_detail = $this->order->order_detail[0];
        try {
            Mail::send('_emails.exchange', $data, function($message) use ($order_detail,$payload) {
                $message->to($order_detail->email, $order_detail->name)->subject('Berhasil Menukarkan Tiket');
                $message->from(env('MAIL_USERNAME'), $payload[0]);
            });    
        } catch (\Throwable $th) {
            Log::info('email tidak terkirim dengan baik');
        }
    }
}
