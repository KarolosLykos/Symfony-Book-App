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
		return $this->render('BookkeeperManagerBundle:Book:index.html.twig');
	}
	
	#Shows one item
	public function showAction($id){
		return $this->render('BookkeeperManagerBundle:Book:show.html.twig');	
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
			#reirect
			return $this->redirect($this->generateUrl('book_new'));
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
		return $this->render('BookkeeperManagerBundle:Book:edit.html.twig');
	}
	#Handles form submission for editing
	public function updateAction(Request $request, $id){
		
	}

	#Deletes an item
	public function deleteAction(Request $request, $id){
		
	}
}