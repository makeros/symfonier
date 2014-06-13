<?php

namespace Symfonier\UserBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;



class RegistrationFormHandler extends BaseHandler
{
    protected $mr;
    protected $form;

    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, ManagerRegistry $mr)
    {
        $this->mr = $mr;
        $this->form = $form;
        $this->request = $request;
        $this->userManager = $userManager;
        // $this->mailer = $mailer;
        // $this->tokenGenerator = $tokenGenerator;
    }

    public function process($confirmation = false, $loggedUser = false)
    {
        // var_dump('asdasdasdasdasdasdasd ');
        $user = ($loggedUser) ? $loggedUser : $this->createUser();
        $this->form->setData($user);

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($user, $confirmation);

                return true;
            }
        }

        return false;
    }

    protected function onSuccess(UserInterface $user, $confirmation)
    {
//         // $dm = $this->mr->getManager();
//         // $repo = $dm->getRepository('SymfonierApiBundle:Building');
//         // $building = $repo->findOneById($this->form->get('_building')->getData());

//         // $address = new Address();
//         // $address->setBuilding($building);
//         // $address->setFlatNumber($this->form->get('_flatNumber')->getData());
//         // $dm->persist($address);

//         // $user->setAddress($address);


//         // // init user settings for new user! :)
//         // new UserSettings($dm, $user);

        parent::onSuccess($user, $confirmation);
    }
}
