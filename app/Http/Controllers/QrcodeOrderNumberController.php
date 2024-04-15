<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQrcodeOrderNumberRequest;
use App\Main\QrcodeOrderNumber\QrCodeOrderNumberRepositoryInterface;
use Illuminate\Http\Request;
use Mockery\Exception;

class QrcodeOrderNumberController extends Controller
{
    public function __construct(
        protected QrCodeOrderNumberRepositoryInterface $qrCodeOrderNumberRepositoryInterface
    ){}

    public function createAction(StoreQrcodeOrderNumberRequest $request)
    {
        try {
            $this->qrCodeOrderNumberRepositoryInterface->create($request);
            return response()
                ->json("Order number created successfully");
        }catch (Exception $e) {
            return response()
                ->json($e->getMessage(), 500);
        }
    }
}
