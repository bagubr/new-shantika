<?php

namespace App\Imports;

use App\Models\Membership;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;

class MembershipImport implements ToModel
{
    public function model(array $row) {
        return new Membership([
            'name'          => $row[0],
            'code_member'   => $row[1],
            'phone'         => @$row[2],
            'address'       => @$row[3],
            'email'         => @$row[4]
        ]);
    }
}
