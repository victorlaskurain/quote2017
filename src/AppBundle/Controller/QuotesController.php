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
     * @Route("/api/customers", name="apiCustomerList")
     */
    public function apiGetCustomersAction(Request $request)
    {
        $db = $this->get('app.db');
        $data = $db->getCustomersFilter($request->query->all());
        return new JsonResponse($data);
    }

    /**
     * @Route("/api/quotes", name="apiQuoteList")
     */
    public function apiGetQuotesAction(Request $request)
    {
        $db = $this->get('app.db');
        $data = $db->getQuotesFilter($request->query->all());
        return new JsonResponse($data);
    }

    /**
     * @Route("/customers", name="customerList")
     */
    public function getCustomersAction(Request $request)
    {
        return $this->render('quotes/customers.html.twig');
    }

    /**
     * @Route("/api/quotes/{id}",
     *        name="apiGetQuoteById")
     */
    public function apiQuoteByIdAction(Request $request, $id)
    {
        $db    = $this->get('app.db');
        $logic = $this->get('app.logic');
        if ($request->isMethod('POST')) {
            $quote = json_decode($request->getContent(), true);
            $form = $this->createForm(QuoteType::class, array());
            $form->submit($quote);
            if (!$form->isValid()) {
                $errors = array();
                foreach ($form->getErrors() as $error) {
                    $errors[] = $error->getMessage();
                }
                return new JsonResponse(implode("\n", $errors), 500);
            }
            if ($id === 'new') {
                unset($quote['id']);
            }
            $id = $logic->addUpdateQuote($quote);
        }
        return new JsonResponse($db->getQuoteById($id));
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
