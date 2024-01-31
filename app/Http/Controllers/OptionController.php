<?php

namespace App\Http\Controllers;

use App\Http\Repository\UserRepository;
use App\Http\Requests\User\UserUpdatePasswordRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class OptionController extends Controller
{
    /**
     * OptionController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('option.index');
    }

    /**
     * @param UserUpdatePasswordRequest $request
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function updatePassword(UserUpdatePasswordRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->updatePassword($request);
        return response()->json(['status' => true, 'message' => 'Success updating password', 'user' => $user], Response::HTTP_OK);
    }
}
