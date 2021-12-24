<?php

namespace App\Modules\Billing\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class CustomerDTO extends DataTransferObject
{
   public string $customerId;
   public string $email;
   public string $firstName;
   public string $lastName;
   public string $city;
   public string $country;
}
