<?php

namespace App\Controllers\Api;

use App\Models\BooksModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Exception;

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

        $rules = [

            "email" => "required|valid_email",
            "password" => "required"
        ];
        if(!$this->validate($rules)){
            //validation error
            $response = [
                "status" =>500,
                "message"=> $this->validator->getErrors(),
                "error"=> true,
                "data" =>[],
            ];
        }
        else{
            //validation success
            $email = $this->request->getVar("email");
            $password = $this->request->getVar("password");
            $user_obj = new UserModel();
            $user_data = $user_obj->where("email", $email)->first();    
            if(!empty($user_data)){
                //user exists
                if(password_verify($password, $user_data['password'])){
                    //password matched
                    $iat = time();
                    $nbf = $iat;
                    $exp = $iat + 500;
                    $payload = [
                        "iat" => $iat,
                        "nbf" => $nbf,
                        "exp" => $exp,
                        "userdata" => $user_data,
                        
                    ];
                   
                  
                    $token = JWT::encode($payload,$this->getKey(),'HS256');
                    $response = [
                        "status" =>500,
                        "message"=> "User Logged In",
                        "error"=> false,
                        "data" =>[
                            "token" => $token,
                        ],
                    ];
                    
                }
                else{
                    //wrong password
                    $response = [
                        "status" =>500,
                        "message"=> "Password did not match",
                        "error"=> true,
                        "data" =>[],
                    ];
                }

            }
            else{
                //user does not exist
                $response = [
                    "status" =>500,
                    "message"=> "User does not exist",
                    "error"=> true,
                    "data" =>[],
                ];
            }
        }
        return $this->respondCreated($response);
   }
   public function getKey(){
    return "AJDFGHHF";
   }
   //GET
   public function userProfile(){
    $auth = $this->request->getHeader("Authorization");
    try{
        if(isset($auth)){
            $token = $auth->getValue();
            $decoded_data = JWT::decode($token, array($this->getKey()),);
            $response = [
                "status"=> 200,
                "message"=> "User Profile",
                "data"=> [
                    "user" => $decoded_data,
                    "id" => $decoded_data->userdata->id,
                ],
                "error" => false,

            ];


        }
        else{
            $response = [
                "status"=> 500,
                "message"=> "User must login",
                "error"=> true,
                "data"=> [],
            ];
        }

    }
    catch(Exception $e){
        $response = [
            "status" => 500,
            "message" => "Exception $e",
            "error"=> true,
            "data"=>[]
        ];
    }
    return $this->respondCreated($response);

   }
   //POST
   public function createBook(){
    $rules = [
        "title" => "required",
        "price" => "required"
    ];
    if(!$this->validate($rules)){
        //not validated
        $response = [
            "status"=> 500,
            "message" => $this->validator->getErrors(),
            "data" =>[],
            "error" => true,
        ];
    }
    else{
        //validated
        $auth = $this->request->getHeader("Authorization");
        try{
            if(isset($auth)){
                $token = $auth->getValue();
                $decoded_data = JWT::decode($token, array($this->getKey()), );
                $user_id = $decoded_data->userdata->id;
                $book_obj = new BooksModel();
                $data = [
                    "user_id" => $user_id,
                    "title" => $this->request->getVar('title'),
                    'price'=> $this->request->getVar('price'),
                ];
                if($book_obj->insert($data)){
    
                    // values inserted to the table
                    $response = [
                        "status" => 200,
                        "message" => "New Book Created Successfully",
                        "data"=>[],
                        "error"=> false,
                    ];
                }
                else{
                    //failed to insert 
                    $response = [
                        "status" => 500,
                        "message" => "Failed to create book",
                        "data"=>[],
                        "error"=> true,
                    ];
        
                }
    
            }
            else{
                //no authorization
                $response = [
                    "status"=> 500,
                    "message"=> "You must log in",
                    "data"=>[],
                    "error"=> true,
                ];
            }
          

        }
        catch (Exception $e){
            $response = [
                "status" => 500,
                "message" => "Exception :" .$e->getMessage(),
                "data"=>[],
                "error"=> true,
            ];
        }
       
        return $this->respondCreated($response);
    }


   }
   //GET
   public function listBook(){

   }
   //DELETE
   public function deleteBook(){

   }
}
