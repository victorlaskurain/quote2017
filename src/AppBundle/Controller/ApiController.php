<?php

namespace AppBundle\Controller;

use AppBundle\Form\CustomerType;
use AppBundle\Form\QuoteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * This controller implements a web service. This web service provides
 * an API to implement the application's GUI.
 */
class ApiController extends Controller
{

    /**
     * Deletes the specified quotes.
     *
     * Requires the ids of the quotes to delete encoded as JSON list
     * in the request data.
     *
     * @Route("/api/delete_quotes", name="apiDeleteQuotes")
     * @Method("POST")
     */
    public function deleteQuotesAction(Request $request)
    {
        $db  = $this->get('app.db');
        $ids = json_decode($request->getContent(), true);
        $db->deleteQuotes($ids);
        return new JsonResponse();
    }

    /**
     * Returns the list of the customers filtered according to the
     * request parameters. The request parameters are in the format
     * sent by the Jsgrid component. The response is in the format
     * required by the Jsgrid component. The data formats are flexible
     * enough for most cases and aligning them keeps the
     * implementation simple.
     *
     * @Route("/api/customers", name="apiCustomerList")
     */
    public function getCustomersAction(Request $request)
    {
        $db = $this->get('app.db');
        $data = $db->getCustomersFilter($request->query->all());
        return new JsonResponse($data);
    }

    /**
     * With GET returns a JSON object with the data of the
     * customers. With POST uses the CustomerType form to validate the
     * data and if the validations passes updates the DB.
     *
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
     * This function is analogous to getCustomersAction.
     *
     * @Route("/api/quotes", name="apiQuoteList")
     */
    public function getQuotesAction(Request $request)
    {
        $db = $this->get('app.db');
        $data = $db->getQuotesFilter($request->query->all());
        return new JsonResponse($data);
    }

    /**
     * This functon is analogous to getCustomerByIdAction.
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
