<?php

namespace AppBundle\Controller;

use AppBundle\Form\QuoteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * This controller serves the two pages that make up the
 * application. The homepage action's sole function is redirecting the
 * user to the quoteList page.
 */
class QuotesController extends Controller
{
    /**
     * @Route("/customers", name="customerList")
     */
    public function getCustomersAction(Request $request)
    {
        return $this->render('quotes/customers.html.twig');
    }

    /**
     * @Route("/quotes", name="quoteList")
     */
    public function getQuotesAction(Request $request)
    {
        return $this->render('quotes/quotes.html.twig');
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('quoteList');
    }

}
