<?php

namespace ToDoPrviBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;
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

//        sending array $items for TWIG to render it
        return $this->render("@ToDoPrvi/Default/index.html.twig", [
            "items" => $items
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Method({"GET", "POST"})
     * edits a todo list item
     */
    public function editItemAction(Item $item, Request $request)
    {
//        $maliPost = new Item();
//        $maliPost->setCreationTime(new \DateTime("now"));

        $form = $this->createFormBuilder($item)
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
                "label" => "EDIT",
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($request->isMethod('POST') && $form->getClickedButton())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            return $this->redirectToRoute("home");
        }

        return $this->render("@ToDoPrvi/Default/edit.html.twig", [
            "form" => $form->createView()
        ]);



        return $this->render("@ToDoPrvi/Default/edit.html.twig");
    }

    /**
     * @Route("/sorter", name="sortBy")
     * @Method({"GET", "POST"})
     * sorts the collection of items according to the parameter (String)
     */
    public function sortItems(Request $request)
    {
        $data = $request->request->get('sort_param');

        $items = $this->getDoctrine()
            ->getRepository('ToDoPrviBundle:Item')->findAll();

        if (strcmp($data, 'byName') == 0) {
            usort($items, function(Item $a, Item $b) {
                return strcmp(strtolower($a->getName()), strtolower($b->getName()));
            });
        } else if(strcmp($data, 'byLocation') == 0) {
            usort($items, function(Item $a, Item $b) {
                return strcmp(strtolower($a->getLocation()), strtolower($b->getLocation()));
            });
        } else if(strcmp($data, 'byCreatedDate') == 0) {
            usort($items, function(Item $a, Item $b) {
                if ($a->getCreationTime() == $b->getCreationTime()) return 0;
                else return $a->getItemCreationTime() > $b->getCreationTime() ? 1 : -1;
            });
        } else if(strcmp($data, 'byExpDate') == 0) {
            usort($items, function(Item $a, Item $b) {
                if ($a->getDueDate() == $b->getDueDate()) return 0;
                else return $a->getDueDate() > $b->getDueDate() ? 1 : -1;
            });
        }

//        unset($items);
//        $items = array();

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
     * @Route("/contact", name="contact"))
     */
    public function contactController()
    {
        return $this->render("@ToDoPrvi/Default/contact.html.twig");
    }


    /**
     * @Route("/add", name="addNew")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $maliPost = new Item();
        $maliPost->setCreationTime(new \DateTime("now"));
        $maliPost->setDueDate(new \DateTime("tomorrow"));

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
