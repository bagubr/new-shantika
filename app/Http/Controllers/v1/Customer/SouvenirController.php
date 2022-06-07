<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RedeemSouvenirRequest;
use App\Models\Souvenir;
use App\Models\SouvenirRedeem;
use App\Repositories\MembershipRepository;
use App\Repositories\SouvenirRepository;
use App\Repositories\UserRepository;
use App\Services\SouvenirRedeemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SouvenirController extends Controller
{
    public function listSouvenir()
    {
        return $this->sendSuccessResponse([
            'data' => SouvenirRepository::getFullListSouvenir()
        ], 'Berhasil Menampilkan List Souvenir');
    }

    public function Redeem(RedeemSouvenirRequest $request)
    {
        $user = UserRepository::findByToken($request->bearerToken())
        ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu registrasi dahulu');
        $data = $request->all();

        $data['status'] = SouvenirRedeem::STATUS_WAITING;
        $membership = MembershipRepository::getByUserId($user->id);
        $data['membership_id'] = $membership->id;
        DB::beginTransaction();
        try {
            $souvenir_reedem = SouvenirRedeemService::redeem($data, $membership);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->failedResponse([], 'Data Gagal diproses');
        }
        return $this->sendSuccessResponse(['data' => $souvenir_reedem], 'Berhasil redeem souvenir');
    }

    public function showSouvenir($id)
    {
        return $this->sendSuccessResponse(['data' => Souvenir::find($id)], 'Berhasil Menampilkan Data Souvenir');
    }

    public function detailRedeem($id)
    {
        return $this->sendSuccessResponse(['data' => SouvenirRedeem::with(['agency', 'souvenir'])->find($id)], 'Berhasil Menampilkan Detail Redeem Souvenir');
    }

}
