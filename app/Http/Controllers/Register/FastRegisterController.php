<?php


namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Support\Facades\Hash;

class FastRegisterController extends Controller {

    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function registerUser($cellphone, $password, $cabinetAdminId, $clientId, $doctorId, $coachId)
    {
        $user = new User();
        $user->cellphone = $cellphone;
        $user->password = Hash::make($password);
        $user->cabinetadminid = $cabinetAdminId;
        $user->clientid = $clientId;
        $user->doctorid = $doctorId;
        $user->coachid = $coachId;

        $user->save();
    }
}
