<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Repositories\ChatHub\ChatHubRepoInterface;
use Modules\ChatHub\Repositories\ChatHub\ChatHubRepoException;
class ChatHubController extends Controller
{
    protected $chatHubRepo;

    public function __construct(
        ChatHubRepoInterface $chatHubRepo
    ) {
        $this->chatHubRepo = $chatHubRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getCustomer(Request $request)
    {
        try {
            $data = $this->chatHubRepo->getCustomer($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ChatHubRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}
