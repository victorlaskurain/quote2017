<?php

namespace AppBundle\Controller;

use AppBundle\Form\CustomerType;
use AppBundle\Form\QuoteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    /**
     * @Route("/api/customers", name="apiCustomerList")
     */
    public function getCustomersAction(Request $request)
    {
        $db = $this->get('app.db');
        $data = $db->getCustomersFilter($request->query->all());
        return new JsonResponse($data);
    }

    /**
     * @Route("/api/customers/{id}",
     *        name="apiGetCustomerById")
     */
    public function getCustomerByIdAction(Request $request, $id)
    {
        $db    = $this->get('app.db');
        $logic = $this->get('app.logic');
        if ($request->isMethod('POST')) {
            $customer = json_decode($request->getContent(), true);
            $form = $this->createForm(CustomerType::class, array());
            $form->submit($customer);
            if (!$form->isValid()) {
                $errors = array();
                foreach ($form->getErrors() as $error) {
                    $errors[] = $error->getMessage();
                }
                return new JsonResponse(implode("\n", $errors), 500);
            }
            if ($id === 'new') {
                unset($customer['id']);
            }
            $id = $logic->addUpdateCustomer($customer);
        }
        return new JsonResponse($db->getCustomerById($id));
    }

    /**
     * @Route("/api/quotes", name="apiQuoteList")
     */
    public function getQuotesAction(Request $request)
    {
        $db = $this->get('app.db');
        $data = $db->getQuotesFilter($request->query->all());
        return new JsonResponse($data);
    }

    /**
     * @Route("/api/quotes/{id}",
     *        name="apiGetQuoteById")
     */
    public function getQuoteByIdAction(Request $request, $id)
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
        return new JsonResponse($logic->getQuoteById($id));
    }

}
