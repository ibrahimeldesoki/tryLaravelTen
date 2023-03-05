<?php

namespace App\Http\Controllers\APIs\Workers;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIs\Workers\LoginWorkerRequest;
use App\Http\Requests\APIs\Workers\StoreWorkerRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 *
 */
class WorkerController extends Controller
{
    /**
     * @param User $user
     */
    public function __construct(private User $user)
    {
    }

    /**
     * Store worker  and return token
     *
     * @param StoreWorkerRequest $storeWorkerRequest
     * @return JsonResponse
     */
    public function store(StoreWorkerRequest $storeWorkerRequest): JsonResponse
    {
        $user = $this->user->create($storeWorkerRequest->validated());

        $userToken = $user->createToken('worker', ['worker_access'])->plainTextToken;

        return response()->json([
            'message' => 'Worker created successfully',
            'token' => $userToken,
        ], 200);
    }

    /**
     * login  worker and return token for future requests
     *
     * @param LoginWorkerRequest $loginWorkerRequest
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginWorkerRequest $loginWorkerRequest): JsonResponse
    {
        $user = User::where('email', $loginWorkerRequest->email)->first();

        if (!$user || !Hash::check($loginWorkerRequest->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->delete();

        $userToken = $user->createToken('worker', ['worker_access'])->plainTextToken;

        return response()->json([
            'message' => "User login successfully",
            'token' => $userToken
        ], 200);
    }
}
