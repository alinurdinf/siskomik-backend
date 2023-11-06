<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Laratrust\Models\Role;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'identifier' => 'required',
            'username' => 'required',
            'account' => 'required',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        if ($input['account'] == 'MAHASISWA') {
            $role = Role::where('name', 'mahasiswa')->first();
        } else {
            $role = Role::where('name', 'dosen')->first();
        }
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'username' => $input['username'],
            'identifier' => $input['identifier'],
            'password' => Hash::make($input['password']),
        ]);

        $user->syncRolesWithoutDetaching([$role]);

        return $user;
    }
}
