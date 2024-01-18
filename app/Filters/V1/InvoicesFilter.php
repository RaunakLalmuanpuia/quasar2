<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;

use App\Filters\ApiFilter;

class InvoicesFilter extends ApiFilter
{
  protected $safeParms = [
    'customerID' => ['eq'],
    'amount' => ['eq', 'lt', 'gt', 'lte', 'gte'],
    'status' => ['eq', 'ne'],
    'billedDated' => ['eq', 'lt', 'gt', 'lte', 'gte'],
    'paidDated' => ['eq', 'lt', 'gt', 'lte', 'gte'],
  ];

  protected $columnMap = [
    'customerID' => 'customer_id',
    'billedDated' => 'billed_dated',
    'paidDated' => 'paid_dated',
  ];
  protected $operatorMap = [
    'eq' => '=',
    'lt' => '<',
    'gt' => '>',
    'lte' => '<=',
    'gte' => '>=',
    'ne' => '!='
  ];
}
