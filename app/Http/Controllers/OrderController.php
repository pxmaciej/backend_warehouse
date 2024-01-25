<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\OrderValidatorException;
use App\Models\Order;
use App\Validator\OrderValidator;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @var OrderInterface
     */
    private $orderRepository;

    /**
     * @var OrderValidator
     */
    private $orderValidator;

    /**
     * @var OrderListInterface
     */
    private $orderListRepository;

    /**
     * @var ProductInterface
     */
    private $productRepository;

    public function __construct
    (
        OrderInterface $orderRepository,
        OrderValidator $orderValidator,
        OrderListInterface $orderListRepository,
        ProductInterface $productRepository,
    )
    {
        $this->middleware('auth:api');

        $this->orderRepository = $orderRepository;
        $this->orderValidator = $orderValidator;
        $this->orderListRepository = $orderListRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $order = $this->orderRepository->getAll();

        return response()->json($order, 200);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->orderValidator->nameBuyerAndDateOrderDeliverRequired($request);

            if ($validator) {
                $order = $this->orderRepository->setData($request);
            }
        } catch (OrderValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($order, 200);

    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->orderValidator->validateId($id);

            if ($validator) {
                $order = $this->orderRepository->show($id);
            }
        } catch (OrderValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        }catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($order, 200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function product(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->orderValidator->validateId($id);

            if ($validator) {
                $order = $this->orderRepository->showOrderByIdRelationToProduct($id);
            }
        } catch (OrderValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        }catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($order, 200);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validatorId = $this->orderValidator->validateId($id);

            if ($validatorId) {
                if ($request->type == "dostawa" && $request->status == 1) {
                    $productsList = $this->orderListRepository->showProductByIdRelationToOrder($id);
                    foreach($productsList as $productList) {
                        $productId = $productList->product_id;
                        $product = $this->productRepository->show($productId);
                        $product->amount = $productList->amount + $product->amount;
                        $this->productRepository->update($product->id, $product);
                    }
                }
                $order = $this->orderRepository->update($id, $request);
            }

        } catch (OrderValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        }catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($order, 200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validatorId = $this->orderValidator->validateId($id);

            if ($validatorId) {
                $this->orderRepository->destroy($id);
            }
        }catch (OrderValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        }catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json(null, 200);
    }
}
