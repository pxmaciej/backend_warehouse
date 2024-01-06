<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\ProductValidatorException;
use App\Validator\ProductValidator;
use Exception;
use Illuminate\Http\JsonResponse;
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

    private $categoryRepository;

    public function __construct(
        ProductInterface $productRepository,
        ProductValidator $productValidator,
        CategoryInterface $categoryRepository
    ) {
        $this->middleware('auth:api');

        $this->productRepository = $productRepository;
        $this->productValidator = $productValidator;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $product = $this->productRepository->getAll();

        return response()->json($product, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = $this->productValidator->nameCategoryCompanyAMountPriceRequired($request);

            if ($validator) {
                $product = $this->productRepository->setData($request);
                $categories = $this->categoryRepository->findMany($request['categories']);

                $product->categories()->attach($categories);
            }

        } catch (ProductValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($product, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $validator = $this->productValidator->validateId($id);

            if ($validator) {
                $orderList = $this->productRepository->show($id);
            }
        } catch (ProductValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($orderList, 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function order(int $id): JsonResponse
    {
        try {
            $validator = $this->productValidator->validateId($id);

            if ($validator) {
                $products = $this->productRepository->showProductByIdRelationToOrder($id);
            }
        } catch (ProductValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($products, 200);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {

            $validatorId = $this->productValidator->validateId($id);

            if ($validatorId) {
                if (isset($request['categories'][0]['id'])) {
                    $categoryIds = collect($request->categories)->pluck('id');
                    $categories = $this->categoryRepository->findMany($categoryIds);
                } else {
                    $categories = $this->categoryRepository->findMany($request['categories']);
                }

                $product = $this->productRepository->update($id, $request);
                $product->categories()->detach();
                $product->categories()->attach($categories);
            }
        } catch (ProductValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($product, 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $validatorId = $this->productValidator->validateId($id);

            if ($validatorId) {
                $this->productRepository->destroy($id);
            }
        }catch (ProductValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json(null, 200);
    }
}
