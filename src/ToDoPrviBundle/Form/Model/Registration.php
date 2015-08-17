<?php
/**
 * Created by PhpStorm.
 * User: skazzi
 * Date: 17.8.15.
 * Time: 13.29
 */

namespace ToDoPrviBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use ToDoPrviBundle\Entity\User;

class Registration
{
    /**
     * @Assert\Type(type="ToDoPrviBundle\Entity\User")
     * @Assert\Valid()
     */
    protected $user;

    /**
     * @Assert\NotBlank()
     * @Assert\True()
     */
    protected $termsAccepted;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = (bool) $termsAccepted;
    }
}