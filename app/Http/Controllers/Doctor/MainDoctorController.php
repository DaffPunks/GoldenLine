<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Services\Decoder;

use App\Models\RegisteredDoctor;

class MainDoctorController extends Controller {

    public static function renderViewWithDoctorInfo($viewName, $additionalData = array()){

        $currentUser = Auth::user();

        $saloonDoctorId = $currentUser->doctorid;

        $registeredDoctor = RegisteredDoctor::findBySaloonId($saloonDoctorId);

        $name = Decoder::decodeString("$registeredDoctor->SHORTFIO");

        $userRole = 'doctor';
        if($currentUser->clientid != null)
            $userRole = 'doctorAsClient';

        $doctorInfo = array('userRole' => $userRole, 'username' => $name);

        return view($viewName, array_merge($doctorInfo, $additionalData));
    }
}