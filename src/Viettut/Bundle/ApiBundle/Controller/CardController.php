<?php
/**
 * Created by PhpStorm.
 * User: giang
 * Date: 10/21/15
 * Time: 10:24 PM
 */

namespace Viettut\Bundle\ApiBundle\Controller;


use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Viettut\DomainManager\CardManagerInterface;
use Viettut\Handler\HandlerInterface;
use Viettut\Model\Core\CardInterface;
use Viettut\Model\Core\ChapterInterface;
use Viettut\Model\Core\CommentInterface;
use Viettut\Model\Core\CourseInterface;

/**
 * @RouteResource("Card")
 */
class CardController extends RestControllerAbstract implements ClassResourceInterface
{

    /**
     * Get all comment
     *
     * @Rest\View(
     *      serializerGroups={"card.summary", "user.summary", "template.summary"}
     * )
     *
     * @ApiDoc(
     *  resource = true,
     *  statusCodes = {
     *      200 = "Returned when successful"
     *  }
     * )
     *
     * @return CardInterface[]
     */
    public function cgetAction()
    {
        return $this->all();
    }

    /**
     * @param $hash
     * @return array
     */
    public function getAction($hash)
    {
        /**
         * @var CardManagerInterface $cardManager
         */
        $cardManager = $this->get('viettut.domain_manager.card');

        $card = $cardManager->getCardByHash($hash);

        if (!$card instanceof CardInterface) {
            throw new NotFoundHttpException('The resource is not found or you don\'t have permission');
        }

        return $card->getData();
    }


    /**
     * Create a card from the submitted data
     *
     * @ApiDoc(
     *  resource = true,
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      400 = "Returned when the submitted data has errors"
     *  }
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postAction(Request $request)
    {
        return $this->post($request);
    }

    /**
     * Update an existing card from the submitted data or create a new card
     *
     * @ApiDoc(
     *  resource = true,
     *  statusCodes = {
     *      201 = "Returned when the resource is created",
     *      204 = "Returned when successful",
     *      400 = "Returned when the submitted data has errors"
     *  }
     * )
     *
     * @param Request $request the request object
     * @param int $id the resource id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when the resource does not exist
     */
    public function putAction(Request $request, $id)
    {
        return $this->put($request, $id);
    }
    
    
    /**
     * Update an existing card from the submitted data or create a new card at a specific location
     *
     * @ApiDoc(
     *  resource = true,
     *  statusCodes = {
     *      204 = "Returned when successful",
     *      400 = "Returned when the submitted data has errors"
     *  }
     * )
     *
     * @param Request $request the request object
     * @param int $id the resource id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when resource not exist
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patch($request, $id);
    }
    
    /**
     * @return string
     */
    protected function getResourceName()
    {
        return 'card';
    }

    /**
     * The 'get' route name to redirect to after resource creation
     *
     * @return string
     */
    protected function getGETRouteName()
    {
        return 'api_1_get_card';
    }

    /**
     * @return HandlerInterface
     */
    protected function getHandler()
    {
        return $this->container->get('viettut_api.handler.card');
    }
}