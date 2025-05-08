<?php

   namespace App\Models;

   use Illuminate\Contracts\Auth\Authenticatable;
   use Illuminate\Database\Eloquent\Model;

   class GuestUser extends Model implements Authenticatable
   {
       protected $table = 'users'; // Specify the associated table
       public $id = 0; // Guest ID
       public $name = 'Guest User'; // Guest name
       public $email = 'guest@example.com'; // Placeholder for guest email

       // Implementing required methods
       public function getAuthIdentifier()
       {
           return $this->id; // Identifier implementation
       }

       public function getAuthIdentifierName()
       {
           return 'id'; // Name of the identifier
       }

       public function getAuthPassword()
       {
           // Return the user's password
           return $this->password; // Make sure 'password' field exists and is filled.
       }
    

       public function getRememberToken()
       {
           return null; // No remember token for guest
       }

       public function setRememberToken($value) // Optional
       {
           // No action needed for guest
       }

       public function getRememberTokenName()
       {
           return 'remember_token'; // Default token name
       }
   }
   