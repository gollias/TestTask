<?php

$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$entityTypeId     = $setup->getEntityTypeId('customer');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$setup->addAttribute('customer', 'linkedin_profile', array(
    'label'             => 'LinkedIn Profile URL',
    'input'             => 'text',
    'type'              => 'varchar',
    'frontend_class'    => 'validate-url validate-length maximum-length-250',
    'visible'           => true,
    'visible_on_front'  => true,
    'adminhtml_only'    => false,
    'required'          => false,
    'user_defined'      => true,
));

$setup->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'linkedin_profile',
    '900'
);

$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'linkedin_profile');
$attribute->setData('used_in_forms', array(
    'customer_account_create',
    'customer_account_edit',
    'adminhtml_customer',
));
$attribute->save();

$setup->endSetup();