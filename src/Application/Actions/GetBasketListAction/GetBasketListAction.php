<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 16.11.18
 * Time: 10:33
 */

namespace App\Application\Actions\GetBasketListAction;


use App\Domain\Basket\BasketRepositoryContract;

class GetBasketListAction
{
    private $basketRepository;

    public function __construct(BasketRepositoryContract $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    public function execute(GetBasketListRequest $basketListRequest): GetBasketListResponse
    {
        $basketList = $this->basketRepository->getAll();

        return new GetBasketListResponse($basketList);

    }
}