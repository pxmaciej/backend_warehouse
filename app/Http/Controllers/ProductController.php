<?php

namespace App\Http\Controllers;

use App\Exceptions\ProductValidatorException;
use App\Validator\ProductValidator;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @var ProductInterface
     */
    private $productRepository;

    /**
     * @var ProductValidator
     */
    private $productValidator;

    public function __construct(ProductInterface $productRepository, ProductValidator $productValidator)
    {
        $this->middleware('auth:api');

        $this->productRepository = $productRepository;
        $this->productValidator = $productValidator;

    }
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $product = $this->productRepository->getAll();

        return response()->json($product, 200);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->productValidator->nameCategoryCompanyAMountPriceRequired($request);

            if ($validator) {
                $product = $this->productRepository->setData($request);
            }
        } catch (ProductValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($product, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->productValidator->validateId($id);

            if ($validator) {
                $orderList = $this->productRepository->show($id);
            }
        } catch (ProductValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($orderList, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function order(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->productValidator->validateId($id);

            if ($validator) {
                $products = $this->productRepository->showProductByIdRelationToOrder($id);
            }
        } catch (ProductValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($products, 200);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        try {

            $validatorId = $this->productValidator->validateId($id);

            if ($validatorId) {
                $product = $this->productRepository->update($id, $request);
            }
        } catch (ProductValidatorException $e) {
            return response()->json($e->getMessage(),400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($product, 200);
    }

    /**
     * @param  \App\Models\Product $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validatorId = $this->productValidator->validateId($id);

            if ($validatorId) {
                $product = $this->productRepository->destroy($id);
            }
        }catch (ProductValidatorException $e) {
            return response()->json($e->getMessage(),400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json(null, 200);
    }
}
