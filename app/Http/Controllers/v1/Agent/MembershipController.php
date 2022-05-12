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
        $membership =  Membership::where('code_member', $request->code_member)->where('user_id', $request->user_id)->first();
        
        if(!$membership){
            return $this->failedResponse([], 'Data Member tidak di temukan');
        }
        $membership['email'] = User::find($request->user_id)->email;
        return $this->sendSuccessResponse([
            'data'=> $membership,
        ]);
    }
}
