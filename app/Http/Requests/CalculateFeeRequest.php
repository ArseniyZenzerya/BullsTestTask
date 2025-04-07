<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Http\Exceptions\HttpResponseException;


    class CalculateFeeRequest extends FormRequest
    {
        public function rules(): array
        {
            return [
                'destination' => 'required|string',
                'weight' => 'required|numeric|min:0',
                'delivery_type' => 'required|in:standard,express',
            ];
        }

        public function authorize(): bool
        {
            return true;
        }

        protected function failedValidation(Validator $validator)
        {
            throw new HttpResponseException(response()->json([
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422));
        }
    }
