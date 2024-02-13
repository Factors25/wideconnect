<?php

namespace App\Service\Datatable;

use Symfony\Component\HttpFoundation\Request;

class DatatableService
{
    public function init(Request $request): array
    {
        $parameters = $request->request->all();

        $draw = $parameters['draw'];
        $start = $parameters['start'];
        $length = $parameters['length'];
        $search = $parameters['search'];
        $orders = $parameters['order'];
        $columns = $parameters['columns'];

        foreach ($orders as $key => $order) {
            $orders[$key]['name'] = $columns[$order['column']]['name'];
        }

        return [
            'draw' => $draw,
            'start' => $start,
            'length' => $length,
            'search' => $search,
            'orders' => $orders,
            'columns' => $columns
        ];
    }
}