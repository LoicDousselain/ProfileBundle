<?php

namespace MMC\Profile\Bundle\ProfileBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserProfileSecurizingManipulatorContext extends GlobalContext implements Context, SnippetAcceptingContext
{
    protected $userProfileSecurizingManipulator;

    public function __construct(
        UserProfileManipulatorInterface $userProfileSecurizingManipulator,
        TokenStorage $tokenStorage
    ) {
        $this->userProfileSecurizingManipulator = $userProfileSecurizingManipulator;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Given :arg1 is logged in
     */
    public function loggedIn($arg1)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                $selectedUser = $user;
            }
        }

        if ($selectedUser) {
            $token = new UsernamePasswordToken(
                $selectedUser, null, 'main', $user->getRoles()
            );
            $this->tokenStorage->setToken($token);
        }
    }

    /**
     * @Given :arg1 use profile :arg2
     */
    public function UseProfile($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                try {
                    $this->userProfileSecurizingManipulator->setActiveProfile($user, $selectedProfile);
                } catch (\Exception $e) {
                    $this->lastException = $e;
                }
            }
        }
    }

    /**
     * @Then I should see :arg1 active profile is :arg2
     */
    public function iShouldSeeActiveProfileIs($arg1, $arg2)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                $activeProfileUuid = $this->userProfileSecurizingManipulator->getActiveProfile($user)->getUuid();
                \PHPUnit_Framework_Assert::assertEquals(
                    $arg2,
                    $activeProfileUuid
                );
            }
        }
    }

    /**
     * @Then I should see that :arg1 is owner of profile :arg2
     */
    public function iShouldSeeThatIsOwnerOfProfile($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                \PHPUnit_Framework_Assert::assertEquals(
                    true,
                    $this->userProfileSecurizingManipulator->isOwner($user, $selectedProfile)
                );
            }
        }
    }

    /**
     * @Given :arg1 set priority of userProfile :arg2 :arg3
     */
    public function tutuSetPriorityOfUserprofileTintin($arg2, $arg3)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg3) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg2) {
                try {
                    $this->userProfileSecurizingManipulator->setProfilePriority($user, $selectedProfile);
                } catch (\Exception $e) {
                    $this->lastException = $e;
                }
            }
        }
    }

    /**
     * @Then :arg1 associate :arg2 to :arg3
     */
    public function AssociateTo($arg2, $arg3)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg3) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg2) {
                try {
                    $this->userProfileSecurizingManipulator->createUserProfile($user, $selectedProfile);
                } catch (\Exception $e) {
                    $this->lastException = $e;
                }
            }
        }
    }

    /**
     * @Then I should see :arg1 userProfiles for :arg2
     */
    public function iShouldSeeUserprofilesFor($arg1, $arg2)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg2) {
                if ($user->getUserProfiles() != null) {
                    foreach ($user->getUserProfiles() as $up) {
                        \PHPUnit_Framework_Assert::assertCount(
                            intval($arg1),
                            $user->getUserProfiles()
                        );
                    }
                } else {
                    \PHPUnit_Framework_Assert::assertCount(
                        intval($arg1),
                        []
                    );
                }
            }
        }
    }

    /**
     * @Given :arg1 promote :arg2 for profile :arg3
     */
    public function PromoteForProfile($arg2, $arg3)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg3) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg2) {
                try {
                    $this->userProfileSecurizingManipulator->promoteUserProfile($user, $selectedProfile);
                } catch (\Exception $e) {
                    $this->lastException = $e;
                }
            }
        }
    }

    /**
     * @Given :arg1 demote :arg2 for profile :arg3
     */
    public function tutuDemoteTotoForProfile($arg2, $arg3)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg3) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg2) {
                try {
                    $this->userProfileSecurizingManipulator->demoteUserProfile($user, $selectedProfile);
                } catch (\Exception $e) {
                    $this->lastException = $e;
                }
            }
        }
    }

    /**
     * @Then I should see that profile :arg1 has :arg2 owners
     */
    public function iShouldSeeThatProfileHasOwners($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg1) {
                \PHPUnit_Framework_Assert::assertCount(
                    intval($arg2),
                    $this->userProfileSecurizingManipulator->getOwners($profile)
                );
            }
        }
    }

    /**
     * @Given :arg1 remove profile :arg2 to :arg3
     */
    public function RemoveProfileTo($arg2, $arg3)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg3) {
                try {
                    $this->userProfileSecurizingManipulator->removeProfileForUser($user, $selectedProfile);
                } catch (\Exception $e) {
                    $this->lastException = $e;
                }
            }
        }
    }

    /**
     * @Given :arg1 is logged out
     */
    public function IsLoggedOut()
    {
        $this->tokenStorage->setToken(null);
    }
}
