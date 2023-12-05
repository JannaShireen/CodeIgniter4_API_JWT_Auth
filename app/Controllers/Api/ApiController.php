<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class ApiController extends ResourceController
{
    //POST
   public function userRegister(){
    $rules = [
        "name" => "required",
        "email" => "required|valid_email|is_unique[users.email]",
        "password" => "required"
       
    ];
    if(!$this->validate($rules)){
        //not validated
        $response = [
            "status" => 500,
            "message" => $this->validator->getErrors(),
            "error" => true,
            "data" => []
        ];
    }
    else{

        //validated
        $data = [
            "name" => $this->request->getVar("name"),
            "email" => $this->request->getVar("email"),
            "password" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
        ];
        $user_obj = new UserModel();
        if($user_obj->insert($data)){
            //inserted successfully
            $response = [
                "status" => 200,
                "message" => "New User Account created Succeffully",
                "error" => false,
                "data" => []
            ];
        }
        else{
            // failed to insert
            $response = [
                "status" => 500,
                "message" => "Failed to create a user account",
                "error" => true,
                "data" => []
            ];

        }

    }
    return $this->respondCreated($response);
   }

   //POST
   public function userLogin(){

   }
   //GET
   public function userProfile(){

   }
   //POST
   public function createBook(){

   }
   //GET
   public function listBook(){

   }
   //DELETE
   public function deleteBook(){

   }
}
