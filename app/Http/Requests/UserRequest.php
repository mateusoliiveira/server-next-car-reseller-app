<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function genericRules()
    {        
        return [
            'password' => 'required|string|min:6|max:20',
        ];
    }

    public function login()
    {        
        return [
            'email' => 'required|string|email',
        ];
    }

    public function register()
    {        
        return [
            'email' => 'required|string|email|min:10|max:50|unique:users',
            'name' => 'required|string|max:50',

        ];
    }
    
    public function rules()
    {   
        $genericRules = $this->genericRules();
        $withLoginRules = $this->login();
        $withRegisterRules = $this->register();

        if(is_null($this['name']))
        {
            $loginFlow = array_merge($genericRules, $withLoginRules);
            return $loginFlow;
           
        }

        $registerFlow = array_merge($genericRules, $withRegisterRules);
        return $registerFlow;
    }

}
