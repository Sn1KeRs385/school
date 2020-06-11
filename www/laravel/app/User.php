<?php

namespace App;

use App\Models\Relation;
use App\Models\Role;
use App\Models\Specialization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    public static function findForPassport($username)
    {
        return User::whereLogin($username)->first();
    }

    public function validateForPassportPasswordGrant($data)
    {
        return true;
    }

    public function createToken()
    {
        $oauth_client = DB::table('oauth_clients')
            ->where('personal_access_client', 0)
            ->first();

        if (!$oauth_client) {
            throw new \Exception('oauth_client not found');
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => $oauth_client->id,
            'client_secret' => $oauth_client->secret,
            'username' => $this->login,
            'password' => 'null',
            // 'scope' => '*',`
        ];
        $request = Request::create('/oauth/token', 'POST', $data);
        $response = app()->handle($request)->getContent();

        return json_decode($response);
    }

    function refreshToken($refreshToken)
    {
        $oauth_client = DB::table('oauth_clients')
            ->where('personal_access_client', 0)
            ->first();

        if (!$oauth_client) {
            throw new \Exception('oauth_client not found');
        }

        $data = [
            'grant_type' => 'refresh_token',
            'client_id' => $oauth_client->id,
            'client_secret' => $oauth_client->secret,
            'refresh_token' => $refreshToken,
            'scope' => '',
        ];
        $request = Request::create('/oauth/token', 'POST', $data);
        $response = app()->handle($request)->getContent();

        return json_decode($response);
    }

    function roles(){
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    function specializations(){
        return $this->belongsToMany(Specialization::class, 'teacher_specializations');
    }

    function relationStudents(){
        return $this->belongsToMany(User::class, 'user_relations', 'parent_id', 'student_id')
            ->withPivot('relation_id');
    }

    function relationParents(){
        return $this->belongsToMany(User::class, 'user_relations', 'student_id', 'parent_id')
            ->withPivot('relation_id');
    }

    public $fillable = [
        'last_name',
        'first_name',
        'patronymic',
        'login',
        'email',
        'password',
        'phone',
        'birth_date',
    ];

    protected $hidden = [
        'password',
    ];

}