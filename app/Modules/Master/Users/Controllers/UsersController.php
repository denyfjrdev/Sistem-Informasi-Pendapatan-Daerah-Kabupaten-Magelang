<?php
namespace App\Modules\Master\Users\Controllers;

use App\Modules\Master\MasterBaseController;
use App\Modules\Master\Users\Models\UsersModel;


class UsersController extends MasterBaseController
{
    protected $usersModel;
    protected $enkrip;

    public function __construct()
    {
      $this->usersModel   = new UsersModel();      
    }


    /**
     * INDEX
     */
    public function index()
    {
        $keyword = trim(
            $this->request->getGet('keyword') ?? ''
        );

        $builder = $this->usersModel;

        if ($keyword !== '') {

            $builder->groupStart()
                ->like('nama_user', $keyword)
                ->orLike('nohp', $keyword)
                ->orLike('email', $keyword)
                ->orLike('email_gov', $keyword)
                ->groupEnd();

        }

        #----lakukan enkripsi data nohp,nama dan email
        $users = $this->usersModel
            ->orderBy('id', 'DESC')
            ->paginate(10);        

        foreach ($users as &$user) {

            if (!empty($user['nohp'])) {

                try {

                  $user['nama_user']  = $this->enkrip->decode_custom($user['nama_user'],env('TOKEN_ENKRIP_CI'));                               
                  $user['nohp']       = $this->enkrip->decode_custom($user['nohp'],env('TOKEN_ENKRIP_CI'));   
                  $user['email']      = $this->enkrip->decode_custom($user['email'],env('TOKEN_ENKRIP_CI'));                            
                  $user['email_gov']      = $this->enkrip->decode_custom($user['email_gov'],env('TOKEN_ENKRIP_CI'));    

                } catch (\Throwable $e) {
                    $user['nohp'] = '-';

                }

            } else {

                $user['nohp'] = '-';

            }
        }            

        $data = [
          'data_user' =>  $this->data_user,
          'menu'    =>  'Pengguna',
          'fiture'  =>  'Master data pengguna',
            'title' => 'Manajemen User',

            'keyword' => $keyword,

            'users' => $users,

            'pager' => $this->usersModel->pager,
        ];
        

        return view(
            'App\Modules\Master\Users\Views\index',
            $data
        );        
    }


    /**
     * FORM CREATE
     */
    public function create()
    {
        $data = [
          'menu'    =>  'Pengguna',
          'fiture'  =>  'Add data pengguna',          
            'title' => 'Tambah User',

            'validation' => \Config\Services::validation(),
        ];

        return view(
            'App\Modules\Master\Users\Views\create',
            $data
        );
    }


    /**
     * SIMPAN
     */
    public function store()
    {      

        $rules = [
            'nama_user' => [
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' =>
                        'Nama user wajib diisi.',
                    'max_length' =>
                        'Nama user maksimal 255 karakter.',
                ],
            ],

            'nohp' => [
                'rules' =>
                    'permit_empty|max_length[255]|is_unique[users.nohp]',
                'errors' => [
                    'is_unique' =>
                        'Nomor HP sudah digunakan.',
                ],
            ],

            'email' => [
                'rules' =>
                    'permit_empty|valid_email|max_length[100]|is_unique[users.email]',
                'errors' => [
                    'valid_email' =>
                        'Format email tidak valid.',
                    'is_unique' =>
                        'Email sudah digunakan.',
                ],
            ],

            'email_gov' => [
                'rules' =>
                    'permit_empty|valid_email|max_length[100]',
                'errors' => [
                    'valid_email' =>
                        'Format email government tidak valid.',
                ],
            ],

            'aktif' => [
                'rules' => 'required|in_list[ya,tidak]',
            ],

            'role' => [
                'rules' => 'required|in_list[sijaka_admin,sijaka_pimpinan]',
            ],

            'keterangan' => [
                'rules' => 'permit_empty',
            ],
        ];


