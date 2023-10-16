<?php

namespace App\Helper;

use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;

/**
 * JsonValidator Class
 */
class JsonValidator
{
    /**
     * @var array
     */
    private $payload_schema = [
        'type' => 'object',
        'properties' => [
            'operation' => [
                'type' => 'string'
            ],
            'unit-cost' => [
                'type' => 'number'
            ],
            'quantity' => [
                'type' => 'integer'
            ]
        ],
        "required" => [
            'operation',
            'unit-cost',
            'quantity'
        ]
    ];

    /**
     * @param $data
     * @return Validator
     */
    public function validator($data)
    {
        $validator = new Validator();
        $validator->validate(
            $data,
            json_decode(json_encode($this->payload_schema)),
            Constraint::CHECK_MODE_COERCE_TYPES);

        return $validator;
    }
}