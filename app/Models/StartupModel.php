<?php
namespace App\Models;

use CodeIgniter\Model;

class StartupModel extends Model
{
    protected $table = 'tb_startup';
    protected $primaryKey = 'id_startup';

    public function getStartupById($id)
    {
        return $this->where('id_startup', $id)->first();
    }
}