<?php

namespace App\Services;

use App\Models\Membership;
use App\Models\MembershipPoint;
use App\Models\Souvenir;
use App\Models\SouvenirRedeem;
use App\Utils\Response;

class SouvenirRedeemService {
    use Response;
    public static function redeem($data, Membership $membership)
    {
        $souvenir = Souvenir::find($data['souvenir_id']);
        if(!$souvenir){
            (new self)->sendFailedResponse([], 'Souvenir tidak di temukan');
        }
        $data['point_used'] = $souvenir->point * $data['quantity'];
        if($membership->sum_point < $data['point_used']){
            (new self)->sendFailedResponse([], 'Point tidak cukup');
        }
        if($souvenir->quantity < $data['quantity']){
            (new self)->sendFailedResponse([], 'Kuota souvenir tidak tersedia');
        }
        $souvenir->decrement('quantity', $data['quantity']);
        MembershipService::decrement($membership, $data['point_used']);
        return SouvenirRedeem::create($data);
    }
}
        