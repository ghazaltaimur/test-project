<?php
namespace Company\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Company\Entity\Company as CompanyEntity;          // <-- Add this import
use Company\Entity\Country as CountryEntity;
use Company\Form\CompanyForm;
use Doctrine\ORM\EntityManager;
use Company\Model\Company as CompanyModel,
    Company\Model\Country as CountryModel,
    Zend\Paginator\Paginator,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator; 
use Zend\Http\Request as HttpRequest;

class CompanyController extends AbstractActionController
{
    protected $companyTable;
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
    
    public function indexAction()
    {
       $page = (int) $this->params()->fromRoute('page', 0);

        $column = $this->params()->fromQuery('column');
        $order = $this->params()->fromQuery('order');
        
        $request = new HttpRequest();
        $parameterGet =  $this->getRequest()->getServer()->get('QUERY_STRING');
        $companyModel = $this->getServiceLocator()->get('Company');
        
        $view = new ViewModel();
        $adapter = new DoctrineAdapter(new ORMPaginator($companyModel->getAdapter($column, $order)));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(total_records);
       // exit;
        if ($page)
            $paginator->setCurrentPageNumber($page);
        $view->setVariable('paginator', $paginator);
        $view->setVariable('column', $column);
        $view->setVariable('order', $order);
        $view->setVariable('parameterGet', $parameterGet);
        return $view;
    }
    
    public function addAction()
    {
        $countryModel = $this->getServiceLocator()->get('Country');
        $companyModel = $this->getServiceLocator()->get('Company');
        $countries = $countryModel->getAll();
        $countriesArray = array();
        foreach ($countries as $country) {
               $countriesArray[$country->id] = $country->name;
        }
        $form = new CompanyForm($countriesArray);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($companyModel->getInputFilter());
            $nonFile = $request->getPost()->toArray();
            $File    = $this->params()->fromFiles('fileupload');
            $data = array_merge(
                 $nonFile
             ); 
            $form->setData($data);
            $adapter = new \Zend\File\Transfer\Adapter\Http(); 
            $adapter->addValidator('Extension', false, 'jpg,png,gif');
            $adapter->setDestination(dirname(__DIR__).'/companypic'); // set directory
            $file_name = $File['name'];
            $adapter->receive($file_name);
            
            $company = new CompanyEntity();
            if ($form->isValid()) {
              $data = $form->getData();
              $country = $countryModel->get($data['country']);
              if ($country) {
                  $company->country = $country;
              }
              $company->populate($data);
              $companyModel->create($company);
              return $this->redirect()->toRoute('company');
            }
        }
        return array('form' => $form, 'countries' => $countries);
    }
    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('company');
        }
        $countryModel = $this->getServiceLocator()->get('Country');
        $companyModel = $this->getServiceLocator()->get('Company');
        $countries = $countryModel->getAll();
        $countriesArray = array();
        foreach ($countries as $country) {
               $countriesArray[$country->id] = $country->name;
        }
        $form = new CompanyForm($countriesArray);
        $companies = $companyModel->get($id);
        $form->setInputFilter($companyModel->getInputFilter());
        $form->setBindOnValidate(false);
        $form->get('submit')->setAttribute('label', 'Edit');
        $form->bind($companies);
        $request = $this->getRequest();


       if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                foreach ($request->getPost() as $key => $value) {
                    if ($key == 'country') {
                        $country = $countryModel->get($value);
                        if ($country) {
                            $companies->country = $country;
                        }
                    } else {
                        $companies->$key = $value;
                    }
                }
                $companyModel->create($companies);
                return $this->redirect()->toRoute('company');
            }
        } else {
            $form->setData($companies->getArrayCopy());    //Converts Object values to array
        }
        return array('form' => $form, 'id' => $id);
    }
    
    public function deleteAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('company');
        }
        $countryModel = $this->getServiceLocator()->get('Country');
        $companyModel = $this->getServiceLocator()->get('Company');
        $request = $this->getRequest();
        $countries = $countryModel->getAll();
        $countriesArray = array();
        foreach ($countries as $country) {
               $countriesArray[$country->id] = $country->name;
               
        }
        $form = new CompanyForm($countriesArray);
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
                $company = $companyModel->get($id);
                if ($company) {
                    $companyModel->remove($company);
                }
            }
 
            // Redirect to list of companys
            return $this->redirect()->toRoute('company');
        }
 
        return array(
            'id' => $id,
            'company' => $this->getEntityManager()->find('Company\Entity\Company', $id)
        );
    }
    
    public function uploadAction()
    {
        $form = new StudentForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $student = new Student();
            $form->setInputFilter($student->getInputFilter());
           // $form->setData($request->getPost());
            $nonFile = $request->getPost()->toArray();
            $File    = $this->params()->fromFiles('fileupload');
            $data = array_merge(
                 $nonFile, //POST 
                 array('fileupload'=> $File['name']) //FILE...
             ); 
            $form->setData($data);
           echo "here".$form->isValid().dirname(__DIR__);
            if ($form->isValid()) {
                $adapter = new \Zend\File\Transfer\Adapter\Http(); 
                //validator can be more than one...
                $adapter->addValidator('Extension', false, 'jpg,png,gif');
               
                if($adapter->isValid()){ 
                    $student->exchangeArray($form->getData());
                    $lastId = $this->getStudentTable()->saveStudent($student);
                    
                    $adapter->setDestination(dirname(__DIR__).'/studentpic'); // set directory
                    $file_name = $File['name'];
                    $file_extension = $File['type'];
                    $fileExt = explode("/", $file_extension);
               
                    $adapter->receive($file_name);
             
                // Redirect to list of student
                return $this->redirect()->toRoute('student');
                }
            }
        }
        return array('form' => $form);
    }

    public function getCompanyTable()
    {
        if (!$this->companyTable) {
            $sm = $this->getServiceLocator();
            $this->companyTable = $sm->get('Company\Model\CompanyTable');
        }
        return $this->companyTable;
    }
    
}