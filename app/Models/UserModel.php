<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'tb_user';
    protected $primaryKey = 'id_user';

    public function getUserName($id)
    {
        return $this->select('nama')
            ->where('id_user', $id)
            ->first();
    }
}
