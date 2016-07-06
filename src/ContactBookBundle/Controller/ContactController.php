<?php

namespace ContactBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ContactBookBundle\Entity\Contact;
use ContactBookBundle\Entity\Address;
use ContactBookBundle\Entity\Email;
use ContactBookBundle\Entity\Phone;

class ContactController extends Controller {

    /**
     * @Route("/create", name="create")
     * @Template("ContactBookBundle:Contact:new.html.twig")
     * @Method("POST")
     */
    public function createContactAction(Request $request) {
        $contact = new Contact();
        $form = $this->createFormBuilder($contact)
                ->setAction($this->generateUrl('create'))
                ->add('name', null, array('label' => 'Name'))
                ->add('surname', null, array('label' => "Surname"))
                ->add('description', null, array('label' => "Description"))
                ->add('submit', 'submit')
                ->getForm();
        $form->handleRequest($request);

        $validator = $this->get('validator');
        $errorsContact = $validator->validate($contact);
        
        if(count($errorsContact) > 0){
            $errorsString = (string)$errorsContact;
            return new Response($errorsString);
        }
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('show', ['id' => $contact->getId()]);
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/new", name="new")
     * @Template("ContactBookBundle:Contact:new.html.twig")
     * @Method("GET")
     */
    public function newAction() {
        $contact = new Contact();

        $form = $this->createFormBuilder($contact)
                ->setAction($this->generateUrl('create'))
                ->add('name', null, array('label' => 'Name'))
                ->add('surname', null, array('label' => "Surname"))
                ->add('description', null, array('label' => "Description"))
                ->add('submit', 'submit')
                ->getForm();
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/", name="showAll")
     * @Template()
     */
    public function showAllAction() {
        return ['contacts' => $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->findAllContactsASC()];
    }

    /**
     * @Route("/show/{id}", name="show")
     * @Template()
     */
    public function showAction($id) {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        return ['contact' => $contact];
    }

    /**
     * @Route("/update/{id}", name="update")
     * @Template("ContactBookBundle:Contact:new.html.twig")
     */
    public function updateAction($id, Request $request) {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $form = $this->createFormBuilder($contact)
                ->add('name', null, array('label' => 'Name'))
                ->add('surname', null, array('label' => "Surname"))
                ->add('description', null, array('label' => "Description"))
                ->add('submit', 'submit')
                ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('show', ['id' => $contact->getId()]);
        }
        return ['form' => $form->createView(), 'contact' => $contact];
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @Template("ContactBookBundle:Contact:new.html.twig")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();

        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $em->remove($contact);
        $em->flush();

        return $this->redirectToRoute('showAll');
    }

    /**
     * @Route("/addAddress/{id}", name="addAddress")
     * @Template()
     * 
     */
    public function addAddress(Request $request, $id) {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);
        $address = new Address();

        $form = $this->createFormBuilder($address)
                ->add('city', null, array('label' => 'City'))
                ->add('street', null, array('label' => "Street"))
                ->add('house_number', null, array('label' => "House number"))
                ->add('apartment_number', null, array('label' => "Apartment number"))
                ->add('submit', 'submit')
                ->getForm();
        $form->handleRequest($request);

        $contact->addAddress($address);
        $address->setContact($contact);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('showAll');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/addEmail/{id}", name="addEmail")
     * @Template()
     * 
     */
    public function addEmail(Request $request, $id) {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);
        $email = new Email();

        $form = $this->createFormBuilder($email)
                ->add('email', null, array('label' => 'Email'))
                ->add('type', null, array('label' => "Type"))
                ->add('submit', 'submit')
                ->getForm();
        $form->handleRequest($request);

        $contact->addEmail($email);
        $email->setContact($contact);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($email);
            $em->flush();

            return $this->redirectToRoute('showAll');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/addPhone/{id}", name="addPhone")
     * @Template()
     * 
     */
    public function addPhone(Request $request, $id) {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);
        $phone = new Phone();

        $form = $this->createFormBuilder($phone)
                ->add('number', null, array('label' => 'Phone number'))
                ->add('type', null, array('label' => "Type"))
                ->add('submit', 'submit')
                ->getForm();
        $form->handleRequest($request);

        $contact->addPhone($phone);
        $phone->setContact($contact);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($phone);
            $em->flush();

            return $this->redirectToRoute('showAll');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/updateAddress/{id}", name="updateAddress")
     * @Template("ContactBookBundle:Contact:addAddress.html.twig")
     */
    public function updateAddressAction($id, Request $request) {
        $address = $this->getDoctrine()->getRepository('ContactBookBundle:Address')->find($id);

        if (!$address) {
            throw $this->createNotFoundException("Addres not found");
        }

        $form = $this->createFormBuilder($address)
                ->add('city', null, array('label' => 'City'))
                ->add('street', null, array('label' => "Street"))
                ->add('house_number', null, array('label' => "House number"))
                ->add('apartment_number', null, array('label' => "Apartment number"))
                ->add('submit', 'submit')
                ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('showAll');
        }
        return ['form' => $form->createView(), 'address' => $address];
    }

