<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIs\StoreWorkerRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class WorkerController extends Controller
{
    public function __construct(private User $user)
    {
    }

    /**
     * @param StoreWorkerRequest $storeWorkerRequest
     * @return JsonResponse
     */
    public function store(StoreWorkerRequest $storeWorkerRequest): JsonResponse
    {
        $user = $this->user->create($storeWorkerRequest->validated());

        $userToken = $user->createToken('worker',['worker_access'])->plainTextToken;

        return response()->json([
            'message' => 'Worker created successfully',
            'token' => $userToken,
        ], 200);
    }
}
