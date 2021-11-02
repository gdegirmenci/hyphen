<?php

namespace App\Http\Requests\API;

use App\Entities\Order;
use App\Http\Requests\Request;
use App\ValueObjects\Item;

/**
 * Class GetDiscountRequest
 * @package App\Http\Requests\API
 */
class GetDiscountRequest extends Request
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|string',
            'customer-id' => 'required|string',
            'items' => 'required|array',
            'total' => 'required|string',
        ];
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return new Order($this->get('id'), $this->get('customer-id'), $this->getItems(), (float)$this->get('total'));
    }

    /**
     * @return array
     */
    protected function getItems(): array
    {
        $items = collect();

        foreach ($this->get('items') as $item) {
            $items->push(
                new Item(
                    $item['product-id'],
                    $item['quantity'],
                    $item['unit-price'],
                    $item['total']
                )
            );
        }

        return $items->toArray();
    }
}
