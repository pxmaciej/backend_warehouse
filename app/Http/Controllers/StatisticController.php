<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\StatisticValidatorException;
use App\Validator\StatisticValidator;
use Exception;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    private $statisticRepository;

    private $statisticValidator;

    public function __construct(StatisticInterface $statisticRepository, StatisticValidator $statisticValidator)
    {
       $this->middleware('auth:api');

       $this->statisticRepository = $statisticRepository;
       $this->statisticValidator = $statisticValidator;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $product = $this->statisticRepository->getAll();

        return response()->json($product, 200);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->statisticValidator->nameCategoryCompanyAMountPriceRequired($request);

            if ($validator) {
                $statistic = $this->statisticRepository->setData($request);
            }
        } catch (StatisticValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($statistic, 200);
    }

    /**
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = $this->statisticValidator->validateId($id);

            if ($validator) {
                $statistic = $this->statisticRepository->show($id);
            }
        } catch (StatisticValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($statistic, 200);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validatorId = $this->statisticValidator->validateId($id);

            if ($validatorId) {
                $statistic = $this->statisticRepository->update($id, $request);
            }
        } catch (StatisticValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($statistic, 200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {

        try {
            $validatorId = $this->statisticValidator->validateId($id);

            if ($validatorId) {
                $this->statisticRepository->destroy($id);
            }
        }catch (StatisticValidatorException $e) {
            return response()->json(null,400);
        } catch (NotFoundException $e) {
            return response()->json(null,404);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json(null, 200);
    }
}
