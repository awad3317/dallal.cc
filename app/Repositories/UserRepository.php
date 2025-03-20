<?php

namespace App\Repositories;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\RepositoriesInterface;

class UserRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return User::paginate(10);
    }

    public function getById($id): User
    {
        return User::findOrFail($id);
    }

    public function store(array $data): User
    {
        $data['username'] = $this->generateUniqueUsername($data['email']);
        return User::create($data);
    }

    public function update(array $data, $id): User
    {
        $user = User::findOrFail($id);
        if (array_key_exists('image', $data)) {
            if (\File::exists($user->image)) {
                \File::delete($user->image);
            }
        }
        $user->update($data);
        return $user;
    }
    public function delete($id): bool
    {
        return DB::transaction(function () use ($id) {
    
        });
    }
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }
    public function findByUsername($username)
    {
        return User::where('username', $username)->first();
    }
    public function findByUsernameOrEmail($identifier)
    {
       return User::where('email', $identifier)->orWhere('username', $identifier)->first();
    }
    public function generateUniqueUsername($email){
        $baseUsername = explode('@', $email)[0];
        $uniqueUsername = $baseUsername;

        $count = 1;
        while (User::where('username', $uniqueUsername)->exists()) {
            $uniqueUsername = $baseUsername . '-' . $count;
            $count++;
        }

        return $uniqueUsername;
    }

    public function changePassword(array $data,$user) {
        if(!Hash::check($data['old_password'], $user->password)){
            return false;
        }
        $user->update([
            'password'=>$data['password'],
        ]);
        return true;
    }

    public function assignRole($user_id,$role){
        $user = $this->getById($user_id);
        if(!$user->hasRole($role)){
            $user->addRole($role);
            return $user->getRoles();
        }
        return $user->getRoles();
    }

    public function revokeRole($user_id,$role){
        $user = $this->getById($user_id);
        if($user->hasRole($role)){
            $user->removeRole($role);
            return $user->getRoles();
        }
        return $user->getRoles();
    }

}
