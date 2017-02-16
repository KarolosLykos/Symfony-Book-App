<?php

namespace Bookkeeper\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Bookkeeper\ManagerBundle\Entity\Book;
use Bookkeeper\ManagerBundle\Form\BookType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookController extends Controller
{	
	#Dispally all item
	public function indexAction(){
		#Instance of entity manager
		$em = $this->getDoctrine()->getManager();
		#Create new object containing all items
		$books = $em->getRepository('BookkeeperManagerBundle:Book')->findAll();
		#Rendex indx page passing books object
		return $this->render('BookkeeperManagerBundle:Book:index.html.twig',array(
			'books' => $books
			));
	}
	
	#Shows one item
	public function showAction($id){
		#Instance of entity manager
		$em = $this->getDoctrine()->getManager();
		#Create new object for the specific id 
		$book = $em->getRepository('BookkeeperManagerBundle:Book')->find($id);
		#Create a delete form
		$delete_form = $this->createFormBuilder()
							->setAction($this->generateUrl('book_delete',array('id' => $id)))
							->setMethod('DELETE')
							->add('submit', SubmitType::class, array('label'=>'Delete Book'))
							->getForm();

		#Rendex indx page passing book object
		return $this->render('BookkeeperManagerBundle:Book:show.html.twig',array(
			'book' => $book,
			'delete_form' => $delete_form->createView()
			));	
	}
	
	#Dispalays a form for creating a new item
	public function newAction(){
		#Create new object
		$book = new Book();
		#Create Form object
		$form = $this->createForm(BookType::class, $book, array(
			'action' => $this->generateUrl('book_create'),
			'method' => 'POST'
			));
		#Add submit Button
		$form->add('submit', SubmitType::class, array('label'=>'Create Book'));
		return $this->render('BookkeeperManagerBundle:Book:new.html.twig',array(
			'form' => $form->createView()
		));
	}
	
	#Handles form submission
	public function createAction(Request $request){
		#Create new object
		$book = new Book();
		#Create Form object
		$form = $this->createForm(BookType::class, $book, array(
			'action' => $this->generateUrl('book_create'),
			'method' => 'POST'
			));
		#Add submit Button
		$form->add('submit', SubmitType::class, array('label'=>'Create Book'));	
		#Handle the request
		$form->handleRequest($request);
		#Check validation
		if ($form->isValid()) {
			#Instance of entity manager
			$em = $this->getDoctrine()->getManager();
			#Start managing the book entity using persist method
			$em->persist($book);
			#Save
			$em->flush();
			#Success message
			$this->get('session')->getFlashBag()->add('msg','Your Book has been created!');
			#redirect
			return $this->redirect($this->generateUrl('book_show',array('id'=> $book->getId())));
		}
		#Flash message if it didnt passed the validation
		$this->get('session')->getFlashBag()->add('msg','Something went WRONG!');
		#Return to new page if something is wrong
		return $this->render('BookkeeperManagerBundle:Book:new.html.twig',array(
			'form'=>$form->createView()
			));	
	}

	#Displays a form for editing a new item
	public function editAction($id){
		#Instance of entity manager
		$em = $this->getDoctrine()->getManager();
		#Create new object for the specific id 
		$book = $em->getRepository('BookkeeperManagerBundle:Book')->find($id);
		#Create Form object
		$form = $this->createForm(BookType::class, $book, array(
			'action' => $this->generateUrl('book_update',array('id' => $book->getId())),
			'method' => 'PUT'
			));
		#Add submit Button
		$form->add('submit', SubmitType::class, array('label'=>'Update Book'));	
		#Render edit page passing the form object with createView property
		return $this->render('BookkeeperManagerBundle:Book:edit.html.twig',array(
			'form' => $form->createView()
			));
	}
	#Handles form submission for editing
	public function updateAction(Request $request, $id){
		#Instance of entity manager
		$em = $this->getDoctrine()->getManager();
		#Create new object for the specific id 
		$book = $em->getRepository('BookkeeperManagerBundle:Book')->find($id);
		#Create Form object
		$form = $this->createForm(BookType::class, $book, array(
			'action' => $this->generateUrl('book_update',array('id'=> $book->getId())),
			'method' => 'PUT'
			));
		#Add submit Button
		$form->add('submit', SubmitType::class, array('label'=>'Update Book'));	
		#Handle the request
		$form->handleRequest($request);
		#Check validation
		if($form->isValid()){
			#Save
			$em->flush();
			#Flash message if it didnt passed the validation
			$this->get('session')->getFlashBag()->add('msg','Your Book has been Updated!');
			#Redirect
			return $this->redirect($this->generateUrl('book_show',array('id'=>$id))); 
		}	

		return $this->render('BookkeeperManagerBundle:Book:edit.html.twig',array(
			'form' => $form->createView
			));
	}

	#Deletes an item
	public function deleteAction(Request $request, $id){
		#Create a delete form
		$delete_form = $this->createFormBuilder()
							->setAction($this->generateUrl('book_delete',array('id' => $id)))
							->setMethod('DELETE')
							->add('submit', SubmitType::class, array('label'=>'Delete Book'))
							->getForm();
		$delete_form->handleRequest($request);
		
		if($delete_form->isValid()){
			#Instance of entity manager
			$em = $this->getDoctrine()->getManager();
			#Create new object for the specific id 
			$book = $em->getRepository('BookkeeperManagerBundle:Book')->find($id);
			#Remove
			$em->remove($book);
			#Save the action
			$em->flush();
		}
			#Flash message
			$this->get('session')->getFlashBag()->add('msg','Your Book has been Deleted!');
			#Redirect
			return $this->redirect($this->generateUrl('book')); 
	}
}