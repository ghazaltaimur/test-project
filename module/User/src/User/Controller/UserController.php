<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel,
 User\Model\UserTable,
    User\Model\User;
use User\Entity\User as UserEntity;          // <-- Add this import
use User\Form\UserForm;
//use Zend\Authentication\Storage\Session;
use Doctrine\ORM\EntityManager;
use Zend\Session\Container;
use Zend\Http\Request as HttpRequest,
    Zend\Paginator\Paginator,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class UserController extends AbstractActionController 
{
    /**             
    * @var Doctrine\ORM\EntityManager
    */  
    protected $em;
    
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }
    
    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                                      ->get('AuthService');
        }
        
        return $this->authservice;
    }   
    public function indexAction(){
       
       $page = (int) $this->params()->fromRoute('page', 0);
       
        $column = $this->params()->fromQuery('column');
        $order = $this->params()->fromQuery('order');
        
        $userModel = $this->getServiceLocator()->get('User');
        $departmentModel = $this->getServiceLocator()->get('Department');
        $departments = $departmentModel->getAll();
        $departmentsArray = array();
        foreach ($departments as $department) {
               $departmentsArray[$department->id] = $department->name;
        }
        $companyModel = $this->getServiceLocator()->get('Company');
        $companies = $companyModel->getAll();
        $companiesArray = array();
        foreach ($companies as $company) {
               $companiesArray[$company->id] = $company->name;
        }
        
        $form = new UserForm($companiesArray,$departmentsArray);
        $form->setData($this->getRequest()->getQuery());
        //$search = new Search();
        $form->setInputFilter($userModel->getInputFilter());

        $action = $this->params()->fromQuery('action');
        $search_arr = array();
        if ($action == 'search') {
            if ($form->isValid()) {
                $search_arr = $form->getData();
            }
        }
        $request = new HttpRequest();
        $parameterGet =  $this->getRequest()->getServer()->get('QUERY_STRING');
        $userModel = $this->getServiceLocator()->get('User');
       // echo "here";
        $view = new ViewModel();
        $adapter = new DoctrineAdapter(new ORMPaginator($userModel->getAdapter($column, $order)));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(total_records);
       // exit;
        if ($page)
            $paginator->setCurrentPageNumber($page);
        $view->setVariable('paginator', $paginator);
        $view->setVariable('column', $column);
        $view->setVariable('order', $order);
        $view->setVariable('parameterGet', $parameterGet);
        $view->setVariable('form', $form);
        return $view;
    }
    
    public function addAction(){
        $userModel = $this->getServiceLocator()->get('User');
        
        $departmentModel = $this->getServiceLocator()->get('Department');
        $departments = $departmentModel->getAll();
        $departmentsArray = array();
        foreach ($departments as $department) {
               $departmentsArray[$department->id] = $department->name;
        }
        $companyModel = $this->getServiceLocator()->get('Company');
        $companies = $companyModel->getAll();
        $companiesArray = array();
        foreach ($companies as $company) {
               $companiesArray[$company->id] = $company->name;
        }
        
        $form = new UserForm($companiesArray,$departmentsArray);
        $request = $this->getRequest();
        $form->setInputFilter($userModel->getInputFilter());
        if ($request->isPost()) {
            $postdata = $request->getPost(); 
            $form->setData($postdata);
            
            $user = new UserEntity();
            if ($form->isValid()) {
              $data = $form->getData();
             
              $user->populate($data);
              $company = $companyModel->get($data['company']);
              if ($company) {
                  $user->company = $company;
              }
              if(isset($data['department'])){
                    $department = $departmentModel->get($data['department']);
                    if ($department) {
                        $user->department = $department;
                    }
              }
              
              $userModel->create($user);
              //exit;
              return $this->redirect()->toRoute('user');
            }
        }
        return array('form' => $form);
    }
    
     public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('user');
        }
        $userModel = $this->getServiceLocator()->get('User');
        
        $departmentModel = $this->getServiceLocator()->get('Department');
        $departments = $departmentModel->getAll();
        $departmentsArray = array();
        foreach ($departments as $department) {
              $departmentsArray[$department->id] = $department->name;
        }
        $companyModel = $this->getServiceLocator()->get('Company');
        $companies = $companyModel->getAll();
        $companiesArray = array();
        foreach ($companies as $company) {
              $companiesArray[$company->id] = $company->name;
        }
        
        $form = new UserForm($companiesArray,$departmentsArray);
        $users = $userModel->get($id);
        $form->setBindOnValidate(false);
        $form->get('submit')->setAttribute('label', 'Edit');
        $form->bind($users);
        $request = $this->getRequest();
        $form->setInputFilter($userModel->getInputFilter());

       if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                foreach ($request->getPost() as $key => $value) {
                    if ($key == 'company') {
                        $company = $companyModel->get($value);
                        if ($company) {
                            $users->company = $company;
                        }
                    } 
                    else if ($key == 'department') {
                        $department = $departmentModel->get($value);
                        if ($department) {
                            $users->department = $department;
                        }
                    } 
                    else {
                        $users->$key = $value;
                    }
                }
                $userModel->create($users);
                return $this->redirect()->toRoute('user');
            }
        }
        return array('form' => $form, 'id' => $id);
    }
    public function deleteAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('user');
        }
        $userModel = $this->getServiceLocator()->get('User');
        $departmentModel = $this->getServiceLocator()->get('Department');
        $departments = $departmentModel->getAll();
        $departmentsArray = array();
        foreach ($departments as $department) {
               $departmentsArray[$department->id] = $department->name;
        }
        $companyModel = $this->getServiceLocator()->get('Company');
        $companies = $companyModel->getAll();
        $companiesArray = array();
        foreach ($companies as $company) {
               $companiesArray[$company->id] = $company->name;
        }
        $form = new UserForm($companiesArray,$departmentsArray);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
                $user = $userModel->get($id);
                if ($user) {
                    $userModel->remove($user);
                }
            }
 
            // Redirect to list of companys
            return $this->redirect()->toRoute('user');
        }
        return array(
            'id' => $id,
            'user' => $userModel->get($id)
        );
    }
   
  public function destroyAction() {
        $authService = new \Zend\Authentication\AuthenticationService;
        $authStorage = $authService->getStorage();
        $authStorage->clear();
        return $this->redirect()->toRoute('user');
  }
}
