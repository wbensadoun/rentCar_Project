<?php

namespace App\Service;


use App\Entity\User;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserManagerHelper
 * @package App\UserBundle\Helpers
 */
class UserManagerHelper
{
    protected $em;
    private $translator;
    private $passwordEncoder;
    private $security;

    /**
     * UserManagerHelper constructor.
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, UserPasswordEncoderInterface $userPasswordEncoder, Security $security)
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->passwordEncoder = $userPasswordEncoder;
        $this->security = $security;
    }

    /**
     * Modifie le mot de passe d'un User existant
     * @param $username
     * @param $password
     * @return User
     * @throws \Exception
     */
    public function changeUserPassword($username, $password){

        /** @var User $user */
        $user = $this->em->getRepository(User::class)->findUserByUsernameOrEmail($username);
        if(!$user){
            throw new \Exception("Aucun utilisateur trouvé");
        }

        $password = trim($password);

        if(empty($password)){
            throw new \Exception("Le mot de passe ne peut être vide");
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        return true;
    }

    /**
     * @param $username
     * @param $password
     * @param $email
     * @param $role
     * @param $active
     * @return User|string
     */
    public function createUser($username, $password, $email, $role, $active)
    {
        $password = trim($password);

        if(empty($password)){
            throw new \Exception("Le mot de passe ne peut être vide");
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->addRole($role);
        $user->setEnabled($active);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function addRole($username, $role)
    {
        /**
         * @var User $user
         */
        $user = $this->em->getRepository(User::class)->findUserByUsernameOrEmail($username);
        if(!$user){
            throw new \Exception("Aucun utilisateur trouvé");
        }

        if(!$user->hasRole($role)){
            $user->addRole($role);
            $this->em->persist($user);
            $this->em->flush();
            return true;
        }

        throw new \Exception("Cet utilisateur posséde déjà ce rôle");
    }

    public function removeRole($username, $role)
    {
        /**
         * @var User $user
         */
        $user = $this->em->getRepository(User::class)->findUserByUsernameOrEmail($username);
        if(!$user){
            throw new \Exception("Aucun utilisateur trouvé");
        }

        if($user->hasRole($role)){
            $user->removeRole($role);
            $this->em->persist($user);
            $this->em->flush();
            return true;
        }

        throw new \Exception("Cet utilisateur ne posséde pas ce rôle");
    }

    /**
     * @param $roles
     * @return bool
     */
    public function IsOnlyGranted($roles)
    {
        $roles = (array)$roles;

        $userRoles = $this->security->getUser()->getRoles();

        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $roles
     * @return bool
     */
    public function IsUserOnlyGranted($roles, User $user)
    {
        $roles = (array)$roles;

        $userRoles = $user->getRoles();

        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }

        return false;
    }
}