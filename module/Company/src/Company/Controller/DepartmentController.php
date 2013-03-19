<?php
namespace Company\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Company\Entity\Company as CompanyEntity;          // <-- Add this import
use Company\Entity\Department as DepartmentEntity;
use Company\Form\DepartmentForm;
use Doctrine\ORM\EntityManager;
use Company\Model\Company as CompanyModel,
    Company\Model\Department as DepartmentModel,
    Zend\Paginator\Paginator,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator; 
use Zend\Http\Request as HttpRequest;

class DepartmentController extends AbstractActionController
{
    protected $departmentTable;
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
        $departmentModel = $this->getServiceLocator()->get('Department');
        
        $view = new ViewModel();
        $adapter = new DoctrineAdapter(new ORMPaginator($departmentModel->getAdapter($column, $order)));
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
        $departmentModel = $this->getServiceLocator()->get('Department');
        $companyModel = $this->getServiceLocator()->get('Company');
        $companies = $companyModel->getAll();
        $companiesArray = array();
        foreach ($companies as $company) {
               $companiesArray[$company->id] = $company->name;
        }
        $form = new DepartmentForm($companiesArray);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost(); 
            $form->setData($data);
            $department = new DepartmentEntity();
            if ($form->isValid()) {
              $data = $form->getData();
              
              $company = $companyModel->get($data['company']);
              if ($company) {
                  $department->company = $company;
              }
             
              $date = new \DateTime("now");
              $department->created_at = $date;
              $department->populate($data);
             // print_r($department);
              $departmentModel->create($department);
              return $this->redirect()->toRoute('department');
            }
        }
        return array('form' => $form, 'companies' => $companiesArray);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('department');
        }
        $departmentModel = $this->getServiceLocator()->get('Department');
        $companyModel = $this->getServiceLocator()->get('Company');
        $companies = $companyModel->getAll();
        $companiesArray = array();
        foreach ($companies as $company) {
               $companiesArray[$company->id] = $company->name;
        }
        $form = new DepartmentForm($companiesArray);
        $departments = $departmentModel->get($id);
        $form->setBindOnValidate(false);
        $form->get('submit')->setAttribute('label', 'Edit');
        $form->bind($departments);
        $request = $this->getRequest();


       if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                foreach ($request->getPost() as $key => $value) {
                    if ($key == 'company') {
                        $company = $companyModel->get($value);
                        if ($company) {
                            $departments->company = $company;
                        }
                    } else {
                        $departments->$key = $value;
                    }
                }
                $departmentModel->create($departments);
                return $this->redirect()->toRoute('department');
            }
        }
        return array('form' => $form, 'id' => $id);
    }
    
    public function deleteAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('department');
        }
        $departmentModel = $this->getServiceLocator()->get('Department');
        $companyModel = $this->getServiceLocator()->get('Company');
        $companies = $companyModel->getAll();
        $companiesArray = array();
        foreach ($companies as $company) {
               $companiesArray[$company->id] = $company->name;
        }
        $form = new DepartmentForm($companiesArray);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
                $department = $departmentModel->get($id);
                if ($department) {
                    $departmentModel->remove($department);
                }
            }
 
            return $this->redirect()->toRoute('department');
        }
 
        return array(
            'id' => $id,
            'department' => $departmentModel->get($id),
        );
    }
}