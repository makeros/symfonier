<?php

namespace Symfonier\ApiBundle\Controller;

use Symfonier\UserBundle\Document\User;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestController extends FOSRestController
{
  /**
   * Only testing!!
   *
   * @ApiDoc(
   *   resource = true,
   *   statusCodes = {
   *     200 = "Returned when successful",
   *   }
   * )
   *
   * @Annotations\Get("/test")
   * @Annotations\View()
   *
   * @param Request               $request      the request object
   * @param ParamFetcherInterface $paramFetcher param fetcher service
   *
   * @return array
   */
  public function getTestAction(Request $request, ParamFetcherInterface $paramFetcher)
  {

    $result = array();

    $dm = $this->get('doctrine_mongodb')->getManager();

    // $testResult = 


    return $result;
  }
}