        if (!$this->validate($rules)) {
          // var_dump($rules);exit();

            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // var_dump($this->request->getPost('role'));exit();


        $this->usersModel->insert([

            'nama_user' =>
              $this->enkrip->encode_custom($this->request->getPost('nama_user'),env('TOKEN_ENKRIP_CI')),                

            'nohp' =>
              $this->enkrip->encode_custom($this->request->getPost('nohp'),env('TOKEN_ENKRIP_CI')) ?: null,

            'aktif' =>
                $this->request->getPost('aktif'),

            'role' =>
                $this->request->getPost('role'),

            'email' =>
              $this->enkrip->encode_custom($this->request->getPost('email'),env('TOKEN_ENKRIP_CI')) ?: null,

            'email_gov' =>
              $this->enkrip->encode_custom($this->request->getPost('email_gov'),env('TOKEN_ENKRIP_CI')) ?: null,            

            'keterangan' =>
                $this->request->getPost('keterangan'),
        ]);
        


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User berhasil ditambahkan.'
            );
    }


    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $user = $this->usersModel->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'User tidak ditemukan.'
            );
        }


        $data = [
          'menu'    =>  'Pengguna',
          'fiture'  =>  'Edit data pengguna',                    
            'title' => 'Edit User',

            'user' => $user,

            'validation' =>
                \Config\Services::validation(),
        ];


        return view(
            'App\Modules\Master\Users\Views\edit',
            $data
        );
    }


    /**
     * UPDATE
     */
    public function update($id)
    {
        $user = $this->usersModel->find($id);

        if (!$user) {

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                    'User tidak ditemukan.'
                );
        }


        $rules = [

            'nama_user' => [
                'rules' =>
                    'required|max_length[255]',
            ],

            'nohp' => [
                'rules' =>
                    'permit_empty|max_length[255]|is_unique[users.nohp,id,' . $id . ']',
                'errors' => [
                    'is_unique' =>
                        'Nomor HP sudah digunakan user lain.',
                ],
            ],

            'email' => [
                'rules' =>
                    'permit_empty|valid_email|max_length[100]|is_unique[users.email,id,' . $id . ']',
                'errors' => [
                    'valid_email' =>
                        'Format email tidak valid.',
                    'is_unique' =>
                        'Email sudah digunakan user lain.',
                ],
            ],

            'email_gov' => [
                'rules' =>
                    'permit_empty|valid_email|max_length[100]',
            ],

            'aktif' => [
                'rules' =>
                    'required|in_list[ya,tidak]',
            ],

            'role' => [
                'rules' =>
                    'required|in_list[sijaka_admin,sijaka_pimpinan]',
            ],

            'keterangan' => [
                'rules' =>
                    'permit_empty',
            ],
        ];


        if (!$this->validate($rules)) {

            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }


        $this->usersModel->update(
            $id,
            [

              'nama_user' =>
                $this->enkrip->encode_custom($this->request->getPost('nama_user'),env('TOKEN_ENKRIP_CI')),                

              'nohp' =>
                $this->enkrip->encode_custom($this->request->getPost('nohp'),env('TOKEN_ENKRIP_CI')) ?: null,

              'aktif' =>
                  $this->request->getPost('aktif'),

              'role' =>
                  $this->request->getPost('role'),

              'email' =>
                $this->enkrip->encode_custom($this->request->getPost('email'),env('TOKEN_ENKRIP_CI')) ?: null,

              'email_gov' =>
                $this->enkrip->encode_custom($this->request->getPost('email_gov'),env('TOKEN_ENKRIP_CI')) ?: null,            

              'keterangan' =>
                  $this->request->getPost('keterangan'),

            ]
        );


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User berhasil diperbarui.'
            );
    }


    /**
     * DELETE
     */
    public function delete($id)
    {
        $user = $this->usersModel->find($id);

        if (!$user) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'User tidak ditemukan.'
                );
        }


        $this->usersModel->delete($id);


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User berhasil dihapus.'
            );
    }

}