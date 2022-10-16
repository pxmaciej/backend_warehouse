<?php

namespace App\Http\Controllers;

use App\Exceptions\CategoryValidatorException;
use App\Exceptions\NotFoundException;
use App\Validator\CategoryValidator;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @var CategoryInterface
     */
    private $categoryRepository;

    /**
     * @var CategoryValidator
     */
    private $categoryValidator;

    public function __construct(CategoryInterface $categoryRepository, CategoryValidator $categoryValidator)
    {
        $this->middleware('auth:api');

        $this->categoryRepository = $categoryRepository;
        $this->categoryValidator = $categoryValidator;

    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $categories = $this->categoryRepository->getAll();

        return response()->json($categories, 200);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->categoryValidator->categoryNameAndDescriptionRequired($request);

            if ($validator) {
                $category = $this->categoryRepository->setData($request);
            }
        } catch (CategoryValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($category, 200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->categoryValidator->validateId($id);

            if ($validator) {
                $category = $this->categoryRepository->show($id);
            }
        } catch (CategoryValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($category, 200);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {

            $validatorId = $this->categoryValidator->validateId($id);

            if ($validatorId) {
                $category = $this->categoryRepository->update($id, $request);
            }

        } catch (CategoryValidatorException $e) {
            return response()->json($e->getMessage(),400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($category, 200);
    }

    /**
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validatorId = $this->categoryValidator->validateId($id);

            if ($validatorId) {
                $this->categoryRepository->destroy($id);
            }
        }catch (CategoryValidatorException $e) {
            return response()->json($e->getMessage(),400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json(null, 200);
    }
}
