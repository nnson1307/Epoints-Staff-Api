<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\Authen\LoginCarrierRequest;
use Modules\User\Http\Requests\Authen\LoginFbRequest;
use Modules\User\Http\Requests\Authen\LoginRequest;
use Modules\User\Http\Requests\Authen\LoginServiceRequest;
use Modules\User\Http\Requests\Authen\QuickLoginRequest;
use Modules\User\Http\Requests\Authen\RefreshTokenRequest;
use Modules\User\Repositories\Authen\AuthenRepoException;
use Modules\User\Repositories\Authen\AuthenRepoInterface;
use Modules\User\Repositories\UploadAvatar\UploadAvatarRepoInterface;

/**
 * Class AuthenController
 * @package Modules\User\Http\Controllers
 * @author DaiDP
 * @since Aug, 2019
 */
class AuthenController extends Controller
{
    protected $auth;
    protected $uploadAvatar;


    /**
     * AuthenController constructor.
     * @param AuthenRepoInterface $auth
     */
    public function __construct(
        AuthenRepoInterface $auth,
        UploadAvatarRepoInterface $uploadAvatar
    ) {
        $this->auth = $auth;
        $this->uploadAvatar = $uploadAvatar;
    }

    /**
     * Login app
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginAction(LoginRequest $request)
    {
        $input = $request->all();

        try {
            $data = $this->auth->login($input);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (AuthenRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Refresh token khi mở app lại
     *
     * @param RefreshTokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshTokenAction(RefreshTokenRequest $request)
    {
        $input = $request->all();

        try {
            $data = $this->auth->refreshToken($input);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (AuthenRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Đăng xuất app
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutAction(Request $request)
    {
        $this->auth->logout($request->all());

        return $this->responseJson(CODE_SUCCESS);
    }


    /**
     * Đăng nhập Fb app
     *
     * @param LoginServiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginService(LoginServiceRequest $request)
    {
        $input = $request->all();

        try {
            $data = $this->auth->loginService($input);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (AuthenRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy list quyền của app
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermission()
    {
        try {
            $data = $this->auth->getPermission();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (AuthenRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Đăng nhập nhanh
     *
     * @param QuickLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function quickLogin(QuickLoginRequest $request)
    {
        try {
            $data = $this->auth->quickLogin($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (AuthenRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Đăng ký device token từ portal
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerDeviceToken(Request $request)
    {
        try {
            $data = $this->auth->registerDeviceTokenPortal($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (AuthenRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Upload avatar
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatarAction(Request $request)
    {
        try {
            $data = $this->auth->uploadAvatar($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (AuthenRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Upload file lên thư viện
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(Request $request)
    {
        try {
            $data = $this->auth->uploadFile($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (AuthenRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Login app
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginV2Action(LoginRequest $request)
    {
        $input = $request->all();

        try {
            $data = $this->auth->loginV2($input);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (AuthenRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
     /**
     * Login app
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfoPermissionAction(Request $request)
    {
        try {
            $data = $this->auth->getInfoPerMission($request);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (AuthenRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Xóa user theo policy của Apple
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $this->auth->delete($request->all());

        return $this->responseJson(CODE_SUCCESS);
    }

    /**
     * Upload avatar by app sen link avatar
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImageByAppLinks(Request $request)
    {
        try {
            $data = $this->uploadAvatar->uploadAvatarByAppLinks($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (AuthenRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}
