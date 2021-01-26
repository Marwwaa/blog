<?php
namespace App\Modules\Customers\Controllers\Site\Auth;

use App\Modules\Customers\Models\Customer;
use Validator;
use Illuminate\Http\Request;
use HZ\Illuminate\Mongez\Managers\ApiController;

class RegisterController extends ApiController
{
    /**
     * Create new users
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        // Customer::where('id', '!=', -1)->delete();
        $validator = $this->scan($request);

        if ($validator->passes()) {
            $usersRepository = $this->{config('app.users-repo')};
            $user = $usersRepository->create($request);
            $userInfo = $usersRepository->wrap($user)->toArray($request);
            $userInfo['accessToken'] = $user->accessTokens[0]['token'];

            if ($request->device) {
                $this->customersRepository->addNewDeviceToken($user, $request->device);
            }

            return $this->success([
                'customer' => $userInfo,
            ]);
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
            'name' => 'required',
            'password' => 'required|min:8',
            'phoneNumber' => 'required|unique:' . config('app.user-type'),
            'email' => 'required|unique:' . Customer::getTableName(),
        ]);
    }
}
