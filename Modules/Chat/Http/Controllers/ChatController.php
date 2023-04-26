<?php

namespace Modules\Chat\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Chat\Http\Requests\ChangeAvatarRequest;
use Modules\Chat\Http\Requests\ChangePasswordRequest;
use Modules\Chat\Http\Requests\GetStaffChatRequest;
use Modules\Chat\Http\Requests\ProfileRequest;
use Modules\Chat\Http\Requests\RegisterRequest;
use Modules\Chat\Http\Requests\RemoveUserRequest;
use Modules\Chat\Http\Requests\UpdateInfoRequest;
use Modules\Chat\Http\Requests\UpdateProfileRequest;
use Modules\Chat\Repositories\ChatInterface;
use Exception;

class ChatController extends Controller
{
    protected $chatRepo;

    public function __construct(ChatInterface $chatRepo)
    {
        $this->chatRepo = $chatRepo;
    }

    /**
     * Lấy ca làm việc hiện tại của nhân viên
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function registerAction(RegisterRequest $request){
        try {
            $all = $request->all();
            $all['staff_token'] = $request->headers->get('staff-token');
            $all['chat_token'] = $request->headers->get('chat-token');
            $all['branch_code'] = $request->headers->get('brand-code');
            $data = $this->chatRepo->register($all);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Đổi mật khẩu
     *
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function updateProfileAction(UpdateProfileRequest $request){
        try {
            $all = $request->all();
            $all['staff_token'] = $request->headers->get('staff-token');
            $all['chat_token'] = $request->headers->get('chat-token');
            $all['branch_code'] = $request->headers->get('brand-code');

            $data = $this->chatRepo->updateProfile($all);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Đổi mật khẩu
     *
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePasswordAction(ChangePasswordRequest $request){
        try {
            $all = $request->all();
            $all['staff_token'] = $request->headers->get('staff-token');
            $all['chat_token'] = $request->headers->get('chat-token');
            $all['branch_code'] = $request->headers->get('brand-code');
            $data = $this->chatRepo->changePassword($all);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Đổi avatar
     *
     * @param ChangeAvatarRequest $request
     * @return JsonResponse
     */
    public function changeAvatarAction(ChangeAvatarRequest $request){
        try {
            $all = $request->all();
            $all['chat_token'] = $request->headers->get('chat-token');
            $all['branch_code'] = $request->headers->get('brand-code');
            $data = $this->chatRepo->changeAvatar($all);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Xóa user
     *
     * @param RemoveUserRequest $request
     * @return JsonResponse
     */
    public function removeUserAction(RemoveUserRequest $request){
        try {
            $all = $request->all();
            $all['staff_token'] = $request->headers->get('staff-token');
            $all['chat_token'] = $request->headers->get('chat-token');
            $all['branch_code'] = $request->headers->get('brand-code');
            $data = $this->chatRepo->removeUser($all);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy thông tin hồ sơ
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function profileWebAction(Request $request)
    {
        try {
            $all['staff_token'] = $request->headers->get('staff-token');
            $all['chat_token'] = $request->headers->get('chat-token');
            $all['branch_code'] = $request->headers->get('brand-code');

            $data = $this->chatRepo->getProfileWeb($all);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy thông tin hồ sơ
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function profileAction(Request $request)
    {
        try {
            $all['staff_token'] = $request->headers->get('staff-token');
            $all['chat_token'] = $request->headers->get('chat-token');
            $all['branch_code'] = $request->headers->get('brand-code');

            $data = $this->chatRepo->getProfile($all);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy DS nhân viên có quyền chat
     *
     * @param GetStaffChatRequest $request
     * @return JsonResponse
     */
    public function getStaffChat(GetStaffChatRequest $request)
    {
        try {
            $data = $this->chatRepo->getStaffChat($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }
}
