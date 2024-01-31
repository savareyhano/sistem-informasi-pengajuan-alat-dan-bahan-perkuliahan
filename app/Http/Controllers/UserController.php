<?php

namespace App\Http\Controllers;

use App\Http\Repository\UserRepository;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserGetRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth');
        $this->userRepository = $userRepository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        Gate::authorize('access-menu', 'wakil_direktur');

        return view('user.index');
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function datatable()
    {
        $users = $this->userRepository->getBesideMyself();
        return DataTables::of($users)
            ->addColumn('action', function ($user) {
                return "
                    <a href=\"#\" class=\"btn btn-warning btn-sm btn_edit\" title='Edit' data-id=\"{$user->id}\"><i class=\"fas fa-edit\"></i></a>
                    <a href=\"#\" class=\"btn btn-danger btn-sm btn_delete\" title='Delete' data-id=\"{$user->id}\"><i class=\"fas fa-trash-alt\"></i></a>
                ";
            })
            ->editColumn('name', function ($user) {
                return ucwords($user->name);
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('m/d/Y');
            })
            ->make(true);
    }

    /**
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        $user = $this->userRepository->create($request);
        return response()->json(['status' => true, 'message' => 'Success creating user', 'user' => $user], Response::HTTP_CREATED);
    }

    /**
     * @param UserDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(UserDeleteRequest $request)
    {
        $this->userRepository->deleteById($request->id);
        return response()->json(['status' => true, 'message' => 'Success deleting user'], Response::HTTP_OK);
    }

    /**
     * @param UserGetRequest $request
     * @return JsonResponse
     */
    public function get(UserGetRequest $request)
    {
        $user = $this->userRepository->getById($request->id);
        return response()->json(['status' => true, 'user' => $user], Response::HTTP_OK);
    }

    /**
     * @param UserUpdateRequest $request
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request)
    {
        $this->userRepository->update($request);
        return response()->json(['status' => true, 'message' => 'Success updating user'], Response::HTTP_OK);
    }
}
