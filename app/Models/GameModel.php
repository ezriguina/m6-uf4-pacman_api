<?php

namespace App\Models;

use CodeIgniter\Model;

class GameModel extends Model
{
    protected $table            = 'game';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['data', 'guanyat', 'punts', 'durada', 'user_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
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

    public function addGame(array $data)
    {
        return $this->insert($data);

    }

    public function getUserLastGames($userId, $limit = 10)
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    public function getUserStats($userId)
    {
        $where = ['user_id' => $userId];

        $total = $this->where($where)->countAllResults(false);

        $guanyades = $this->where($where)->where('guanyat', 1)->countAllResults(false);
        $perdudes = $this->where($where)->where('guanyat', 0)->countAllResults(false);

        $mitjana_punts = $this->where($where)->selectAvg('punts')->first()['punts'] ?? 0;
        $mitjana_durada = $this->where($where)->selectAvg('durada')->first()['durada'] ?? 0;

        $result = [
            'total' => $total,
            'guanyades' => $guanyades,
            'perdudes' => $perdudes,
            'mitjana_punts' => $mitjana_punts,
            'mitjana_durada' => $mitjana_durada
        ];

        $total = (int)($result['total'] ?? 0);
        $guanyades = (int)($result['guanyades'] ?? 0);
        $perdudes = (int)($result['perdudes'] ?? 0);
        $mitjana_punts = isset($result['mitjana_punts']) ? round($result['mitjana_punts']) : 0;
        $mitjana_durada = isset($result['mitjana_durada']) ? round($result['mitjana_durada']) : 0;
        $percentatge_victories = $total > 0 ? round(($guanyades / $total) * 100) : 0;

        return [
            'status' => 'ok',
            'total' => $total,
            'guanyades' => $guanyades,
            'perdudes' => $perdudes,
            'percentatge_victories' => $percentatge_victories,
            'mitjana_punts' => $mitjana_punts,
            'mitjana_durada' => $mitjana_durada
        ];
    }

    
    public function getTopUsers($limit = 10)
    {
        $results = $this->select('users.name as nom_usuari')
            ->selectCount('game.id', 'partides')
            ->select('SUM(CASE WHEN game.guanyat = 1 THEN 1 ELSE 0 END) as victories')
            ->select('SUM(CASE WHEN game.guanyat = 0 THEN 1 ELSE 0 END) as derrotes')
            ->selectSum('game.punts', 'punts_totals')
            ->join('users', 'users.id = game.user_id')
            ->groupBy('game.user_id')
            ->orderBy('punts_totals', 'DESC')
            ->limit($limit)
            ->findAll();
    
        $jugadors = [];
        $posicio = 1;
        foreach ($results as $row) {
            $jugadors[] = [
                'posicio' => $posicio++,
                'nom_usuari' => $row['nom_usuari'],
                'partides' => (int)$row['partides'],
                'victories' => (int)$row['victories'],
                'derrotes' => (int)$row['derrotes'],
                'punts_totals' => (int)$row['punts_totals'],
            ];
        }
    
        return [
            'status' => 'ok',
            'jugadors' => $jugadors
        ];
    }
    
}
