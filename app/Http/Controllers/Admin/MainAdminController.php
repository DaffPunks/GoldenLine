<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Services\Decoder;

use App\Models\CabinetAdmin;

class MainAdminController extends Controller {

    public static function renderViewWithAdminInfo($viewName, $additionalData = array()){

        $cabinetAdminId = Auth::user()->cabinetadminid;

        $cabinetAdmin = CabinetAdmin::find($cabinetAdminId);

        $name = Decoder::decodeString("$cabinetAdmin->SURNAME $cabinetAdmin->NAME");

        $userRole = 'admin';
        $adminRole = Auth::user()->cabinetadminid;

        $cabinetAdminInfo = array('userRole' => $userRole, 'username' => $name, 'adminRole' => $adminRole);

        return view($viewName, array_merge($cabinetAdminInfo, $additionalData));
    }
}