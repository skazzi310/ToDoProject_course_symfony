<?php

namespace ToDoPrviBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ToDoPrviBundle\Entity\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ToDoPrviBundle\Form\Model\Registration;
use ToDoPrviBundle\Form\Type\RegistrationType;


class DefaultController extends Controller
{
    /**
     * @return mixed returns /ToDoPrviBundle/Entity/User object of logged in user
     */
    private function getActiveUser()
    {
        return $this->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * @return array of /ToDoPrviBundle:Entity/Item objects
     */
    private function getUserPosts()
    {
        $user = $this->getActiveUser();
        $items = $user->getPosts()->getValues();
        return $items;
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RegistrationType(), new Registration());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $registration = $form->getData();

            // crypting the password
            $user = $registration->getUser();
            $plain_password = $user->getPassword();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plain_password);
            $user->setPassword($encoded);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login_route');
        }

        return $this->render(
            '@ToDoPrvi/Default/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/home", name="home")
     * @Template()
     */
    public function addAction(Request $request)
    {
        // we get the active user object and fetch all post from database
        $items = $this->getUserPosts();

        return $this->render("@ToDoPrvi/Default/index.html.twig", [
            "items" => $items
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Method({"GET", "POST"})
     * edits a to-do list item
     */
    public function editItemAction(Item $item, Request $request)
    {
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
        if ($request->isMethod('POST') && $form->getClickedButton()) {
            $em = $this->getDoctrine()->getManager();
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
        $items = $this->getUserPosts();

        // determining the parameter for sorting
        switch($data) {
            case 'byName':
                usort($items, function (Item $a, Item $b) {
                    return strcmp(strtolower($a->getName()), strtolower($b->getName()));
                });
                break;
            case 'byLocation':
                usort($items, function (Item $a, Item $b) {
                    return strcmp(strtolower($a->getLocation()), strtolower($b->getLocation()));
                });
                break;
            case 'byCreatedDate':
                usort($items, function (Item $a, Item $b) {
                    if ($a->getCreationTime() == $b->getCreationTime()) return 0;
                    else return $a->getCreationTime() > $b->getCreationTime() ? 1 : -1;
                });
                break;
            case 'byExpDate':
                usort($items, function (Item $a, Item $b) {
                    if ($a->getDueDate() == $b->getDueDate()) return 0;
                    else return $a->getDueDate() > $b->getDueDate() ? 1 : -1;
                });
                break;
        }

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
        $post = new Item();
        $post->setCreationTime(new \DateTime("now"));
        $post->setDueDate(new \DateTime("tomorrow"));

        $form = $this->createFormBuilder($post)
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
        if ($request->isMethod('POST') && $form->getClickedButton()) {
            $em = $this->getDoctrine()->getManager();
            $post->setUser($this->getActiveUser());
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute("home");
        }

        return $this->render("@ToDoPrvi/Default/addNew.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
