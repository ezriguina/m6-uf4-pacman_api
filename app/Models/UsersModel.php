<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'edat', 'pais'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

     public function getUserByMailOrUsername($email) {
     
         // return $this->where('email',$email)->first();
         return $this->orWhere('email',$email)->orWhere('name',$email)->first();
     }

    public function getUserById($id) {
        return $this->where('id',$id)->first();
    }
    
    // public function getAllUsers() {
    //     return $this->findAll();
    // }

    // public function acceptSubscription($id) {
    //     $this->update($id, ['validated' => 1]);
    // }
    // public function rejectSubscription($id) {  
    //     $this->delete($id);
    //     $this->purgeDeleted();
    // }

    // public function deleteSubscription($id) {
    //     $this->update($id, ['validated' => 0]);
    // }

    // public function getAllValidatedUsers() {
    //     return $this->where('validated', 1)->findAll();
    // }
    // public function getAllNotValidatedUsers() {
    //     return $this->where('validated', 0)->findAll();
    // }
}
