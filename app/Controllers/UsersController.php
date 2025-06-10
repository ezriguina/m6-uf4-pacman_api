<?php

namespace App\Controllers;

// use App\Controllers\BaseController;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


// if (!function_exists('decodeTokenJWT')) {
//     function decodeTokenJWT($token, $config) {
//         return JWT::decode($token, new \Firebase\JWT\Key($config['secretKey'], $config['encryption']));
//     }
// }

class UsersController extends ResourceController
{
   public function login()
    {
        // helper("form");

        $rules = [
            'email' => 'required',
            'password' => 'required|min_length[4]'
        ];
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $model = new UsersModel();
        $user = $model->getUserByMailOrUsername($this->request->getVar('email'));

        if (!$user) return $this->failNotFound('Email no trobat');

        $verify = password_verify($this->request->getVar('password'), $user['password']);

        if (!$verify) return $this->failUnauthorized('Usuari o contrasenya incorrectes.');

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
            'messages' => 'Usuari logat satisfactòriament',
            'token' => $token
        ];
        return $this->respondCreated($response);
    }

    
    public function register_post()
    {
        helper('form');   // Per poder emprar validation_list_errors() & validation_show_error
        $data['title'] = "Formulari de registre";
        $data['page_title'] = "Formulari de registre";
    
        $validationRules = [
            'username' => [
                'label'  => 'Nom d\'Usuari',
                'rules'  => 'required|min_length[3]',
                'errors' => [
                    'required' => 'El nom és un camp obligatori',
                    'min_length' => 'El nom ha de tenir almenys 3 caràcters',
                ],
            ],
            'email' => [
                'label'  => 'Correu Electrònic',
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'El correu electrònic és obligatori',
                    'valid_email' => 'No és un correu electrònic vàlid',
                    'is_unique' => 'Aquest correu ja està registrat',
                ],
            ],
            'password' => [
                'label'  => 'Contrasenya',
                'rules'  => 'required|min_length[4]',
                'errors' => [
                    'required' => 'La contrasenya és obligatòria',
                    'min_length' => 'La contrasenya ha de tenir almenys 4 caràcters',
                ],
            ],
            'repeat_password' => [
                'label'  => 'Repeteix la Contrasenya',
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Has de repetir la contrasenya',
                    'matches' => 'Les contrasenyes no coincideixen',
                ],
            ],
            'account_number' => [
                'label'  => 'Número de Compte',
                'rules'  => 'required|numeric',
                'errors' => [
                    'required' => 'El número de compte és obligatori',
                    'numeric' => 'El número de compte ha de ser numèric',
                ],
            ],
            'accept_payment' => [
                'label'  => 'Acceptar pagament',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'És obligatori acceptar el pagament',
                ],
            ],
        ];
    
            if ($this->validate($validationRules)) {
                $model = new UsersModel();
    
                $data = [
                    'name' => $this->request->getPost('username'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'account_number' => $this->request->getPost('account_number'),
                    'payment_accepted' => $this->request->getPost('accept_payment'),
                ];
    
                if ($model->insert($data)) {
                    session()->setFlashdata('success', 'Usuari registrat correctament.');
                    return redirect()->to(base_url('userdemo/login'));
                } else {
                    session()->setFlashdata('error', 'No s\'ha pogut registrar l\'usuari. Torna-ho a intentar.');
                }
            } else {
                $data['validation'] = $this->validator;
                return redirect()->back()->withInput();
            }
    
        echo view("users/register", $data);
    }

    public function create()
    {

        // helper('form'); // Per poder emprar validation_list_errors() & validation_show_error
        // $data['title'] = "Crear Usuari";
        // $data['page_title'] = "Crear Usuari";

        // $validationRules = [
        //     'username' => [
        //         'label'  => 'Nom d\'Usuari',
        //         'rules'  => 'required|min_length[3]',
        //         'errors' => [
        //             'required' => 'El nom és un camp obligatori',
        //             'min_length' => 'El nom ha de tenir almenys 3 caràcters',
        //         ],
        //     ],
        //     'email' => [
        //         'label'  => 'Correu Electrònic',
        //         'rules'  => 'required|valid_email|is_unique[users.email]',
        //         'errors' => [
        //             'required' => 'El correu electrònic és obligatori',
        //             'valid_email' => 'No és un correu electrònic vàlid',
        //             'is_unique' => 'Aquest correu ja està registrat',
        //         ],
        //     ],
        //     'password' => [
        //         'label'  => 'Contrasenya',
        //         'rules'  => 'required|min_length[4]',
        //         'errors' => [
        //             'required' => 'La contrasenya és obligatòria',
        //             'min_length' => 'La contrasenya ha de tenir almenys 4 caràcters',
        //         ],
        //     ],
        //     'edat' => [
        //         'label'  => 'Edat',
        //         'rules'  => 'required|numeric',
        //         'errors' => [
        //             'required' => "L'edat és obligatòria",
        //             'numeric' => "L'edat ha de ser numèrica",
        //         ],
        //     ],
        //     'pais' => [
        //         'label'  => 'País',
        //         'rules'  => 'required|min_length[3]',
        //         'errors' => [
        //             'required' => "L'edat és obligatòria",
        //             'min_length' => "El país ha de tenir almenys 3 caràcters",
        //         ],
        //     ],
        // ];

        // if ($this->validate($validationRules)) {
            $model = new UsersModel();

            $data = [
                'name' => $this->request->getVar('name'),
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'edat' => $this->request->getVar('edat'),
                'pais' => $this->request->getVar('pais'),
            ];
    
            if ($model->insert($data)) {
                // session()->setFlashdata('success', 'Usuari registrat correctament.');
                 $response = [
                    'status' => 200,
                    'error' => false,
                    'messages' => 'Usuari creat correctament',
                ];
                // return redirect()->to(base_url('users/create'));
            } else {
                // session()->setFlashdata('error', 'No s\'ha pogut registrar l\'usuari. Torna-ho a intentar.');
                $response = [
                    'status' => 400,
                    'error' => false,
                    'messages' => 'Dades incorrectes o contrasenya insegura',
                ];
                
            }
        // } else {
        //     $data['validation'] = $this->validator;
        // }
        return $this->respond($response);

    // echo view("users/create", $data);
   
    }

    public function update_user()
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
        
        $model = new UsersModel();
        $user = $model->getUserById($token_data->uid);
        if (!$user) {
            return $this->respond([
            'status' => 'error',
            'message' => 'Usuari no trobat'
            ], 404);
        }
        $userId = $token_data->uid;
        $json = $this->request->getJSON(true);

        $updateData = [];
        if (isset($json['name'])) {
            $updateData['name'] = $json['name'];
        }
        if (isset($json['password']) && !empty($json['password'])) {
            $updateData['password'] = password_hash($json['password'], PASSWORD_DEFAULT);
        }
        if (isset($json['mail'])) {
            $updateData['email'] = $json['mail'];
        }
        if (isset($json['pais'])) {
            $updateData['pais'] = $json['pais'];
        }

        if (empty($updateData)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'No hi ha dades per actualitzar'
            ], 400);
        }

        if ($model->update($userId, $updateData)) {
            return $this->respond([
                'status' => 'ok',
                'message' => 'Dades actualitzades'
            ], 200);
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'No s\'han pogut actualitzar les dades'
            ], 400);
        }
    }

        
    public function logged()
    {
        helper('jwt');
            
        $header = $this->request->header("token-data");
        if (!$header) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token invàlid o no present'
            ], 401);
        }

        $token_data = json_decode($header->getValue());

        if (!$token_data) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token invàlid'
            ], 401);
        }

        $response = [
            'status' => 200,
            'error' => false,
            'logged' => true,
            
        ];
        return $this->respond($response);
    }
                // Password hashing
                // https://www.php.net/manual/en/function.password-hash.php
                // https://www.php.net/manual/en/function.password-verify.php
                // https://www.php.net/manual/en/faq.passwords.php
                // https://www.php.net/manual/en/faq.passwords.php#faq.passwords.how-to-verify
                // https://www.php.net/manual/en/faq.passwords.php#faq.passwords.how-to-hash
                // https://www.php


    // public function delete($id)
    // {
    //     $model = new UsersModel();
    //     $user = $model->getUserById($id);
    //     if (empty($user)) {
    //         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    //     }

    //     if ($model->delete($id) && $model->purgeDeleted()) {
    //         session()->setFlashdata('success', 'Usuari esborrat correctament.');
    //         return redirect()->to(base_url('users/list'));
    //     } else {
    //         session()->setFlashdata('error', 'No s\'ha pogut esborrar l\'usuari. Torna-ho a intentar.');
    //         return redirect()->to(base_url('users/list'));
    //     }
    // }

    public function logout()
    {
        helper('jwt');
    
        $header = $this->request->header("token-data");
        if (!$header) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token invàlid.'
            ], 401);
        }
    
        $token_data = json_decode($header->getValue());
        if (!$token_data) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token invàlid'
            ], 401);
        }
    
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
    
        if (!$token || !isset($token_data->uid)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Token no vàlid'
            ], 400);
        }

        $cfgAPI = new \Config\APIJwt('default');
        $token_data = JWT::decode($token, new Key($cfgAPI->config()->tokenSecret, $cfgAPI->config()->hash));
        $tokensModel = new \App\Models\TokensModel();
        $tokensModel->revoke($token_data);
    
        return $this->respond([
            'status' => 'ok',
            'message' => 'Sessió tancada'
        ], 200);
    }
}
