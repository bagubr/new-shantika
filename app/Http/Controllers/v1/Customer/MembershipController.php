<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Souvenir;
use App\Repositories\MembershipRepository;
use App\Repositories\SouvenirRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = UserRepository::findByToken($request->bearerToken())
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');
        if (isset($user->membership->id)) {
            $data = MembershipRepository::getHome($user->id);
        }else{
            $data = MembershipRepository::createMembership($user->id);
        }
        $data['data']['code_member'] = implode("|", [$data['data']['code_member'], $user->id]);
        return $this->sendSuccessResponse($data, 'Berhasil Menampilkan Data');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function Redeem(Request $request)
    {
        $user = UserRepository::findByToken($request->bearerToken())
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');
        $rules = [
            'souvenir_id' => 'required',
            'quantity' => 'required',
        ];
        $data = $request->all();
        $validate = Validator::make($data, $rules);
        if (isset($user->membership->id)) {
            $data = [
                'souvenir_id' => $request->souvenir_id,
                'membership_id' => $user->membership->id,
                'quantity' => $request->quantity,
                'status' => 'WAITING'
            ];

            $create = SouvenirRepository::Reedem($data);
            if($create){
                return $this->sendSuccessResponse(['data' => $create], 'Berhasil redeem souvenir');
            }else{
                return $this->sendFailedResponse([], 'Gagal redeem souvenir');
            }
        }else{
            return $this->sendFailedResponse([], 'Akun ini belum terkait membership');
        }
    }

    public function listSouvenir()
    {
        $data = SouvenirRepository::getFullListSouvenir()->toArray();
        return $this->sendSuccessResponse(['data' => $data], 'Berhasil Menampilkan List Souvenir');
    }

    public function pointHistory(Request $request)
    {
        $user = UserRepository::findByToken($request->bearerToken())
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');
        if (isset($user->membership->id)) {
            $MR = new MembershipRepository;
            return $this->sendSuccessResponse(['data' => $MR->getPointHistory($user->membership->id)->toArray()], 'Berhasil Menampilkan History Point');
        }else{
            return $this->sendFailedResponse([], 'Akun ini belum terkait membership');
        }

    }

    public function showSouvenir($id)
    {
        $data = SouvenirRepository::showSouvenir($id)->toArray();
        return $this->sendSuccessResponse(['data' => $data], 'Berhasil Menampilkan Data Souvenir');
    }

    public function detailRedeem($id)
    {
        $SR = new SouvenirRepository;
        $data = $SR->getRedeemDetailHistory($id);

        return $this->sendSuccessResponse(['data' => $data], 'Berhasil Menampilkan Detail Redeem Souvenir');
    }
}