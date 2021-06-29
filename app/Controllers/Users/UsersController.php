<?php


namespace App\Controllers\Users;


use App\Middleware\Auth;
use App\Models\KycModel;
use App\Models\UsersModel;
use App\Services\ImageManipulator;
use App\Services\Token;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class UsersController extends ResourceController
{

    use ResponseTrait;

    private Auth $auth;
    private UsersModel $userModel;
    /**
     * @var KycModel
     */
    private $kycModel;

    public function __construct()
    {
        $this->auth             = new Auth();
        $this->userModel        = new UsersModel();
        $this->kycModel         = new KycModel();
        helper(['validation_helper']);
    }

    public function uploadProfileImage()
    {
        if ($this->auth->check())
        {
            $validated = $this->validate([
                'file' => [
                    'uploaded[file]',
                    'mime_in[file,image/jpg,image/jpeg,image/png]',
                    'max_size[file,2096]',
                ],
            ]);
            $image = $this->request->getFile('file');
            if ($validated)
            {
                //delete his current image from the server, if exist
                if ($this->auth->Users->profile_image !== NULL)
                {
                    unlink(ROOTPATH.'public/profile/'.$this->auth->Users->profile_image);
                    unlink(ROOTPATH.'public/temp_profile/'.$this->auth->Users->profile_image);
                }

                $newName = $image->getRandomName();
                $image->move(ROOTPATH . 'public/temp_profile/', $newName);

                //create the image thumbs for manipulation
                $ImageThumbs = $newName;

                //image manipulator to set the (100x100) and position center
                (new ImageManipulator($ImageThumbs))->handle();

                //update the current user profile image
                $this->userModel->updateProfileImage($this->auth->Users->id, $newName);

                return $this->respond(
                    [
                        'status'    => true,
                        'message'   => 'Image have been uploaded successfully',
                        'path'      => base_url('public/profile/'.$newName)
                    ]);

            }
            else
            {
                return $this->fail($this->validator->getError());
            }
        }
        return $this->failUnauthorized();
    }

    public function updatePassword()
    {
        if ($this->auth->check())
        {
            $data = (new Token())->getJsonData();

            if (!array_key_exists('password',$data))
            {
                return $this->fail('Password is required');
            }

            if (!array_key_exists('confirm_password',$data))
            {
                return $this->fail('Confirm password is required');
            }

            if (Validate_Password($data['password']))
            {
                if ($data['password'] == $data['confirm_password'])
                {
                    if ($this->userModel->resetPassword($data['password'], $this->auth->Users->id))
                    {
                        return $this->respond([
                            'status'    => true,
                            'message'   => 'Password updated successfully'
                        ]);
                    }

                    return $this->fail('Something went wrong');
                }
                return $this->fail('Password does not match');
            }
            return $this->fail('Password must be up to 6 character');
        }

        return $this->failUnauthorized();
    }

    public function uploadKYC()
    {
        if ($this->auth->check())
        {
            $first_name     = $this->request->getPost('first_name');
            $middle_name    = $this->request->getPost('middle_name');
            $last_name      = $this->request->getPost('last_name');
            $address        = $this->request->getPost('address');
            $city           = $this->request->getPost('city');
            $state          = $this->request->getPost('state');
            $dob            = $this->request->getPost('dob');
            $phone          = $this->request->getPost('phone');
            $country        = $this->request->getPost('country');
            $id_type        = $this->request->getPost('id_type');
            $id_number      = $this->request->getPost('id_number');
            $file           = $this->request->getFile('id_upload');

            $validated = $this->validate([
                'first_name'    => 'required',
                'last_name'     => 'required',
                'address'       => 'required',
                'city'          => 'required',
                'state'         => 'required',
                'country'       => 'required',
                'dob'           => 'required',
                'phone'         => 'required',
                'id_number'     => 'required',
                'id_type'       => 'required',
                'id_upload' => [
                    'uploaded[id_upload]',
                    'mime_in[id_upload,image/jpg,image/jpeg,image/png]',
                    'max_size[id_upload,5096]',
                ],
            ]);

            if (!$validated)
            {
                return $this->fail($this->validator->listErrors());
            }

            //do the upload
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/kyc/', $newName);

            //insert
            $id = $this->kycModel->create([
                'user_id'       => $this->auth->Users->id,
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'middle_name'   => $middle_name,
                'address'       => $address,
                'city'          => $city,
                'state'         => $state,
                'country'       => $country,
                'date_of_birth' => $dob,
                'phone'         => $phone,
                'id_type'       => $id_type,
                'id_number'     => $id_number,
                'id_upload'     => $newName,
                'created_at'    => date('Y-m-d H:i:s')
            ]);

            if ($id == null)
            {
                return $this->fail('KYC uploaded already');
            }

            return $this->respond([
                'status'    => true,
                'message'   => 'Uploaded successfully'
            ]);

        }

        return $this->failUnauthorized();
    }

    public function getKYC()
    {
        if ($this->auth->check())
        {
            $details    = $this->kycModel->getInfo($this->auth->Users->id);

            if ($details)
            {
                return $this->respond([
                    'status'    => true,
                    'message'   => 'KYC found',
                    'data'      => [
                        'first_name'        => $details->first_name,
                        'middle_name'       => $details->middle_name,
                        'last_name'         => $details->last_name,
                        'phone'             => $details->phone,
                        'date_of_birth'     => $details->date_of_birth,
                        'address'           => $details->address,
                        'city'              => $details->city,
                        'state'             => $details->state,
                        'country'           => $details->country,
                        'id_type'           => $details->id_type,
                        'id_number'         => $details->id_number,
                        'id_upload'         => base_url("public/kyc/$details->id_upload"),
                        'approved'          => $details->approved,
                        'created_at'        => date('d M Y', strtotime($details->created_at))
                    ]
                ]);
            }

            return $this->failNotFound();
        }

        return $this->failUnauthorized();
    }

}