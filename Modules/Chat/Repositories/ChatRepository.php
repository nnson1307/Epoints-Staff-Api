<?php


namespace Modules\Chat\Repositories;


use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Http\Api\ChatServiceApi;
use Modules\Chat\Models\AdminServiceBrandFeatureChildTable;
use Modules\Chat\Models\MapRoleGroupStaffTable;
use Modules\Chat\Models\PageTable;
use Modules\Chat\Models\StaffTable;
use Modules\TimeKeeping\Models\CheckInLogTable;
use Modules\TimeKeeping\Models\CheckOutLogTable;
use Modules\TimeKeeping\Models\TimeWorkingStaffTable;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use MyCore\Repository\PagingTrait;

class ChatRepository implements ChatInterface
{

    protected $staffTable;
    protected $chatServiceApi;

    public function __construct(ChatServiceApi $chatServiceApi, StaffTable $staffTable)
    {
        $this->chatServiceApi = $chatServiceApi;
        $this->staffTable = $staffTable;
    }

    /**
     * Đăng kí tài khoản mới
     *
     * @param array $all
     * @return mixed
     */
    public function register(array $all)
    {
        $user = $this->staffTable->getInfoUserLogin($all['staff_id']);

        if (empty($user)) {
            throw new \Exception(__("Không tìm thấy thông tin nhân viên"));
        }

        $lastIndex = strripos($user['full_name'], ' ');
        $firstName = $user['full_name'];
        $lastName = "";
        if ($lastIndex !== false && $lastIndex >= 0) {
            $lastName = substr($user['full_name'], $lastIndex + 1);
            $firstName = substr($user['full_name'], 0, $lastIndex);
        }
        // if($lastName == ""){
        //     $lastName = $firstName;
        // }
        $email = (isset($user['email']) && $user['email'] != "") ? $user['email'] : $user['user_name'];

        if (!str_contains($email, '@')) {
            $email = $email . '@pioapps.vn';
        }
        $this->chatServiceApi->register(
            $all['branch_code'],
            [
                "staffId" => $user['staff_id'],
                "username" => $user['user_name'],
                "email" => $email,
                "firstName" => $firstName,
                "lastName" => $lastName,
                "phone" => $user['phone1'] ?? $user['phone2'],
                "password" => $all['password'] ?? '123456a@',
                "repeatPassword" => $all['password'] ?? '123456a@',
            ]);
        return $user;
    }

    public function updateProfile(array $all)
    {
        $user = $this->staffTable->getInfoUserLogin($all['staff_id']);

        if (empty($user)) {
            throw new \Exception(__("Không tìm thấy thông tin nhân viên"));
        }

        $lastIndex = strripos($user['full_name'], ' ');
        $firstName = $user['full_name'];
        $lastName = "";
        if ($lastIndex !== false && $lastIndex >= 0) {
            $lastName = substr($user['full_name'], $lastIndex + 1);
            $firstName = substr($user['full_name'], 0, $lastIndex);
        }

        $email = (isset($user['email']) && $user['email'] != "") ? $user['email'] : $user['user_name'];
        if (!str_contains($email, '@')) {
            $email = $email . '@pioapps.vn';
        }

        $this->chatServiceApi->updateProfile(
            $all['branch_code'], $all['staff_token'],
            [
                'staffId' => $all['staff_id'],
                "email" => $email,
                "firstName" => $firstName,
                "lastName" => $lastName,
            ]);
        return $user;
    }

    public function changePassword(array $all)
    {
        $user = Auth::user();
        $result = $this->chatServiceApi->changePassword($all['branch_code'], $all['chat_token'], $all['password']);
        if ($result['status'] == "success") {
            return $user;
        }
        throw new \Exception("Đổi mật khẩu thất bại");
    }

