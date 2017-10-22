<?php

namespace AppBundle\Controller;

use AppBundle\Form\QuoteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/{_locale}/quotes", name="quoteList")
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
