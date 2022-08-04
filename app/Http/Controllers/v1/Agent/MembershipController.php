<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function getData(Request $request)
    {
        try {
            $data = explode("|", $request->code_member);
            $membership =  Membership::where('code_member', $data[0])->first();
            
            if(!$membership){
                return $this->failedResponse([], 'Data Member tidak di temukan');
            }
            $user['code_member'] = $membership->code_member_stk;
            $user['name'] = User::find($data[1])->name;
            $user['phone'] = User::find($data[1])->phone;
            $user['email'] = User::find($data[1])->email;
            
            return $this->sendSuccessResponse([
                'data'=> $user,
            ]);
        } catch (\Throwable $th) {
            return $this->sendFailedResponse([], 'Maaf Format barcode salah');
        }
    }
}
