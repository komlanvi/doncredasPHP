<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 02/04/15
 * Time: 0.26
 */

namespace Blog\Dao;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Blog\Domain\User;

class UserDAO extends DAO implements UserProviderInterface{

    /**
     * @param integer $id
     * @throws \Exception
     * @return User
     */
    public function findUserById($id) {
        $sql = "SELECT * FROM blog_user WHERE id = ?";
        $row = $this->getDB()->fetchAssoc($sql, array($id));


        if($row) {
            return $this->buildDomainObject($row);
        }else{
            throw new \Exception("No user find for id: " .$id);
        }
    }

    /**
     * @return array
     */
    public function findAll() {
        $sql = "SELECT * FROM blog_user ORDER BY role, username";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Saves a user into the database.
     *
     * @param \Blog\Domain\User $user The user to save
     */
    public function save(User $user) {
        $userData = array(
            'username' => $user->getUsername(),
            'salt' => $user->getSalt(),
            'password' => $user->getPassword(),
            'role' => $user->getRole()
        );

        if ($user->getId()) {
            // The user has already been saved : update it
            $this->getDb()->update('blog_user', $userData, array('id' => $user->getId()));
        } else {
            // The user has never been saved : insert it
            $this->getDb()->insert('t_user', $userData);
            // Get the id of the newly created user and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }

    /**
     * Removes a user from the database.
     *
     * @param @param integer $id The user id.
     */
    public function delete($id) {
        // Delete the user
        $this->getDb()->delete('user', array('id' => $id));
    }


    /**
     * @param string
     * @return User
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username) {
        $sql = "SELECT * FROM blog_user WHERE username = ?";
        $row = $this->getDB()->fetchAssoc($sql, array($username));

        if($row) {
            return $this->buildDomainObject($row);
        }else{
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }
    }


    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'Blog\Domain\User' === $class;
    }

    /**
     * @param array $row
     *
     * @return User $user
     */
    protected function buildDomainObject(array $row) {
        $user = new User();
        $user->setId($row["id"]);
        $user->setUsername($row["username"]);
        $user->setSalt($row["salt"]);
        $user->setRole($row["role"]);
        $user->setPassword($row["password"]);
        return $user;
    }
}