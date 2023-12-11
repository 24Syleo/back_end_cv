<?php
// src/Security/AccessTokenHandler.php
namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(private UserRepository $userRepo)
    {
    }
    
    public function getUserBadgeFrom(string $token): UserBadge
    {
        // e.g. query the "access token" database to search for this token
        $user = $this->userRepo->findOneBy([ 'token' => $token]);
        if (null === $user) {
            throw new BadCredentialsException('Invalid credentials.');
        }
        // and return a UserBadge object containing the user identifier from the found token
        return new UserBadge($user->getToken());
    }
}
?>