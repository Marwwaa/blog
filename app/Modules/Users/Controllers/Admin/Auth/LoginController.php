<?php
namespace App\Modules\Users\Controllers\Admin\Auth;

use Validator;
use Illuminate\Http\Request;
use HZ\Illuminate\Mongez\Managers\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends ApiController
{
    /**
     * Login the user
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $validator = $this->scan($request);
        if ($validator->passes()) {
            $user = $this->usersRepository->getByModel('email', $request->email);
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return $this->unauthorized(trans('auth.invalidData'));
            } else {
                Auth::login($user);

                $usersRepository = $this->{config('app.users-repo')};

                $accessToken = $usersRepository->generateAccessToken($user, $request);

                $userInfo = $usersRepository->wrap($user)->toArray($request);

                $userInfo['accessToken'] = $accessToken;

                return $this->success([
                    'user' => $userInfo,
                ]);
            }
        } else {
            return $this->badRequest($validator->errors());
        }
    }

    /**
     * Determine whether the passed values are valid
     *
     * @return mixed
     */
    private function scan(Request $request)
    {
        return Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:8',
        ]);
    }
    
    /**
     * Login the user
     *
     * @return mixed
     */
    public function logout(Request $request)
    {
        $user = user();
        $accessTokens = $user->accessTokens;

        $currentAccessToken = $request->authorizationValue();

        foreach ($accessTokens as $key => $accessToken) {
            if ($accessToken['token'] == $currentAccessToken) {
                unset($accessTokens[$key]);
                break;
            }
        }

        $user->accessTokens = array_values($accessTokens);

        $user->save();

        return $this->success();
    }
}