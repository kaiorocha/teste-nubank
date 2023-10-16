<?php

namespace App\Entity;

use App\Helper\JsonValidator;

/**
 * Operation Entity
 */
class Operation
{
    /**
     * @var
     */
    private $operations;

    /**
     * @var int
     */
    private $exception_limit = 20000;

    /**
     * @var float
     */
    private $rate = 0.2;

    /**
     * @param $object
     */
    public function __construct($object)
    {
        $this->operations = $object;
    }

    /**
     * @return array
     */
    public function process(): array
    {
        $response = [];
        $current_avg = $current_quantity = $performance = 0;

        foreach ($this->operations as $row => $operation) {

            $validator = new JsonValidator();
            $validator = $validator->validator($operation);

            if (!$validator->isValid()) {
                $response[] = ['error' => "Json invalido na linha {$row}"];
                break;
            }

            $operation = (array) $operation;

            switch ($operation['operation'])
            {
                case 'sell':
                    $response[] = $this->sellOperation($operation, $performance, $current_avg, $current_quantity);
                    break;
                case 'buy':
                    $response[] = $this->buyOperation($operation, $current_avg, $current_quantity);
                    break;
                default:
                    return ['error' => "Operacao invalida na linha {$row}"];
                    break;
            }
        }

        return $response;
    }

    /**
     * @param array $operation
     * @param $performance
     * @param $current_avg
     * @param $current_quantity
     * @return float[]
     */
    private function sellOperation(array $operation, &$performance, &$current_avg, &$current_quantity): array
    {
        $total = $operation['quantity'] * $operation['unit-cost'];

        $this->performanceCalculate($performance, $current_avg, $operation, $total);

        $fees = ["tax" => $this->taxCalculate($performance, $total)];

        $this->currentQtyUpdate($current_quantity, $operation['quantity'], "sub");

        $this->clearValues($current_quantity, $current_avg, $performance);

        return $fees;
    }

    /**
     * @param array $operation
     * @param $current_avg
     * @param $current_quantity
     * @return float[]
     */
    private function buyOperation(array $operation, &$current_avg, &$current_quantity): array
    {
        $current_avg = (($current_quantity * $current_avg) + ($operation['quantity'] * $operation['unit-cost'])) / ($current_quantity + $operation['quantity']);

        $this->currentQtyUpdate($current_quantity, $operation['quantity'], "sum");

        return ["tax" => $this->taxCalculate()];
    }

    /**
     * @param $performance
     * @param $total
     * @return float
     */
    private function taxCalculate($performance = 0, $total = 0): float
    {
        if ($performance >= 0 && $total > $this->exception_limit) {
            return $performance * $this->rate;
        }

        return 0;
    }

    /**
     * @param $performance
     * @param $current_avg
     * @param $operation
     * @param $total
     * @return void
     */
    private function performanceCalculate(&$performance, $current_avg, $operation, $total): void
    {
        $performance -= ($current_avg * $operation['quantity']) - $total;
    }

    /**
     * @param $current_quantity
     * @param $quantity
     * @param $operator
     * @return void
     */
    private function currentQtyUpdate(&$current_quantity, $quantity, $operator): void
    {
        if ($operator == 'sum') {
            $current_quantity += $quantity;
        } else {
            $current_quantity -= $quantity;
        }
    }

    /**
     * @param $current_quantity
     * @param $current_avg
     * @param $performance
     * @return void
     */
    private function clearValues(&$current_quantity, &$current_avg, &$performance): void
    {
        if ($current_quantity == 0) {
            $current_avg = 0;
            $performance = 0;
        }
    }
}