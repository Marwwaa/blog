<?php

namespace App\Modules\Customers\Controllers\Site;

use Illuminate\Http\Request;
use HZ\Illuminate\Mongez\Managers\ApiController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class NotificationsController extends ApiController
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
        return $this->success([
            'notifications' => [
                [
                    'id' => 12,
                    'type' => 'order',
                    'title' => 'تم إضافة طلبكم بنجا',
                    'content' => 'سيتم مراجعة الطلب و بدأ التنفيذ فيه في أقرب وقت',
                    'image' => null,
                    'createdAt' => [
                        'format' => date('d-m-Y h:i:s a'),
                        'timestamp' => time(),
                    ],
                    'extra' => [
                        'orderId' => 421,
                    ]
                ]
            ],
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
