<?php

namespace App\Repositories;

use App\Models\Membership;
use App\Models\MembershipPoint;
use App\Models\User;

class MembershipRepository {

    private $idRequest;
    private $membershipId;

    public static function getHome($id)
    {
        $data = new MembershipRepository;
        $data->idRequest = $id;
        return ['data' =>
            [
                'name' => $data->getName(),
                'phone' => $data->getPhone(),
                'point' => $data->getPoint(),
                'code_member' => $data->getCodeMember()
            ],
            'redeem_history' => $data->getRedeemHistory(),
            'list_souvenir' => $data->getListSouvenir()
        ];
    }

    public function getUser()
    {
        return User::find($this->idRequest);
    }

    public static function createMembership($id)
    {
        $membership = Membership::create(['user_id' => $id]);
        MembershipPoint::create(['membership_id' => $membership->id, 'value' => 0, 'status' => 'create']);
        return MembershipRepository::getHome($id);
    }

    public function getMembershipId()
    {
        $this->membershipId = $this->getUser()->membership->id;
    }

    public function getPoint()
    {
        return $this->getUser()->membership->sum_point;
    }

    public function MembershipPoint()
    {
        return new MembershipPoint;
    }

    public function getPointHistory($id)
    {
        return $this->MembershipPoint()->where('membership_id', $id)->OrderBy('created_at', 'desc')->get();
    }

    public function decrementPoint($data)
    {
        $data['status'] = 'redeem';
        return $this->MembershipPoint()
            ->create($data);
    }

    public function getCodeMember()
    {
        return $this->getUser()->membership->code_member;
    }

    public function SouvenirRepository()
    {
        return new SouvenirRepository;
    }

    public function getRedeemHistory()
    {
        $this->getMembershipId();
        return $this->SouvenirRepository()->getRedeemHistory($this->membershipId);
    }

    public function getListSouvenir()
    {
        return $this->SouvenirRepository()->getListSouvenir();
    }

    public function getPhone()
    {
        return $this->getUser()->phone;
    }

    public function getName()
    {
        return $this->getUser()->name;
    }
}