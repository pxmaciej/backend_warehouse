<?php

namespace App\Http\Controllers;

use App\Exceptions\OrderListValidatorException;
use App\Validator\OrderListValidator;
use Exception;
use Illuminate\Http\Request;

class OrderListController extends Controller
{
    /**
     * @var OrderListInterface
     */
    private $orderListRepository;

    /**
     * @var OrderListValidator
     */
    private $orderListValidator;

    public function __construct(OrderListInterface $orderListRepository, OrderListValidator $orderListValidator)
    {
        $this->middleware('auth:api');
        $this->orderListRepository = $orderListRepository;
        $this->orderListValidator = $orderListValidator;
    }
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $order = $this->orderListRepository->getAll();

        return response()->json($order, 200);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->orderListValidator->productIdOrderIdAmountPriceRequired($request);

            if ($validator) {
                $orderList = $this->orderListRepository->setData($request);
            }
        } catch (OrderListValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($orderList, 200);

    }

    /**
     * @param  \App\Models\OrderList $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->orderListValidator->validateId($id);

            if ($validator) {
                $orderList = $this->orderListRepository->show($id);
            }
        } catch (OrderListValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($orderList, 200);
    }

    /**
     * @param  \App\Models\OrderList $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function order(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->orderListValidator->validateId($id);

            if ($validator) {
                $productList = $this->orderListRepository->showProductByIdRelationToOrder($id);
            }
        } catch (OrderListValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($productList, 200);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderList $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {

            $validatorId = $this->orderListValidator->validateId($id);

            if ($validatorId) {
                $orderList = $this->orderListRepository->update($id, $request);
            }

        } catch (OrderListValidatorException $e) {
            return response()->json($e->getMessage(),400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($orderList, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderList  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validatorId = $this->orderListValidator->validateId($id);

            if ($validatorId) {
                $orderList = $this->orderListRepository->destroy($id);
            }
        }catch (OrderListValidatorException $e) {
            return response()->json($e->getMessage(),400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json(null, 200);
    }
}
