<?php

namespace App\Http\Requests\APIs\Workers;

use App\Rules\LatitudeLongitudeRule;
use Illuminate\Foundation\Http\FormRequest;

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
