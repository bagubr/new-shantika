<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\MembershipPoint;
use App\Repositories\MembershipRepository;
use App\Repositories\SouvenirReedemRepository;
use App\Repositories\SouvenirRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $user = UserRepository::findByToken($request->bearerToken())
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');
        $data['data'] = MembershipRepository::getByUserId($user->id);
        $data['data']['code_member_stk'] = 'SNTK' . sprintf('%08d', $data['data']['code_member']);
        $data['data']['code_member'] = implode("|", [$data['data']['code_member'], $user->id]);
        $data['redeem_history'] = SouvenirReedemRepository::getByUserId($data['data']['id']);
        $data['list_souvenir'] = SouvenirRepository::getListSouvenir();
        return $this->sendSuccessResponse($data, 'Berhasil Menampilkan Data');
    }

    public function pointHistory(Request $request)
    {
        $user = UserRepository::findByToken($request->bearerToken())
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');
        $membership = MembershipRepository::getByUserId($user->id);
        return $this->sendSuccessResponse([
            'data' => MembershipPoint::where('membership_id', $membership->id)->orderBy('id', 'desc')->get()
        ], 'Berhasil Menampilkan History Point');
    }
}