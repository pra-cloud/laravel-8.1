<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TenantTest extends TestCase
{
    
    use DatabaseMigrations;
    /**
     * Create new Tenant Test
     *
     * @test
     */
    public function createTenantTest()
    {
        $request = '{
            "domain":"testdomain.com",
            "name": "farhan",
            "status":0,
            "plan_expiry_date":"2020-07-23",
            "payment_failed_tries":0,
            "email":"farhanrizwan123@gmail.com",
            "mobile":"0651861130",
            "country":"India",
            "city":"kanpur",
            "saas_plan_id":1,
            "tenant_billing_detail":{
                "billing_name":"Sameer",
                "billing_email":"faisalrizwan123@gmail.com",
                "billing_phone":"9044300127",
                "billing_address":"88/354 Chaman Ganj Kanpur",
                "tax_type_id":"AABC",
                "tax_id":"DEFG"
            }
        }';
        
        $this->json('POST', '/tenant/create', json_decode($request, true))
        ->seeJson([
            'success' => true,
        ]);
    }

    /**
     * Edit Tenant Test
     *
     * @test
     */
    public function editTenantTest()
    {
        $request = '{
            "tenant_id":2,
            "domain":"testdomain2.com",
            "name": "farhann2",
            "email":"farhanrizwan1232@gmail.com",
            "mobile":"06518611320",
            "city":"kanpu2r",
            "country":"India",
            "status":0,
            "saas_plan_id":1,
            "plan_expiry_date":"2020-07-23",
            "payment_failed_tries":0,
            "tenant_billing_detail":{
                "billing_name":"Sameer",
                "billing_email":"faisalrizwan123@gmail.com",
                "billing_phone":"9044300127",
                "billing_address":"88/354 Chaman Ganj Kanpur",
                "tax_type_id":"AABC",
                "tax_id":"DEFG"
            }
        
        }';
        
        $this->json('POST', '/tenant/update', json_decode($request, true))
        ->seeJson([
            'success' => true,
        ]);
    }
    
}
