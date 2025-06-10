<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\GameConfigModel;
use App\Models\UsersModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ApiController extends ResourceController
{

        
    public function addGameConfig()
    {
        helper('jwt');
    
        
        $header = $this->request->header("token-data");
        if (!$header) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }
    
        $token_data = json_decode($header->getValue());
        if (!$token_data || !isset($token_data->uid)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }
    
        $userId = $token_data->uid;
    
        
        $json = $this->request->getJSON(true);
    
        $data = [
            'tema' => $json['tema'] ?? null,
            'musica' => $json['musica'] ?? null,
            'dificultat' => $json['dificultat'] ?? null,
        ];
    
        
    
        $model = new GameConfigModel();
        if ($model->addConfig($data)) {
            return $this->respond([
                'status' => 200,
                'error' => false,
                'messages' => 'Configuració desada',
            ]);
        } else {
            return $this->respond([
                'status' => 400,
                'error' => true,
                'messages' => 'Dades incorrectes',
            ]);
        }
    }

    public function updateGameConfig()
    {
        helper('jwt');

        $header = $this->request->header("token-data");
        if (!$header) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }

        $token_data = json_decode($header->getValue());
        if (!$token_data || !isset($token_data->uid)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }

        $userId = $token_data->uid;
        $json = $this->request->getJSON(true);

        $data = [
            'tema' => $json['tema'] ?? null,
            'musica' => $json['musica'] ?? null,
            'dificultat' => $json['dificultat'] ?? null,
        ];

        $model = new GameConfigModel();
        $updated = $model->updateConfig($userId, $data);

        if ($updated) {
            return $this->respond([
                'status' => 'ok',
                'message' => 'Configuració actualitzada'
            ], 200);
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }
    }

    public function addGame()
    {
        helper('jwt');

        $header = $this->request->header("token-data");
        if (!$header) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }

        $token_data = json_decode($header->getValue());
        if (!$token_data || !isset($token_data->uid)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }

        $userId = $token_data->uid;
       
        $usersModel = new \App\Models\UsersModel();
        $user = $usersModel->getUserByMailOrUsername($token_data->email);
        $user_id = $usersModel->getUserById($userId);
        if (!$user_id) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Usuari no trobat'
            ], 404);
        }
        if (!$user || !isset($user['id'])) {
            return $this->respond([
            'status' => 'error',
            'message' => 'Usuari no trobat'
            ], 404);
        }
        $userId = $user['id'];
        $json = $this->request->getJSON(true);

        $data = [
            'data' => $json['data'] ?? null,
            'guanyat' => $json['guanyat'] ?? null,
            'punts' => $json['punts'] ?? null,
            'durada' => $json['durada'] ?? null,
            'user_id' => $userId,
        ];

        $model = new \App\Models\GameModel();
        if ($model->addGame($data)) {
            return $this->respondCreated([
                'status' => 'ok',
                'message' => 'Partida registrada',
            ]);
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }
    }

    public function getUserLastGames()
    {
        helper('jwt');

        $header = $this->request->header("token-data");
        if (!$header) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }

        $token_data = json_decode($header->getValue());
        if (!$token_data || !isset($token_data->uid)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }

        $usersModel = new \App\Models\UsersModel();
        $user = $usersModel->getUserByMailOrUsername($token_data->email);
        if (!$user || !isset($user['id'])) {
            return $this->respond([
            'status' => 'error',
            'message' => 'Usuari no trobat'
            ], 404);
        }
        $userId = $user['id'];

        $model = new \App\Models\GameModel();
        $games = $model->getUserLastGames($userId);

        return $this->respond([
            'status' => 'ok',
            'partides' => $games
        ], 200);
    }

    public function getUserStats()
    {
        helper('jwt');

        $header = $this->request->header("token-data");
        if (!$header) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }

        $token_data = json_decode($header->getValue());
        if (!$token_data || !isset($token_data->uid)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 401);
        }

        $userId = $token_data->uid;

        $model = new \App\Models\GameModel();
        $stats = $model->getUserStats($userId);

        return $this->respond([
            'status' => 'ok',
            'stats' => $stats
        ], 200);
    }

    public function getTopUsers()
    {
        $model = new \App\Models\GameModel();
        $topUsers = $model->getTopUsers();

        return $this->respond([
            'status' => 'ok',
            'top_users' => $topUsers
        ], 200);
    }


    /**
     * API Sample call
     *
     */
    public function test()
    {
        // Get current token payload as object
        $token_data = json_decode($this->request->header("token-data")->getValue());

        // Get current config for this controller request as object
        // $token_config = json_decode($this->request->header("token-config")->getValue());
       
        // Get JWT policy config
        // $policy_name = $this->request->header("jwt-policy")->getValue();

        // check if user has permission or token policy is ok
        // if user no authorized
        //      $this->fail("User no valid")

        $response = [
            'status' => 200,
            'error' => false,
            'messages' => 'Test function ok',
            'data' => [
                "data" => time(),
                "token-username" => $token_data->name,
                "token-email" => $token_data->email,
            ]
        ];
        return $this->respond($response);
    }

    /**
     * Login API to generate JWT token
     *
     */
    public function login()
    {
        helper("form");

        $rules = [
            'email' => 'required',
            'password' => 'required|min_length[4]'
        ];
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $model = new UsersModel();
        $user = $model->getUserByMailOrUsername($this->request->getVar('email'));

        if (!$user) return $this->failNotFound('Email Not Found');

        $verify = password_verify($this->request->getVar('password'), $user['password']);

        if (!$verify) return $this->fail('Wrong Password');

        /****************** GENERATE TOKEN ********************/
        helper("jwt");
        $APIGroupConfig = "default";
        $cfgAPI = new \Config\APIJwt($APIGroupConfig);

        $data = array(
            "uid" => $user['id'],
            "name" => $user['name'],
            "email" => $user['email']
        );

        $token = newTokenJWT($cfgAPI->config(), $data);
        /****************** END TOKEN GENERATION **************/

        $response = [
            'status' => 200,
            'error' => false,
            'messages' => 'User logged In successfully',
            'token' => $token
        ];
        return $this->respondCreated($response);
    }
} 