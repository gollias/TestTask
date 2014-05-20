<?php

class OwnException extends Exception {};

require_once Mage::getModuleDir('controllers', 'Mage_Customer') . DS . 'AccountController.php';
class AccountControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    protected $_controller;    

    public static function setUpBeforeClass()
    {
        // suppress notices
        Mage::app()->setErrorHandler(Mage_Core_Model_App::DEFAULT_ERROR_HANDLER);
    }

    public static function tearDownAfterClass()
    {
        restore_error_handler();
    }

    public function setUp()
    {
        $this->_request = new Mage_Core_Controller_Request_Http();
        $this->_response = new Zend_Controller_Response_HttpTestCase();
        $this->_controller = new Mage_Customer_AccountController($this->_request, $this->_response);
        Mage::app()->setResponse($this->_response);
    }

    protected function tearDown()
    {
        $this->_controller = NULL;
    }

    /**
     * @dataProvider providerAccountCreate
     * @expectedException OwnException
     */
    public function testCreatePostAction($data)
    {
        $this->_request
            ->setModuleName('customer')
            ->setControllerName('account')
            ->setActionName('createPost')
            ->setPost($data);

        $this->_controller->dispatch('createPost');

        $this->assertFalse($this->_controller->getFlag('', 'no-dispatch'), "Should NOT contain no-dispatch flag.");

        if (!$this->_getSession()->isLoggedIn()) {
            throw new OwnException('Customer is not logged in after action');
        }

        $sessionCustomerId = $this->_getSession()->getCustomer()->getId();

        if (is_null($sessionCustomerId)) {
            throw new OwnException('No Customer ID');
        }

        // skip session data and varify saved data
        $savedLinkedinProfile = Mage::getModel('customer/customer')->load($sessionCustomerId)->getLinkedinProfile();
        $this->assertEquals($data['linkedin_profile'], $savedLinkedinProfile);

    }


    /**
     * Test data provider
     *
     * @return array
     */
    public function providerAccountCreate()
    {
        return array(
            array(
                'firstname'         => 'Firstname1',
                'lastname'          => 'Lastname1',
                'email'             => 'test1@example.com',
                'linkedin_profile'  => 'http://ua.linkedin.com/tester1',
                'password'          => '!test1ng',
                'confirmation'      => '!test1ng',
                'success_url'       => Mage::getBaseUrl() . 'customer/account/index/',
                'error_url'         => Mage::getBaseUrl() . 'customer/account/create/',
            ),
            array(
                'firstname'         => 'Firstname2',
                'lastname'          => 'Lastname2',
                'email'             => 'test2@example.com',
                'linkedin_profile'  => 'http://ua.linkedin.com/tester2',
                'password'          => '!test1ng',
                'confirmation'      => '!test1ng',
                'success_url'       => Mage::getBaseUrl() . 'customer/account/index/',
                'error_url'         => Mage::getBaseUrl() . 'customer/account/create/',
            ),
            array(
                'firstname'         => 'Firstname3',
                'lastname'          => 'Lastname3',
                'email'             => 'test3@example.com',
                'linkedin_profile'  => 'http://ua.linkedin.com/tester3',
                'password'          => '!test1ng',
                'confirmation'      => '!test1ng',
                'success_url'       => Mage::getBaseUrl() . 'customer/account/index/',
                'error_url'         => Mage::getBaseUrl() . 'customer/account/create/',
            ),
        );
    }

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

}
