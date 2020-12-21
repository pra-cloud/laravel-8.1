<?php

namespace App\Http\Controllers;

use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;
use Illuminate\Http\Request;

class TenantSettingsController extends Controller
{
    use SettingsServiceTrait;


    public function updateDeliverySettings(Request $request)
    {


        try {

            $this->validate($request,[

                'tenant_id' => "required",
                'user_id' => "required",
                'user_type' => "required|in:admin,tenant,merchant,customer,driver",
                'setting_key' => "required",
                'setting_value' => "required"

            ]);



            $response=$this->updateSetting(

                $request->post("setting_key"),
                $request->post("setting_value"),
                $request->post("tenant_id"),
                $request->post("user_id"),
                $request->post("user_type")
            );


            return response()->json($response);

//            $request_to_be_sent = new \Illuminate\Http\Request();
//            $request_to_be_sent->query->add([
//                'tenant_id' => $request->post("tenant_id"),
//                'user_id' => $request->post("user_id"),
//                'user_type' => $request->Post("user_type"),
//                'setting_key' => $request->post("setting_key"),
//                'setting_value' => $request->post("setting_value")
//            ]);


            return $this->consumeService('setting', $request, 'POST', '/create');

        }catch (\Exception $e){

            return $this->errorResponse(null, $e->getMessage());
        }
    }



    public function fetchDeliverySettings(Request $request)
    {


        try {

            $this->validate($request,[

                'tenant_id' => "required",
                'user_id' => "required",
                'user_type' => "required|in:admin,tenant,merchant,customer,driver",
                'setting_key' => "required",

            ]);

            $response=$this->settingsByKey(

                $request->post("setting_key"),
                $request->post("tenant_id"),
                $request->post("user_id"),
                $request->post("user_type")

            );


            return response()->json($response);

        }catch (\Exception $e){

            return $this->errorResponse(null, $e->getMessage());
        }
    }

}
