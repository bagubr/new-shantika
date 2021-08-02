<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function check(Request $request) {
        $code_member = $request->code_member ?? $this->sendFailedResponse([], 'ID Member belum diisi');

        $member = Membership::where('code_member', $code_member)->first()
            ?? $this->sendFailedResponse([], 'ID Membership tidak ditemukan');

        return $this->sendSuccessResponse([
            'membership'=>$member,
            'discount'=>config('application.price_list.member')
        ]);
    }
}