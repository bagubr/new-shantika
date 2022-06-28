<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
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
=======
use App\Models\MembershipPoint;
use App\Repositories\MembershipRepository;
use App\Repositories\SouvenirReedemRepository;
use App\Repositories\SouvenirRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
>>>>>>> rilisv1
    public function index(Request $request)
    {
        $user = UserRepository::findByToken($request->bearerToken())
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');
<<<<<<< HEAD
        if (isset($user->membership->id)) {
            $data = MembershipRepository::getHome($user->id);
            return $this->sendSuccessResponse($data, 'Berhasil Menampilkan Data Home');
        }else{
            $data = MembershipRepository::createMembership($user->id);
            return $this->sendSuccessResponse($data, 'Berhasil Menampilkan Data Home');
        }
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
=======
        $data['data'] = MembershipRepository::getByUserId($user->id);
        $data['data']['code_member'] = implode("|", [$data['data']['code_member'], $user->id]);
        $data['redeem_history'] = SouvenirReedemRepository::getByUserId($data['data']['id']);
        $data['list_souvenir'] = SouvenirRepository::getListSouvenir();
        return $this->sendSuccessResponse($data, 'Berhasil Menampilkan Data');
>>>>>>> rilisv1
    }

    public function pointHistory(Request $request)
    {
        $user = UserRepository::findByToken($request->bearerToken())
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');
<<<<<<< HEAD
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
=======
        $membership = MembershipRepository::getByUserId($user->id);
        return $this->sendSuccessResponse([
            'data' => MembershipPoint::where('membership_id', $membership->id)->orderBy('id', 'desc')->get()
        ], 'Berhasil Menampilkan History Point');
>>>>>>> rilisv1
    }
}