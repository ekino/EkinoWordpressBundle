<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Subscriber;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Common\EventSubscriber;

/**
 * Class TablePrefixSubscriber
 *
 * Doctrine event to prefix tables
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class TablePrefixSubscriber implements EventSubscriber
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
     * @param string $prefix
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

        if ($classMetadata->getReflectionClass() && $classMetadata->getReflectionClass()->implementsInterface('Ekino\\WordpressBundle\\Model\\WordpressEntityInterface')) {
            $classMetadata->setPrimaryTable(array('name' => $this->prefix.$classMetadata->getTableName()));

            foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
                if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY) {
                    $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                    $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix.$mappedTableName;
                }
            }
        }
    }
}
