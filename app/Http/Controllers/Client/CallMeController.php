<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Client;
use App\Models\CardType;

use App\Services\Decoder;

use Illuminate\Support\Facades\Input;
use Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Mail;

class CallMeController extends Controller {

    public function getPhoneNumber(){

        if(Request::ajax()) {

            return Auth::user()->cellphone;

        }
    }

    public function callMe(){

        if(Request::ajax()) {

            $sport_mail = "zlsport@mail.ru";
            $procedures_mail = "adm_zl42@mail.ru";

            $data = Input::all();
            $is_procedures = $data['is_procedures'];
            $phone_number = $data['number'];
            $client_name = $data['client_name'];

            $email = $is_procedures ? $procedures_mail : $sport_mail;

            $mail = new \PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->Host =  env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;
            $mail->setFrom(env('MAIL_USERNAME'), env('MAIL_NAME'));
            $mail->addAddress($email);

            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Клиент просит перезвонить';
            $mail->Body    = "Клиент $client_name просит перезвонить на номер $phone_number";

            if(!$mail->send()) {
                return ['status' => 'fail', 'msg' => $mail->ErrorInfo];
            } else {
                return ['status' => 'success'];
            }
        }
    }
}