<?php
/**
 * Created by PhpStorm.
 * User: skazzi
 * Date: 17.8.15.
 * Time: 13.32
 */

namespace ToDoPrviBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ToDoPrviBundle\Form\RegistrationType;
use ToDoPrviBundle\Form\Model\Registration;

class AccountController extends Controller
{
    public function registerAction()
    {
        $registration = new Registration();
        $form = $this->createForm(new RegistrationType(), $registration, array(
            'action' => $this->generateUrl('account_create'),
        ));

        return $this->render(
            '@ToDoPrvi/Default/register.html.twig',
            array('form' => $form->createView())
        );
    }
}