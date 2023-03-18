<?php

namespace App\Http\Requests\APIs\Workers;

use App\Rules\LatitudeLongitudeRule;
use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Schema(
 *     schema="ClockInWorkerRequest",
 *     title="Clock-In Worker Request",
 *     description="The request body for storing a new clock-in for a worker.",
 *     type="object",
 *     required={
 *         "worker_id",
 *         "time",
 *         "latitude",
 *         "longitude"
 *     },
 *     @OA\Property(
 *         property="worker_id",
 *         type="integer",
 *         example=123
 *     ),
 *     @OA\Property(
 *         property="time",
 *         type="integer",
 *         example=1647072195
 *     ),
 *     @OA\Property(
 *         property="latitude",
 *         type="number",
 *         example=40.7128
 *     ),
 *     @OA\Property(
 *         property="longitude",
 *         type="number",
 *         example=-74.0060
 *     )
 * )
 */
class ClockInWorkerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'worker_id' => 'required|integer|exists:users,id',
            'time' => 'required|integer|date_format:U|after_or_equal:today|before_or_equal:tomorrow',
            'latitude' => ['required', new LatitudeLongitudeRule],
            'longitude' => ['required', new LatitudeLongitudeRule],
        ];
    }
}
