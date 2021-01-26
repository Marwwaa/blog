<?php
namespace App\Modules\Customers\Controllers\Site;

use Illuminate\Http\Request;
use HZ\Illuminate\Mongez\Managers\ApiController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateAccountController extends ApiController
{
    /**
     * Repository name
     * 
     * @var string
     */
    protected $repository = 'customers';

    /**
     * {@inheritDoc}
     */
    public function index(Request $request)
    {
        $validator = $this->isValid($request);

        $customer = user();
        if (! $validator->passes()) {
            return $this->badRequest($validator->errors());
        } elseif($request->password && ! $customer->isMatchingPassword($request->oldPassword)) {
            return $this->badRequest(trans('auth.invalidPassword'));
        } else {
            $customer = $this->repository->update($customer->id, $request);
        }

        return $this->success([
            'customer' => $this->repository->wrap($customer)
        ]);
    }

    /**
     * Add new device token to current customer
     * 
     * @param Request $request
     * @return Response
     */
    public function addDeviceToken(Request $request)
    {
        if ($request->device) {
            $this->customersRepository->addNewDeviceToken(user(), $request->device);
            return $this->success();
        } else {
            return $this->badRequest(trans('validation.required', 'device'));
        }
    }
    
    /**
     * Add new device token to current customer
     * 
     * @param Request $request
     * @return Response
     */
    public function removeDeviceToken(Request $request)
    {
        if ($request->device) {
            $this->customersRepository->removeDeviceToken(user(), $request->device);
            return $this->success();
        } else {
            return $this->badRequest(trans('validation.required', 'device'));
        }
    }
    
    /**
     * Determine whether the passed values are valid
     *
     * @return mixed
     */
    private function isValid(Request $request)
    {
        $user = user();

        $table = $this->repository->getTableName();

        return Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'confirmed|min:8',
            'email' => [
                'required', 
                Rule::unique($table)->ignore($user->email, 'email'),
            ],
            'phoneNumber' => [
                'required', 
                Rule::unique($table)->ignore($user->phoneNumber, 'phoneNumber'),
            ],
        ]);
    }
}