<?php

namespace App\Models;

use CodeIgniter\Model;

class ContentModel extends Model
{
    protected $table = 'tb_content';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'heading',
        'content',
        'section',
        'status_delete'
    ];

    public function getBySection(string $section)
    {
        return $this->select('heading, content')
            ->where('status_delete', 0)
            ->where('section', $section)
            ->orderBy('id', 'DESC')
            ->first();
    }
}