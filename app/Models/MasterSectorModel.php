<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterSectorModel extends Model
{
    protected $table      = 'tb_master_sector';
    protected $primaryKey = 'id_sector';
    
    public function getActiveSectors()
    {
        return $this->where('status_delete', 0)
            ->orderBy('nama', 'ASC')
            ->findAll();
    }
}
