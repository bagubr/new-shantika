<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Checkpoint;
use App\Models\Membership;
use App\Models\Route;
use App\Models\Setting;
use App\Models\User;
use App\Utils\CodeMember;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function check(Request $request)
    {
        // 'SNTK00018006'
        $code_member = $request->code_member ?? $this->sendFailedResponse([], 'ID Member belum diisi');
        $code_member = CodeMember::code($code_member);
        Membership::where('code_member', $code_member)->first()?:$this->sendFailedResponse([], 'Kode Membership tidak ditemukan');
        $member = Membership::where('code_member', $code_member)->when($request->name, function ($query) use ($request)
        {
            $query->orWhere('name', $request->name);
        })->first();
        if(!$member){
            $this->sendFailedResponse([], 'Nama dengan kode member ' . $code_member . ' Membership tidak ditemukan');
        }
        $array = [
            'name' => $member->name,
            'phone' => User::find($member->user_id)->phone??$member->phone,
            'email' => User::find($member->user_id)->email??'',
        ];
        $member->user = (object) $array;

        return $this->sendSuccessResponse([
            'membership' => $member,
            'discount' => Setting::first()->member
        ]);
    }
}
