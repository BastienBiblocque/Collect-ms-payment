<?php

// src/Controller/PaymentController.php

namespace App\Controller;

use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
{
    private StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    #[Route('/create-payment-intent', name: 'create_payment_intent', methods: ['POST'])]

    public function createPaymentIntent(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $paymentIntent = $this->stripeService->createPaymentIntent($data['amount'], 'usd');

        return new JsonResponse(['clientSecret' => $paymentIntent->client_secret]);
    }
}
