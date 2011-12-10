<?php
namespace Jackalope\Query;

use PHPCR\Query\QueryInterface;
use PHPCR\Query\InvalidQueryException;

use Jackalope\ObjectManager;
use Jackalope\NotImplementedException;

/**
 * {@inheritDoc}
 *
 * @api
 */
class QueryManager implements \PHPCR\Query\QueryManagerInterface
{
    /**
     * The factory to instantiate objects
     * @var \Jackalope\Factory
     */
    protected $factory;

    /**
     * @var \Jackalope\ObjectManager
     */
    protected $objectManager;

    /**
     * Create the query manager - akquire through the session.
     *
     * @param object $factory an object factory implementing "get" as
     *      described in \Jackalope\Factory
     * @param ObjectManager $objectManager
     */
    public function __construct($factory, ObjectManager $objectManager)
    {
        $this->factory = $factory;
        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function createQuery($statement, $language)
    {
        switch($language) {
            case QueryInterface::JCR_SQL2:
                return $this->factory->get('Query\SqlQuery', array($statement, $this->objectManager));
            case QueryInterface::JCR_JQOM:
                throw new InvalidQueryException('Please use getQOMFactory to get the query object model factory. You can not build a QOM query from a string.');
            default:
                throw new InvalidQueryException("No such query language: $language");
        }
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getQOMFactory()
    {
        return $this->factory->get('Query\QOM\QueryObjectModelFactory', array($this->objectManager));
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getQuery($node)
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getSupportedQueryLanguages()
    {
        return array(QueryInterface::JCR_SQL2, QueryInterface::JCR_JQOM);
    }
}
