<?php

namespace App\Repositories;
use App\Models\User;
use Laratrust\Models\Role;
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
        return User::with(['role'])->paginate(10);
    }

    public function getById($id): User
    {
        return User::findOrFail($id);
    }

    public function store(array $data): User
    {
        $data['username'] = $this->generateUniqueUsername($data['email']);
        $data['role_id'] = 2;
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
       return User::with(['role:id,name'])->where('email', $identifier)->orWhere('username', $identifier)->first();
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

    public function assignRole($user,$role){
        $user->role_id = null;
        $user->save();
        $user->role_id = $role->id;
        $user->save();
        return $user->role;
    }

    public function revokeRole($user,$role){
        if ($user->role_id === $role->id) {
            $user->role_id = null;
            $user->save();
        }
        return $user->role;
    }

    public function getUsersStatisticsByYear($year)
    {
        if (config('database.default') === 'sqlite') {
            return User::select(
                    DB::raw('strftime("%m", created_at) as month'),
                    DB::raw('COUNT(*) as users_count')
                )
                ->where(DB::raw('strftime("%Y", created_at)'), $year)
                ->groupBy(DB::raw('strftime("%m", created_at)'))
                ->orderBy(DB::raw('strftime("%m", created_at)'))
                ->get();
        } else {
            return User::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as users_count')
                )
                ->whereYear('created_at', $year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get();
        }
    }

}
