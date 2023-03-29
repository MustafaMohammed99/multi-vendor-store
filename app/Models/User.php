<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Concerns\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;



    protected static function booted()
    {

        static::created(function (User $user) {
            $role = Role::where('name', '=', 'user')->first();
            $user->roles()->attach([$role->id]);
            // $user->roles()->attach($request->roles);

        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }



    public static function rules($id = 0)
    {
        return [
            'user_name' => ['required', 'string', 'min:3', 'max:255',],
            'user_phone_number' => [
                'nullable', 'sometimes', 'digits:10',
                Rule::unique('users', 'phone_number')->ignore($id),
            ],
            'user_email' => 'required|email',
            'user_type' => 'required|in:super-admin,admin,user',
            'user_password' => 'required|sometimes'
        ];
    }
}
