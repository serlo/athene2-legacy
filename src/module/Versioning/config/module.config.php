<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

/**
 * @codeCoverageIgnore
 */
return array(
    'class_resolver' => array(
        'Versioning\Service\RepositoryServiceInterface' => 'Versioning\Service\RepositoryService'
    ),
    'di' => array(
        'definition' => array(
            'class' => array(
                'Versioning\RepositoryManager' => array(
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setClassResolver' => array(
                        'required' => 'true'
                    )
                ),
                'Versioning\Service\RepositoryService' => array()
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Versioning\RepositoryManagerInterface' => 'Versioning\RepositoryManager',
                'Versioning\Service\RepositoryServiceInterface' => 'Versioning\Service\RepositoryService'
            ),
            'Versioning\Service\RepositoryService' => array(
                'shared' => false
            )
        )
    )
);
