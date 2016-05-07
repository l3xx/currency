<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Currency;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("AppBundle:Default:index.html.twig")
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        return [];
    }

    /**
     * @Route("/ajax", name="ajax")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function ajaxAction(Request $request)
    {
        $date = new \DateTime($request->get('date', 'now'));
        $currencies = $request->get('currencies', []);
        if (empty($currencies)) {
            throw new HttpException(400, "Currencies is not empty.");
        }
        $repositoryCurrency = $this->getDoctrine()->getManager()->getRepository('AppBundle:Currency');
        $responseArray = [];
        foreach ($currencies as $currency) {
            /** @var Currency $result */
            $result = $repositoryCurrency->getOneRowForAjax($currency['source'], $currency['name'], $date);
            if (!$result) {
                $ext = 'N/A';
            } else {
                $ext = $result->getValue();
            }
            $responseArray[] = ['source' => $currency['source'], 'name' => $currency['name'], 'value' => $ext];

        }
        $response = new JsonResponse();
        $response->setData($responseArray);
        return $response;
    }
}
