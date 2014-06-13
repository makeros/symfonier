<?php

// namespace Symfonier\UserBundle\Document;

// use FOS\UserBundle\Document\User as BaseUser;
// use Symfonier\ApiBundle\Document\Housing as Housing;
// use Symfonier\ApiBundle\Document\Address as Address;
// use Symfonier\ApiBundle\Document\Group as Group;

// use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

// /**
//  * @MongoDB\Document
//  */
// class User extends BaseUser
// {
//     /**
//      * @MongoDB\Id(strategy="auto")
//      */
//     public $id;

//     /**
//      * @MongoDB\ReferenceOne(
//      *  targetDocument="Symfonier\ApiBundle\Document\Housing",
//      *  cascade={"persist"}
//      * )
//      */
//     protected $housing;

//     /**
//      * @MongoDB\String
//      */
//     protected $firstName;

//     /**
//      * @MongoDB\String
//      */
//     protected $lastName;

//     /**
//      * @MongoDB\Boolean
//      */
//     protected $isVerified = false;

//     /**
//     * @MongoDB\ReferenceOne(targetDocument="Symfonier\ApiBundle\Document\Address" ,cascade={"remove"})
//     */
//     protected $address;

//     /**
//     * @MongoDB\ReferenceMany(targetDocument="Symfonier\ApiBundle\Document\Group")
//     */
//     protected $SymfonierGroups;

//     /**
//      * @MongoDB\String
//      */
//     protected $verificationCode;

//     /**
//      * @MongoDB\ReferenceOne(targetDocument="Symfonier\ApiBundle\Document\File")
//      */
//     protected $avatar;

//     /**
//      * @MongoDB\ReferenceOne(targetDocument="Symfonier\ApiBundle\Document\UserSettings", mappedBy="user")
//      */
//     protected $settings; 

//     public function __construct()
//     {
//         parent::__construct();
//         $this->SymfonierGroups = new \Doctrine\Common\Collections\ArrayCollection();

//     }

//     /**
//      * Set user roles as array 
//      */
//     public function setRoles(array $roles)
//     {
//         $this->roles = array();

//         foreach ($roles as $role) {
//             $this->addRole($role);
//         }

//         return $this;
//     }

//     /**
//      * Get id
//      *
//      * @return id $id
//      */
//     public function getId()
//     {
//         return $this->id;
//     }

//     /**
//      * Set isVerified
//      *
//      * @param boolean $isVerified
//      * @return self
//      */
//     public function setIsVerified($isVerified)
//     {
//         $this->isVerified = $isVerified;
//         return $this;
//     }

//     /**
//      * Get isVerified
//      *
//      * @return boolean $isVerified
//      */
//     public function getIsVerified()
//     {
//         return $this->isVerified;
//     }

//     /**
//      * Set avatar
//      *
//      * @param File $avatar
//      * @return self
//      */
//     public function setAvatar(\Symfonier\ApiBundle\Document\File $avatar)
//     {
//         $this->avatar = $avatar;
//         return $this;
//     }

//     /**
//      * Get avatar
//      *
//      * @return File $avatar
//      */
//     public function getAvatar()
//     {
//         return $this->avatar;
//     }

//     /**
//      * Set settings
//      *
//      * @param File $settings
//      * @return self
//      */
//     public function setSettings(\Symfonier\ApiBundle\Document\UserSettings $settings)
//     {
//         $this->settings = $settings;
//         return $this;
//     }

//     /**
//      * Get settings
//      *
//      * @return File $settings
//      */
//     public function getSettings()
//     {
//         return $this->settings;
//     }


//     /**
//      * Set housing
//      *
//      * @param object $housing
//      * @return self
//      */
//     public function setHousing(Housing $housing)
//     {
//         $this->housing = $housing;
//         $housing->addUser($this);
//         return $this;
//     }

//     /**
//      * Get housing
//      *
//      * @return object $housing
//      */
//     public function getHousing()
//     {
//         return $this->housing;
//     }

//     /**
//      * Set firstName
//      *
//      * @param string $firstName
//      * @return self
//      */
//     public function setFirstName($firstName)
//     {
//         $this->firstName = $firstName;
//         return $this;
//     }

//     /**
//      * Get firstName
//      *
//      * @return string $firstName
//      */
//     public function getFirstName()
//     {
//         return $this->firstName;
//     }

//     /**
//      * Set lastName
//      *
//      * @param string $lastName
//      * @return self
//      */
//     public function setLastName($lastName)
//     {
//         $this->lastName = $lastName;
//         return $this;
//     }

//     /**
//      * Get lastName
//      *
//      * @return string $lastName
//      */
//     public function getLastName()
//     {
//         return $this->lastName;
//     }

//     /**
//      * Get address
//      *
//      * @return Object address
//      */
//     public function getAddress()
//     {
//         return $this->address;
//     }

//     /**
//      * Set address
//      *
//      * @param Address address
//      * @return self
//      */
//     public function setAddress(Address $address)
//     {
//         $this->address = $address;
//         return $this;
//     }

//     /**
//      * Add new Symfonier group
//      * @return self
//      */
//     public function addSymfonierGroup(Group $Symfoniergroup) {
//         $this->SymfonierGroups[] = $Symfoniergroup;
//         return $this;
//     }
    
//     /**
//      * Remove Symfonier group
//      *
//      * @param Symfonier\ApiBundle\Document\Group $Symfoniergroup
//      */
//     public function removeSymfonierGroup(Group $Symfoniergroup) {
//         $this->SymfonierGroups->removeElement($Symfoniergroup);
//     }
    
//     /**
//      * Set Symfonier groups
//      * @return self
//      */
//     public function setSymfonierGroups($group) {
//         $this->groups = $groups;
//         return $this;
//     }
    
//     /**
//      * Get Symfonier groups
//      * @return array
//      */
//     public function getSymfonierGroups() {
//         return $this->SymfonierGroups;
//     }

//     /**
//      * Get verificationCode
//      *
//      * @return string verificationCode
//      */
//     public function getVerificationCode()
//     {
//         return $this->verificationCode;
//     }

//     /**
//      * Set verificationCode
//      *
//      * @param string verificationCode
//      * @return self
//      */
//     public function setVerificationCode($verificationCode)
//     {
//         $this->verificationCode = $verificationCode;
//         return $this;
//     }
// }