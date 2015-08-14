<?php

namespace ToDoPrviBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use ToDoPrviBundle\Entity\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/home", name="home")
     * @Template()
     */
    public function addAction(Request $request)
    {
//        contacting database and fetching all rows
        $items = $this->getDoctrine()
            ->getRepository('ToDoPrviBundle:Item')->findAll();

//        checking if everything is fine
//        if (!$items) {
//
//        }

//        sending array $items for TWIG to render it
        return $this->render("@ToDoPrvi/Default/index.html.twig", [
            "items" => $items
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     * @Method({"GET", "POST"})
     * removes a to-do item from database
     */
    public function removeItem(Item $item)
    {
//        $item = $this->getDoctrine()
//            ->getRepository('ToDoPrviBundle:Item')->find($id);
//       ^^^ not needed because symfony finds the needed item (from id) from database right away

        $em = $this->getDoctrine()->getManager();

        $em->remove($item);
        $em->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/removeAll", name="removeAll"))
     */
    public function removeAllItems()
    {
        $items = $this->getDoctrine()
            ->getRepository('ToDoPrviBundle:Item')->findAll();
        $em = $this->getDoctrine()->getManager();

        foreach ($items as $item)
            $em->remove($item);
        $em->flush();

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/add", name="addNew")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $maliPost = new Item();
        $maliPost->setCreationTime(new \DateTime("now"));

        $form = $this->createFormBuilder($maliPost)
            ->add("Name", "text", [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add("CreationTime", "date")
            ->add("DueDate", "date")
            ->add("Location", "text", [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add("submit", "submit", [
                "label" => "ADD",
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($request->isMethod('POST') && $form->getClickedButton())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($maliPost);
            $em->flush();
            return $this->redirectToRoute("home");
        }

        return $this->render("@ToDoPrvi/Default/addNew.html.twig", [
            "form" => $form->createView()
            ]);
    }
}
