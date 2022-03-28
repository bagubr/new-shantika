<?php

namespace App\Repositories;

use App\Models\Membership;
use App\Models\Souvenir;
use App\Models\SouvenirRedeem;

class SouvenirRepository {

    private $id;

    public static function create($data)
    {
        $Souvenir = new SouvenirRepository;
        return $Souvenir->Souvenir()->create($data);
    }

    public static function Reedem($data)
    {
        $SR = new SouvenirRepository;
        $saldo = Membership::find($data['membership_id'])->sum_point;
        if((int) $SR->getSouvenirPricePoint($data['souvenir_id']) > $saldo)
        {
            return 0;
        }
        $SR->Souvenir()->find($data['souvenir_id'])->decrement('quantity', $data['quantity']);
        $pointUsed = $data['quantity'] * (int) $SR->getSouvenirPricePoint($data['souvenir_id']);
        $result = [
            'value' => $pointUsed,
            'membership_id' => $data['membership_id'],

        ];
        $SR->MembershipRepository()->decrementPoint($result);
        $data['point_used'] = $pointUsed;
        $create = $SR->SouvenirRedeem()->create($data);
        return $create;
    }


    public function setId($id)
    {
        return $this->id = $id;
    }

    public function Souvenir()

    {
        return new Souvenir;
    }

    public function SouvenirRedeem()
    {
        return new SouvenirRedeem;
    }

    public function MembershipRepository()
    {
        return new MembershipRepository;
    }

    public function getRedeemHistory($id)
    {
        return $this->SouvenirRedeem()->where('membership_id', $id)->get();
    }

    public function getSouvenirPricePoint($id)
    {
        return $this->Souvenir()->where('id', $id)->first()->point;
    }

    public static function getListSouvenir()
    {
        $SR = new SouvenirRepository;
        return $SR->Souvenir()->take(10)->get();
    }

    public static function getFullListSouvenir()
    {
        $SR = new SouvenirRepository;
        return $SR->Souvenir()->get();
    }

    public static function showSouvenir($id)
    {
        $SR = new SouvenirRepository;
        return $SR->Souvenir()->where('id', $id)->first();
    }

    public function updateSouvenir($id, $data)
    {
        return $this->Souvenir()->find($id)->update($data);
    }

    public function deleteSouvenir($id)
    {
        return $this->Souvenir()->find($id)->get();
    }
}