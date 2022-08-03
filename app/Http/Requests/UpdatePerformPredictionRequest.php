<?php

namespace App\Http\Requests;

use App\Models\PerformPrediction;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePerformPredictionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('perform_prediction_edit');
    }

    public function rules()
    {
        return [
            'ten' => [
                'string',
                'nullable',
            ],
            'hinh_anh' => [
                'string',
                'required',
            ],
            'kqdd' => [
                'required',
            ],
        ];
    }
}
