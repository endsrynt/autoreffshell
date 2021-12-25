<?php

require 'function.php';


echo "\e[0;33m[1]\e[0m Shell Creator Account Manual OTP\n";
echo "\e[0;33m[2]\e[0m Shell Creator Account Automatic OTP (sms-ative.ru)\n";
echo "\e[0;33m[3]\e[0m Shell Creator Account Automatic OTP (smshub.org)\n\n";

echo "\n?Pilih : ";
$pilihan = trim(fgets(STDIN));
echo "\n";
if ($pilihan == 1) {

    echo "\e[0;32m? Kode Refferal : \e[0m";
    $kodeReff = trim(fgets(STDIN));
    while(true) {
    $nama = explode(" ", nama());
    $nama1 = $nama[0];
    $nama2 = $nama[1];
    $rand = angkarand(5);
    $tgl = angkarand(1);
    $bln = angkarand(1);
    $thn = angkarand(1);
    $dob = "200$thn-0$bln-1$tgl";
    $dev1 = acakc(8);
    $dev2 = acakc(4);
    $dev3 = acakc(4);
    $dev4 = acakc(4);
    $dev5 = acakc(12);
    $deviceid = "$dev1-$dev2-$dev3-$dev4-$dev5";
    $email = "$nama1$nama2$rand@gmail.com";
    echo "\n";
    echo "\e[0;32m? Phone Number  : \e[0m";
    $phoneNumber = trim(fgets(STDIN));

    echo "\e[0;33m[!]\e[0m Proccessing Register $phoneNumber\n";
    echo "\e[0;33m[!]\e[0m Proccessing Register $email\n";

    $headers = [
        'Host: apac2-auth-api.capillarytech.com',
        'Accept: application/json',
        'Content-Type: application/json',
        'User-Agent: ShellGoPlus%20Production/17 CFNetwork/1312 Darwin/21.0.0',
        'Accept-Language: id',
        'Connection: close',
    ];;

    $data = '{"mobile":"62'.$phoneNumber.'","brand":"SHELLINDONESIALIVE","deviceId":"'.$deviceid.'"}';
    $getSession = curl('https://apac2-auth-api.capillarytech.com/auth/v1/token/generate', $data, $headers);
    $sessionId = get_between($getSession[1], '"sessionId":"', '"');
    echo "\e[0;33m[!]\e[0m Has Found Session ID    : \e[0;32m$sessionId\e[0m\n";
    echo "\e[0;33m[!]\e[0m Has Found Device ID     : \e[0;32m$deviceid\e[0m\n";

    $data = '{"brand":"SHELLINDONESIALIVE","mobile":"62'.$phoneNumber.'","sessionId":"'.$sessionId.'","deviceId":"'.$deviceid.'"}';
    $getOtp = curl('https://apac2-auth-api.capillarytech.com/auth/v1/otp/generate', $data, $headers);
    $message = get_between($getOtp[1], '"message":"', '"');

    if ($message) {
    echo "\e[0;33m[!]\e[0m Has Found Message       : \e[0;32m$message\e[0m\n";
    } else {
    echo "\e[0;33m[!]\e[0m Not Found Message\n";
    }

    otpCode:
    echo "\e[0;32m? ? Otp : \e[0m";
    $otpAnda = trim(fgets(STDIN));
    $data = '{"brand":"SHELLINDONESIALIVE","deviceId":"'.$deviceid.'","sessionId":"'.$sessionId.'","otp":"'.$otpAnda.'","mobile":"62'.$phoneNumber.'"}';
    $validationOtp = curl('https://apac2-auth-api.capillarytech.com/auth/v1/otp/validate', $data, $headers);
    $message = get_between($validationOtp[1], '"message":"', '"');
    $token = get_between($validationOtp[1], '"token":"', '",');
    if ($message == 'SUCCESS') {
    echo "\e[0;33m[!]\e[0m Information Register    : \e[0;32mSuccessfully Register\e[0m\n";
    echo "\e[0;33m[!]\e[0m Information Token       : $token\n";
    } else if ($message == 'Invalid OTP') {
    echo "\e[0;33m[!]\e[0m Information Register    : \e[0;31mInvalid OTP\e[0m\n";
    goto otpCode;
    }

    $headers = [
        'Host: apac2-api-gateway.capillarytech.com',
        'Cap_brand: SHELLINDONESIALIVE',
        'Content-Type: application/json',
        'Accept: */*',
        'Cap_mobile: 62'.$phoneNumber.'',
        'Cap_authorization: '.$token.'',
        'Accept-Language: id',
        'User-Agent: ShellGoPlus%20Production/17 CFNetwork/1312 Darwin/21.0.0',
        'Cap_device_id: '.$deviceid.'',
        'Connection: close',
    ];

    $getRefferal = curlget('https://apac2-api-gateway.capillarytech.com/mobile/v2/api/referral/validate?code='.$kodeReff.'', null, $headers);
    $message = get_between($getRefferal[1], '"valid":', ',"');
    if ($message) {
    echo "\e[0;33m[!]\e[0m Information Refferal   : \e[0;32mSuccessfully Input Refferal\e[0m\n";
    }
    echo "\n";

    $headers = [
        'Host: apac2-api-gateway.capillarytech.com',
        'Content-Type: application/json',
        'Accept: application/json',
        'Cap_device_id: '.$deviceid.'',
        'Cap_brand: SHELLINDONESIALIVE',
        'Cap_mobile: 62'.$phoneNumber.'',
        'Accept-Language: id',
        'Cap_authorization: '.$token.'',
        'User-Agent: ShellGoPlus%20Production/17 CFNetwork/1312 Darwin/21.0.0',
        'Connection: close',
    ];
    $data = '{"statusLabelReason":"App Registration","loyaltyInfo":{"loyaltyType":"loyalty"},"referralCode":"'.$kodeReff.'","profiles":[{"identifiers":[{"type":"mobile","value":"62'.$phoneNumber.'"},{"value":"'.$email.'","type":"email"}],"fields":{"app_privacy_policy":"1","onboarding":"pending","goplus_tnc":"1"},"lastName":"'.$nama2.'","firstName":"'.$nama1.'"}],"extendedFields":{"acquisition_channel":"mobileApp","verification_status":false,"dob":"'.$dob.'"},"statusLabel":"Active"}';
    $lastRegister = curl('https://apac2-api-gateway.capillarytech.com/mobile/v2/api/v2/customers', $data, $headers);
    $message = get_between($lastRegister[1], '"createdId":', ',"');
    $reward = get_between($lastRegister[1], '"rawAwardedPoints":', ',"');
    echo "\e[0;33m[!]\e[0m Information ID         : \e[0;32m$message\e[0m\n";
    echo "\e[0;33m[!]\e[0m Information Point      : \e[0;32m$reward\e[0m\n";

    }
} else if ($pilihan == 2) {

    echo "\e[0;32m? Kode Refferal : \e[0m";
    $kodeReff = trim(fgets(STDIN));
    echo "\e[0;32m? Api Key       : \e[0m";
    $apiKey = trim(fgets(STDIN));
    while(true) {
        $nama = explode(" ", nama());
        $nama1 = $nama[0];
        $nama2 = $nama[1];
        $rand = angkarand(5);    
        $tgl = angkarand(1);
        $bln = angkarand(1);
        $thn = angkarand(1);
        $dob = "200$thn-0$bln-1$tgl";
        $dev1 = acakc(8);
        $dev2 = acakc(4);
        $dev3 = acakc(4);
        $dev4 = acakc(4);
        $dev5 = acakc(12);
        $deviceid = "$dev1-$dev2-$dev3-$dev4-$dev5";
        $email = "$nama1$nama2$rand@gmail.com";
        echo "\n";
        $getphoneNumber = curlget('https://sms-activate.ru/stubs/handler_api.php?api_key='.$apiKey.'&action=getNumber&service=ot&forward=0&operator=telkomsel&ref=0&country=6', null, null);
        $accessID = get_between($getphoneNumber[1], 'ACCESS_NUMBER:', ':');
        $phoneNumber = preg_match_all("#$accessID:(.*)#", $getphoneNumber[1], $hasil);
        $phoneNumber = $hasil[1][0];
        echo "\n";
        if ($phoneNumber) {
        echo "\e[0;33m[!]\e[0m Phone Number            : \e[0;32m$phoneNumber\e[0m\n";
        echo "\e[0;33m[!]\e[0m Access Number           : \e[0;32m$accessID\e[0m\n";
        echo "\e[0;33m[!]\e[0m Information Refferal    : \e[0;32m$kodeReff\e[0m\n";
        echo "\e[0;33m[!]\e[0m Proccessing Register $phoneNumber\n";
        echo "\e[0;33m[!]\e[0m Proccessing Register $email\n";
        } else {
            continue;
        }
        $headers = [
            'Host: apac2-auth-api.capillarytech.com',
            'Accept: application/json',
            'Content-Type: application/json',
            'User-Agent: ShellGoPlus%20Production/17 CFNetwork/1312 Darwin/21.0.0',
            'Accept-Language: id',
            'Connection: close',
        ];;
    
        $data = '{"mobile":"'.$phoneNumber.'","brand":"SHELLINDONESIALIVE","deviceId":"'.$deviceid.'"}';
        $getSession = curl('https://apac2-auth-api.capillarytech.com/auth/v1/token/generate', $data, $headers);
        $sessionId = get_between($getSession[1], '"sessionId":"', '"');
        echo "\e[0;33m[!]\e[0m Has Found Session ID    : \e[0;32m$sessionId\e[0m\n";
        echo "\e[0;33m[!]\e[0m Gerenate Device ID      : \e[0;32m$deviceid\e[0m\n";
    
        $data = '{"brand":"SHELLINDONESIALIVE","mobile":"'.$phoneNumber.'","sessionId":"'.$sessionId.'","deviceId":"'.$deviceid.'"}';
        $getOtp = curl('https://apac2-auth-api.capillarytech.com/auth/v1/otp/generate', $data, $headers);
        $message = get_between($getOtp[1], '"message":"', '"');
    
        if ($message) {
        echo "\e[0;33m[!]\e[0m Has Found Message       : \e[0;32m$message\e[0m\n";
        } else {
        echo "\e[0;33m[!]\e[0m Not Found Message\n";
        }
        $data = 'action=setStatus&api_key='.$apiKey.'+&id='.$accessID.'&status=1';
        $accessReady = curl('https://sms-activate.ru/stubs/handler_api.php', $data, null);
        
    for ($i=0; $i < 10; $i++) { 
        $getOtpCode = curlget('https://sms-activate.ru/stubs/handler_api.php?api_key='.$apiKey.'&action=getStatus&id='.$accessID.'', null, null);
        $json = json_encode($getOtpCode[1]);
        $otpAnda = get_between($json, '(OTP) is ', '"');
    
        if ($otpAnda) {
            echo "\e[0;33m[!]\e[0m Otp      : \e[0;32m$otpAnda\e[0m\n";
            $doneReady = curlget('https://sms-activate.ru/stubs/handler_api.php?api_key='.$apiKey.'&action=setStatus&status=6status&id='.$accessID.'', null, null);
            break;
        } else {
            echo "\e[0;33m[!]\e[0m Otp      : \e[0;32mWaiting For Code\e[0m\n";
            sleep(5);
            continue;
        }
    }
    
    try {
        $data = 'action=setStatus&api_key='.$apiKey.'&id='.$accessID.'&status=-1';
        $doneReady = curl('https://sms-activate.ru/stubs/handler_api.php', $data, null);
    } catch (\Throwable $th) {
    }
        $data = '{"brand":"SHELLINDONESIALIVE","deviceId":"'.$deviceid.'","sessionId":"'.$sessionId.'","otp":"'.$otpAnda.'","mobile":"'.$phoneNumber.'"}';
        $validationOtp = curl('https://apac2-auth-api.capillarytech.com/auth/v1/otp/validate', $data, $headers);
        $message = get_between($validationOtp[1], '"message":"', '"');
        $token = get_between($validationOtp[1], '"token":"', '",');
        if ($message == 'SUCCESS') {
        echo "\e[0;33m[!]\e[0m Information Register    : \e[0;32mSuccessfully Register\e[0m\n";
        } else if ($message == 'Invalid OTP') {
        echo "\e[0;33m[!]\e[0m Information Register    : \e[0;31mInvalid OTP\e[0m\n";
        }
    
        $headers = [
            'Host: apac2-api-gateway.capillarytech.com',
            'Cap_brand: SHELLINDONESIALIVE',
            'Content-Type: application/json',
            'Accept: */*',
            'Cap_mobile: '.$phoneNumber.'',
            'Cap_authorization: '.$token.'',
            'Accept-Language: id',
            'User-Agent: ShellGoPlus%20Production/17 CFNetwork/1312 Darwin/21.0.0',
            'Cap_device_id: '.$deviceid.'',
            'Connection: close',
        ];
    
        $getRefferal = curlget('https://apac2-api-gateway.capillarytech.com/mobile/v2/api/referral/validate?code='.$kodeReff.'', null, $headers);
        $message = get_between($getRefferal[1], '"valid":', ',"');
        if ($message) {
        echo "\e[0;33m[!]\e[0m Information Refferal    : \e[0;32mSuccessfully Input Refferal\e[0m\n";
        }
    
        $headers = [
            'Host: apac2-api-gateway.capillarytech.com',
            'Content-Type: application/json',
            'Accept: application/json',
            'Cap_device_id: '.$deviceid.'',
            'Cap_brand: SHELLINDONESIALIVE',
            'Cap_mobile: '.$phoneNumber.'',
            'Accept-Language: id',
            'Cap_authorization: '.$token.'',
            'User-Agent: ShellGoPlus%20Production/17 CFNetwork/1312 Darwin/21.0.0',
            'Connection: close',
        ];
        $data = '{"statusLabelReason":"App Registration","loyaltyInfo":{"loyaltyType":"loyalty"},"referralCode":"'.$kodeReff.'","profiles":[{"identifiers":[{"type":"mobile","value":"'.$phoneNumber.'"},{"value":"'.$email.'","type":"email"}],"fields":{"app_privacy_policy":"1","onboarding":"pending","goplus_tnc":"1"},"lastName":"'.$nama2.'","firstName":"'.$nama1.'"}],"extendedFields":{"acquisition_channel":"mobileApp","verification_status":false,"dob":"'.$dob.'"},"statusLabel":"Active"}';
        $lastRegister = curl('https://apac2-api-gateway.capillarytech.com/mobile/v2/api/v2/customers', $data, $headers);
        $message = get_between($lastRegister[1], '"createdId":', ',"');
        $reward = get_between($lastRegister[1], '"rawAwardedPoints":', ',"');
        if ($message) {
        echo "\e[0;33m[!]\e[0m Information ID          : \e[0;32m$message\e[0m\n";
        echo "\e[0;33m[!]\e[0m Information Point       : \e[0;32m$reward\e[0m\n";
        } else {
            echo $lastRegister[1];
        }
        }
    } else if ($pilihan == 3) {

    echo "\e[0;32m? Kode Refferal : \e[0m";
    $kodeReff = trim(fgets(STDIN));
    echo "\e[0;32m? Api Key       : \e[0m";
    $apiKey = trim(fgets(STDIN));
    while(true) {
        $nama = explode(" ", nama());
        $nama1 = $nama[0];
        $nama2 = $nama[1];
        $rand = angkarand(5);    
        $tgl = angkarand(1);
        $bln = angkarand(1);
        $thn = angkarand(1);
        $dob = "200$thn-0$bln-1$tgl";
        $dev1 = acakc(8);
        $dev2 = acakc(4);
        $dev3 = acakc(4);
        $dev4 = acakc(4);
        $dev5 = acakc(12);
        $deviceid = "$dev1-$dev2-$dev3-$dev4-$dev5";
        $email = "$nama1$nama2$rand@gmail.com";
        echo "\n";
        $getphoneNumber = curlget('https://smshub.org/stubs/handler_api.php?api_key='.$apiKey.'&action=getNumber&service=ot&forward=0&operator=telkomsel&ref=0&country=6', null, null);
        $accessID = get_between($getphoneNumber[1], 'ACCESS_NUMBER:', ':');
        $phoneNumber = preg_match_all("#$accessID:(.*)#", $getphoneNumber[1], $hasil);
        $phoneNumber = $hasil[1][0];
        echo "\n";
        if ($phoneNumber) {
        echo "\e[0;33m[!]\e[0m Phone Number            : \e[0;32m$phoneNumber\e[0m\n";
        echo "\e[0;33m[!]\e[0m Access Number           : \e[0;32m$accessID\e[0m\n";
        echo "\e[0;33m[!]\e[0m Information Refferal    : \e[0;32m$kodeReff\e[0m\n";
        echo "\e[0;33m[!]\e[0m Proccessing Register $phoneNumber\n";
        echo "\e[0;33m[!]\e[0m Proccessing Register $email\n";
        } else {
            continue;
        }
        $headers = [
            'Host: apac2-auth-api.capillarytech.com',
            'Accept: application/json',
            'Content-Type: application/json',
            'User-Agent: ShellGoPlus%20Production/17 CFNetwork/1312 Darwin/21.0.0',
            'Accept-Language: id',
            'Connection: close',
        ];;

        $data = '{"mobile":"'.$phoneNumber.'","brand":"SHELLINDONESIALIVE","deviceId":"'.$deviceid.'"}';
        $getSession = curl('https://apac2-auth-api.capillarytech.com/auth/v1/token/generate', $data, $headers);
        $sessionId = get_between($getSession[1], '"sessionId":"', '"');
        echo "\e[0;33m[!]\e[0m Has Found Session ID    : \e[0;32m$sessionId\e[0m\n";
        echo "\e[0;33m[!]\e[0m Gerenate Device ID      : \e[0;32m$deviceid\e[0m\n";

        $data = '{"brand":"SHELLINDONESIALIVE","mobile":"'.$phoneNumber.'","sessionId":"'.$sessionId.'","deviceId":"'.$deviceid.'"}';
        $getOtp = curl('https://apac2-auth-api.capillarytech.com/auth/v1/otp/generate', $data, $headers);
        $message = get_between($getOtp[1], '"message":"', '"');

        if ($message) {
        echo "\e[0;33m[!]\e[0m Has Found Message       : \e[0;32m$message\e[0m\n";
        } else {
        echo "\e[0;33m[!]\e[0m Not Found Message\n";
        }
        $data = 'action=setStatus&api_key='.$apiKey.'+&id='.$accessID.'&status=1';
        $accessReady = curl('https://smshub.org/stubs/handler_api.php', $data, null);
        
    for ($i=0; $i < 10; $i++) { 
        $getOtpCode = curlget('https://smshub.org/stubs/handler_api.php?api_key='.$apiKey.'&action=getStatus&id='.$accessID.'', null, null);
        $json = json_encode($getOtpCode[1]);
        $otpAnda = get_between($json, '(OTP) is ', '"');

        if ($otpAnda) {
            echo "\e[0;33m[!]\e[0m Otp      : \e[0;32m$otpAnda\e[0m\n";
            $doneReady = curlget('https://smshub.org/stubs/handler_api.php?api_key='.$apiKey.'&action=setStatus&status=6status&id='.$accessID.'', null, null);
            break;
        } else {
            echo "\e[0;33m[!]\e[0m Otp      : \e[0;32mWaiting For Code\e[0m\n";
            sleep(5);
            continue;
        }
    }
    
    try {
        $data = 'action=setStatus&api_key='.$apiKey.'&id='.$accessID.'&status=-1';
        $doneReady = curl('https://smshub.org/stubs/handler_api.php', $data, null);
    } catch (\Throwable $th) {
    }
        $data = '{"brand":"SHELLINDONESIALIVE","deviceId":"'.$deviceid.'","sessionId":"'.$sessionId.'","otp":"'.$otpAnda.'","mobile":"'.$phoneNumber.'"}';
        $validationOtp = curl('https://apac2-auth-api.capillarytech.com/auth/v1/otp/validate', $data, $headers);
        $message = get_between($validationOtp[1], '"message":"', '"');
        $token = get_between($validationOtp[1], '"token":"', '",');
        if ($message == 'SUCCESS') {
        echo "\e[0;33m[!]\e[0m Information Register    : \e[0;32mSuccessfully Register\e[0m\n";
        } else if ($message == 'Invalid OTP') {
        echo "\e[0;33m[!]\e[0m Information Register    : \e[0;31mInvalid OTP\e[0m\n";
        }

        $headers = [
            'Host: apac2-api-gateway.capillarytech.com',
            'Cap_brand: SHELLINDONESIALIVE',
            'Content-Type: application/json',
            'Accept: */*',
            'Cap_mobile: '.$phoneNumber.'',
            'Cap_authorization: '.$token.'',
            'Accept-Language: id',
            'User-Agent: ShellGoPlus%20Production/17 CFNetwork/1312 Darwin/21.0.0',
            'Cap_device_id: '.$deviceid.'',
            'Connection: close',
        ];

        $getRefferal = curlget('https://apac2-api-gateway.capillarytech.com/mobile/v2/api/referral/validate?code='.$kodeReff.'', null, $headers);
        $message = get_between($getRefferal[1], '"valid":', ',"');
        if ($message) {
        echo "\e[0;33m[!]\e[0m Information Refferal    : \e[0;32mSuccessfully Input Refferal\e[0m\n";
        }

        $headers = [
            'Host: apac2-api-gateway.capillarytech.com',
            'Content-Type: application/json',
            'Accept: application/json',
            'Cap_device_id: '.$deviceid.'',
            'Cap_brand: SHELLINDONESIALIVE',
            'Cap_mobile: '.$phoneNumber.'',
            'Accept-Language: id',
            'Cap_authorization: '.$token.'',
            'User-Agent: ShellGoPlus%20Production/17 CFNetwork/1312 Darwin/21.0.0',
            'Connection: close',
        ];
        $data = '{"statusLabelReason":"App Registration","loyaltyInfo":{"loyaltyType":"loyalty"},"referralCode":"'.$kodeReff.'","profiles":[{"identifiers":[{"type":"mobile","value":"'.$phoneNumber.'"},{"value":"'.$email.'","type":"email"}],"fields":{"app_privacy_policy":"1","onboarding":"pending","goplus_tnc":"1"},"lastName":"'.$nama2.'","firstName":"'.$nama1.'"}],"extendedFields":{"acquisition_channel":"mobileApp","verification_status":false,"dob":"'.$dob.'"},"statusLabel":"Active"}';
        $lastRegister = curl('https://apac2-api-gateway.capillarytech.com/mobile/v2/api/v2/customers', $data, $headers);
        $message = get_between($lastRegister[1], '"createdId":', ',"');
        $reward = get_between($lastRegister[1], '"rawAwardedPoints":', ',"');
        if ($message) {
        echo "\e[0;33m[!]\e[0m Information ID          : \e[0;32m$message\e[0m\n";
        echo "\e[0;33m[!]\e[0m Information Point       : \e[0;32m$reward\e[0m\n";
        } else {
            echo $lastRegister[1];
        }
        }
} 
