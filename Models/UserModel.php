<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama', 'email', 'password', 'role', 'nim_nip', 'no_hp', 'foto'];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'nama'     => 'required|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[8]',
        'nim_nip'  => 'permit_empty|max_length[50]',
    ];

    protected $validationMessages = [
        'email'    => ['is_unique' => 'Email sudah terdaftar.'],
        'password' => ['min_length' => 'Password minimal 8 karakter.'],
    ];

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }
}