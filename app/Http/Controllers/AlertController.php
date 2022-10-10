<?php

namespace App\Http\Controllers;

use App\Exceptions\AlertValidatorException;
use App\Models\Alert;
use App\Http\Controllers\AlertInterface;
use App\Validator\AlertValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlertController extends Controller
{
    /**
     * @var AlertInterface
     */
    private $alertRepository;

    /**
     * @var AlertValidator
     */
    private $alertValidator;

    public function __construct(AlertInterface $alertRepository, AlertValidator $alertValidator)
    {
       // $this->middleware('auth:api');
        $this->alertRepository = $alertRepository;
        $this->alertValidator = $alertValidator;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $alerts = $this->alertRepository->getAll();

        return response()->json($alerts, 200);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = $this->alertValidator->productIdAndNameRequired($request);

            if ($validator) {
                $alerts = $this->alertRepository->setData($request);
            }
        } catch (AlertValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($alerts, 200);
    }

    /**
     * @param  \App\Models\Alert $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $validator = $this->alertValidator->validateId($id);

            if ($validator) {
                $alert = $this->alertRepository->productNameJoinToAlertById($id);
            }
        } catch (AlertValidatorException $e) {
            return response()->json(null,400);
        } catch (Exception $e) {
            return response()->json(null, 500);
        }

        return response()->json($alert, 200);
    }

    /**
     * @param  \App\Models\Alert $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        try {

            $validatorId = $this->alertValidator->validateId($id);

            if ($validatorId) {
                $alert = $this->alertRepository->update($id, $request);
            }

        } catch (AlertValidatorException $e) {
            return response()->json($e->getMessage(),400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($alert, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alert $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $validatorId = $this->alertValidator->validateId($id);

            if ($validatorId) {
                $alert = $this->alertRepository->destroy($id);
            }
        }catch (AlertValidatorException $e) {
            return response()->json($e->getMessage(),400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json(null, 200);
    }
}
