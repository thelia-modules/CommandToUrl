<?php

namespace CommandToUrl\Model\Base;

use \Exception;
use \PDO;
use CommandToUrl\Model\CommandUrl as ChildCommandUrl;
use CommandToUrl\Model\CommandUrlQuery as ChildCommandUrlQuery;
use CommandToUrl\Model\Map\CommandUrlTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'command_url' table.
 *
 *
 *
 * @method     ChildCommandUrlQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCommandUrlQuery orderByCommand($order = Criteria::ASC) Order by the command column
 * @method     ChildCommandUrlQuery orderByToken($order = Criteria::ASC) Order by the token column
 * @method     ChildCommandUrlQuery orderByAllowedIps($order = Criteria::ASC) Order by the allowed_ips column
 * @method     ChildCommandUrlQuery orderByActive($order = Criteria::ASC) Order by the active column
 *
 * @method     ChildCommandUrlQuery groupById() Group by the id column
 * @method     ChildCommandUrlQuery groupByCommand() Group by the command column
 * @method     ChildCommandUrlQuery groupByToken() Group by the token column
 * @method     ChildCommandUrlQuery groupByAllowedIps() Group by the allowed_ips column
 * @method     ChildCommandUrlQuery groupByActive() Group by the active column
 *
 * @method     ChildCommandUrlQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCommandUrlQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCommandUrlQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCommandUrl findOne(ConnectionInterface $con = null) Return the first ChildCommandUrl matching the query
 * @method     ChildCommandUrl findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCommandUrl matching the query, or a new ChildCommandUrl object populated from the query conditions when no match is found
 *
 * @method     ChildCommandUrl findOneById(int $id) Return the first ChildCommandUrl filtered by the id column
 * @method     ChildCommandUrl findOneByCommand(string $command) Return the first ChildCommandUrl filtered by the command column
 * @method     ChildCommandUrl findOneByToken(string $token) Return the first ChildCommandUrl filtered by the token column
 * @method     ChildCommandUrl findOneByAllowedIps(string $allowed_ips) Return the first ChildCommandUrl filtered by the allowed_ips column
 * @method     ChildCommandUrl findOneByActive(int $active) Return the first ChildCommandUrl filtered by the active column
 *
 * @method     array findById(int $id) Return ChildCommandUrl objects filtered by the id column
 * @method     array findByCommand(string $command) Return ChildCommandUrl objects filtered by the command column
 * @method     array findByToken(string $token) Return ChildCommandUrl objects filtered by the token column
 * @method     array findByAllowedIps(string $allowed_ips) Return ChildCommandUrl objects filtered by the allowed_ips column
 * @method     array findByActive(int $active) Return ChildCommandUrl objects filtered by the active column
 *
 */
abstract class CommandUrlQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \CommandToUrl\Model\Base\CommandUrlQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\CommandToUrl\\Model\\CommandUrl', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCommandUrlQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCommandUrlQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \CommandToUrl\Model\CommandUrlQuery) {
            return $criteria;
        }
        $query = new \CommandToUrl\Model\CommandUrlQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCommandUrl|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CommandUrlTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CommandUrlTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildCommandUrl A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, COMMAND, TOKEN, ALLOWED_IPS, ACTIVE FROM command_url WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildCommandUrl();
            $obj->hydrate($row);
            CommandUrlTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildCommandUrl|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildCommandUrlQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CommandUrlTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildCommandUrlQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CommandUrlTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCommandUrlQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CommandUrlTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CommandUrlTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommandUrlTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the command column
     *
     * Example usage:
     * <code>
     * $query->filterByCommand('fooValue');   // WHERE command = 'fooValue'
     * $query->filterByCommand('%fooValue%'); // WHERE command LIKE '%fooValue%'
     * </code>
     *
     * @param     string $command The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCommandUrlQuery The current query, for fluid interface
     */
    public function filterByCommand($command = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($command)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $command)) {
                $command = str_replace('*', '%', $command);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CommandUrlTableMap::COMMAND, $command, $comparison);
    }

    /**
     * Filter the query on the token column
     *
     * Example usage:
     * <code>
     * $query->filterByToken('fooValue');   // WHERE token = 'fooValue'
     * $query->filterByToken('%fooValue%'); // WHERE token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $token The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCommandUrlQuery The current query, for fluid interface
     */
    public function filterByToken($token = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($token)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $token)) {
                $token = str_replace('*', '%', $token);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CommandUrlTableMap::TOKEN, $token, $comparison);
    }

    /**
     * Filter the query on the allowed_ips column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowedIps('fooValue');   // WHERE allowed_ips = 'fooValue'
     * $query->filterByAllowedIps('%fooValue%'); // WHERE allowed_ips LIKE '%fooValue%'
     * </code>
     *
     * @param     string $allowedIps The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCommandUrlQuery The current query, for fluid interface
     */
    public function filterByAllowedIps($allowedIps = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($allowedIps)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $allowedIps)) {
                $allowedIps = str_replace('*', '%', $allowedIps);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CommandUrlTableMap::ALLOWED_IPS, $allowedIps, $comparison);
    }

    /**
     * Filter the query on the active column
     *
     * Example usage:
     * <code>
     * $query->filterByActive(1234); // WHERE active = 1234
     * $query->filterByActive(array(12, 34)); // WHERE active IN (12, 34)
     * $query->filterByActive(array('min' => 12)); // WHERE active > 12
     * </code>
     *
     * @param     mixed $active The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCommandUrlQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_array($active)) {
            $useMinMax = false;
            if (isset($active['min'])) {
                $this->addUsingAlias(CommandUrlTableMap::ACTIVE, $active['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($active['max'])) {
                $this->addUsingAlias(CommandUrlTableMap::ACTIVE, $active['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommandUrlTableMap::ACTIVE, $active, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCommandUrl $commandUrl Object to remove from the list of results
     *
     * @return ChildCommandUrlQuery The current query, for fluid interface
     */
    public function prune($commandUrl = null)
    {
        if ($commandUrl) {
            $this->addUsingAlias(CommandUrlTableMap::ID, $commandUrl->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the command_url table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CommandUrlTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CommandUrlTableMap::clearInstancePool();
            CommandUrlTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildCommandUrl or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildCommandUrl object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CommandUrlTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CommandUrlTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        CommandUrlTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CommandUrlTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // CommandUrlQuery
