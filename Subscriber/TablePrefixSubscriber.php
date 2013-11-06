<?php

namespace Ekino\WordpressBundle\Subscriber;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * Class TablePrefixSubscriber
 *
 * Doctrine event to prefix tables
 */
class TablePrefixSubscriber implements \Doctrine\Common\EventSubscriber
{
    /**
     * Prefix value
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * Constructor
     *
     * @param stirng $prefix
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Returns subscribed events
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array('loadClassMetadata');
    }

    /**
     * Loads class metadata and updates table prefix name
     *
     * @param LoadClassMetadataEventArgs $args
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $classMetadata = $args->getClassMetadata();

        if ($classMetadata->isInheritanceTypeSingleTable() && !$classMetadata->isRootEntity()) {
            return;
        }

        $classMetadata->setTableName($this->prefix . $classMetadata->getTableName());

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY) {
                $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
            }
        }
    }
}