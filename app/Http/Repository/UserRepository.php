<?php


namespace App\Http\Repository;


use App\User;
use Illuminate\Support\Facades\Auth;

class UserRepository implements RepositoryInterface
{
    /**
     * @return mixed
     */
    public function getBesideMyself()
    {
        return User::notMyself()->get();
    }

    /**
     * @return mixed
     */
    public function getProdiUser()
    {
        return User::prodi()->get();
    }

    /**
     * @param $userData
     * @return User|mixed
     */
    public function create($userData)
    {
        $user = new User();
        $user->name = $userData->name;
        $user->email = $userData->email;
        $user->username = $userData->username;
        $user->password = $userData->password;
        $user->role = $userData->role;
        $user->save();

        return $user;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id)
    {
        return User::findOrFail($id)->delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return User::findOrFail($id);
    }

    /**
     * @param $userData
     * @return mixed
     */
    public function update($userData)
    {
        $user = User::findOrFail($userData->id);
        $user->name = $userData->name;
        $user->email = $userData->email;
        $user->username = $userData->username;
        if ($userData->filled('password')) $user->password = $userData->password;
        $user->role = $userData->role;
        $user->save();

        return $user;
    }

    /**
     * @param $userData
     * @return mixed
     */
    public function updatePassword($userData)
    {
        $user = User::findOrFail(Auth::id());
        $user->password = $userData->new_password;
        $user->save();

        return $user;
    }
}