    public function changeAvatar(array $all)
    {
        try {

            $imageFile = getimagesize($all['avatar']);

            if ($imageFile == false) {
                throw new ChatRepoException(ChatRepoException::FILE_NOT_TYPE);
            }

            $fileSize = number_format(filesize($all['avatar']) / 1048576, 2); //MB

            if ($fileSize > 20) {
                throw new ChatRepoException(ChatRepoException::MAX_FILE_SIZE);
            }
            $result = $this->chatServiceApi->uploadImage($all['branch_code'], $all['chat_token'], request()->file('avatar'));

            $changeAvatarResult = null;
            if (isset($result['_id'])) {
                $changeAvatarResult = $this->chatServiceApi->changeAvatar($all['branch_code'], $all['chat_token'], $result['_id']);
            }
            if (empty($changeAvatarResult)) {
                throw new ChatRepoException(ChatRepoException::GET_UPLOAD_FILE_FAILED);
            }
            return [
                "image_id" => $changeAvatarResult['_id'],
                "image_name" => $changeAvatarResult['name'],
            ];

        } catch (\Exception | QueryException $exception) {
            throw new ChatRepoException(ChatRepoException::GET_UPLOAD_FILE_FAILED);
        }
    }

    /**
     * Xóa user chat
     *
     * @param array $all
     * @return mixed
     */
    public function removeUser(array $all)
    {
        $user = Auth::user();
        $result = $this->chatServiceApi->removeUser($all['branch_code'], $all['staff_token'], $all['staff_id']);
        return $user;
    }

    /**
     * Lấy thông tin hồ sơ
     *
     * @param $input
     * @return mixed|void
     * @throws ChatRepoException
     */
    public function getProfile($input)
    {
        try {
            $result = $this->chatServiceApi->profile($input['branch_code'], $input['chat_token']);

            return $result;
        } catch (\Exception | QueryException $exception) {
            throw new ChatRepoException(ChatRepoException::GET_PROFILE_FAILED);
        }
    }

    public function getProfileWeb($input)
    {
        try {
            $result = $this->chatServiceApi->profileWeb($input['branch_code'], $input['staff_token']);

            return $result;
        } catch (\Exception | QueryException $exception) {
            throw new ChatRepoException(ChatRepoException::GET_PROFILE_FAILED);
        }
    }

    /**
     * Lấy DS có quyền chat
     *
     * @param $input
     * @return mixed|void
     * @throws ChatRepoException
     */
    public function getStaffChat($input)
    {
        try {
            $mStaff = app()->get(StaffTable::class);

            $arrStaff = [];

            //Lấy ds nhân viên
            $getStaff = $mStaff->getStaff();


            if (count($getStaff) > 0) {
                $mFeatureChild = app()->get(AdminServiceBrandFeatureChildTable::class);
                $mapRoleGroupStaff = app()->get(MapRoleGroupStaffTable::class);
                $mPage = app()->get(PageTable::class);

                $arrService = [];
                $arrServiceApp = [];

                //Lấy bảng quyền dịch vụ được cấp cho brand
                $permissionService = $mFeatureChild->getPermissionChatPortal();

                if ($permissionService != null) {
                    $arrService [] = $permissionService['feature_code'];
                }

                //Lấy quyền app được cấp cho brand
                $permissionServiceApp = $mFeatureChild->getPermissionChatApp();

                if ($permissionServiceApp != null) {
                    $arrServiceApp [] = $permissionServiceApp['feature_code'];
                }

                foreach ($getStaff as $v) {
                    if ($v['is_admin'] == 1) {
                        //Lấy quyền page
                        $getRolePage = $mPage->getAllRoute($arrService);
                    } else {
                        //Lấy quyền page
                        $getRolePage = $mapRoleGroupStaff->getRolePageByStaff($v['staff_id'], $arrService);
                    }

                    //Lấy ds quyền của nhân viên
                    $getRoleAction = $mapRoleGroupStaff->getAllRoleActionByStaff($v['staff_id'], $arrServiceApp);

                    if (count($getRolePage) > 0) {
                        $arrStaff['web'] [] = $v;
                    }

                    if (count($getRoleAction) > 0) {
                        $arrStaff['app'] [] = $v;
                    }
                }

            }

            return $arrStaff;
        } catch (\Exception | QueryException $e) {
            throw new ChatRepoException(ChatRepoException::GET_STAFF_CHAT_FAILED, $e->getMessage());
        }
    }
}
