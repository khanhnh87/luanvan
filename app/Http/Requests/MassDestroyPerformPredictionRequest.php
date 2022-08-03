<?php

namespace App\Http\Requests;

use App\Models\PerformPrediction;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPerformPredictionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('perform_prediction_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:perform_predictions,id',
        ];
    }
}
