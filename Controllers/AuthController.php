<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    // buat regisster
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    public function registerProcess()
    {
        $rules = [
            'nama'             => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[8]',
            'konfirmasi_pass'  => 'required|matches[password]',
            'nim_nip'          => 'permit_empty|max_length[50]',
        ];

        $messages = [
            'email'           => ['is_unique'  => 'Email sudah terdaftar.'],
            'konfirmasi_pass' => ['matches'    => 'Konfirmasi password tidak cocok.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'mahasiswa',
            'nim_nip'  => $this->request->getPost('nim_nip'),
            'no_hp'    => $this->request->getPost('no_hp'),
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // --login--
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function loginProcess()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmail($email);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        // Simpan data user ke session
        session()->set([
            'isLoggedIn' => true,
            'userId'     => $user['id'],
            'userNama'   => $user['nama'],
            'userEmail'  => $user['email'],
            'userRole'   => $user['role'],
        ]);

        // Redirect berdasarkan role
        return match ($user['role']) {
            'pimpinan' => redirect()->to('/pimpinan/dashboard'),
            'staff'    => redirect()->to('/staff/dashboard'),
            default    => redirect()->to('/mahasiswa/dashboard'),
        };
    }

    // --logout--
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')
            ->with('success', 'Anda telah logout.');
    }
}