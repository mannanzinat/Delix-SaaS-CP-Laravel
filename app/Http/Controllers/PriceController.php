<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PlanRepository;


class PriceController extends Controller
{
    protected $planRepository;

    public function __construct(PlanRepository $planRepository)
    {
        $this->planRepository          = $planRepository;

    }
    public function index()
    {
            $data             = [
                'plans'             => $this->planRepository->all(),
                'plans2'            => [
                    'monthly'       => $this->planRepository->activePlans([], 'monthly'),
                    'yearly'        => $this->planRepository->activePlans([], 'yearly'),
                    'life_time'     => $this->planRepository->activePlans([], 'lifetime'),
                ],
            ];
        return view('website.price', $data);
    }
}