    /**
     * @Route("/updateEmail/{id}", name="updateEmail")
     * @Template("ContactBookBundle:Contact:addEmail.html.twig")
     */
    public function updateEmailAction($id, Request $request) {
        $email = $this->getDoctrine()->getRepository('ContactBookBundle:Email')->find($id);

        if (!$email) {
            throw $this->createNotFoundException("Email not found");
        }

        $form = $this->createFormBuilder($email)
                ->add('email', null, array('label' => 'Email'))
                ->add('type', null, array('label' => "Type"))
                ->add('submit', 'submit')
                ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('showAll');
        }
        return ['form' => $form->createView(), 'email' => $email];
    }

    /**
     * @Route("/updatePhone/{id}", name="updatePhone")
     * @Template("ContactBookBundle:Contact:addPhone.html.twig")
     */
    public function updatePhoneAction($id, Request $request) {
        $phone = $this->getDoctrine()->getRepository('ContactBookBundle:Phone')->find($id);

        if (!$phone) {
            throw $this->createNotFoundException("Phone not found");
        }

        $form = $this->createFormBuilder($phone)
                ->add('number', null, array('label' => 'Number'))
                ->add('type', null, array('label' => "Type"))
                ->add('submit', 'submit')
                ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('showAll');
        }
        return ['form' => $form->createView(), 'phone' => $phone];
    }

    /**
     * @Route("/deleteAddress/{id}", name="deleteAddress")
     * @Template()
     */
    public function deleteAddressAction($id) {
        $em = $this->getDoctrine()->getManager();

        $address = $this->getDoctrine()->getRepository('ContactBookBundle:Address')->find($id);

        if (!$address) {
            throw $this->createNotFoundException("Contact not found");
        }

        $em->remove($address);
        $em->flush();

        return $this->redirectToRoute('showAll');
    }

    /**
     * @Route("/deleteEmail/{id}", name="deleteEmail")
     * @Template()
     */
    public function deleteEmailAction($id) {
        $em = $this->getDoctrine()->getManager();

        $email = $this->getDoctrine()->getRepository('ContactBookBundle:Email')->find($id);

        if (!$email) {
            throw $this->createNotFoundException("Email not found");
        }

        $em->remove($email);
        $em->flush();

        return $this->redirectToRoute('showAll');
    }

    /**
     * @Route("/deletePhone/{id}", name="deletePhone")
     * @Template()
     */
    public function deletePhoneAction($id) {
        $em = $this->getDoctrine()->getManager();

        $phone = $this->getDoctrine()->getRepository('ContactBookBundle:Phone')->find($id);

        if (!$phone) {
            throw $this->createNotFoundException("Phone number not found");
        }

        $em->remove($phone);
        $em->flush();

        return $this->redirectToRoute('showAll');
    }

}
