<?php

namespace App\Service;

use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\ShopParametersRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class MembershipContributionService
{
    public function __construct(
        private Security $security,
        private RequestStack $requestStack,
        private UserRepository $userRepository,
        private ShopParametersRepository $shopParametersRepository,
    ) {
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->shopParametersRepository = $shopParametersRepository;
    }

    public function checkContribution(): array
    {

        $request = $this->requestStack->getCurrentRequest();
        $session = $request->getSession();
        $userSession = $this->security->getUser();
        $today = new \DateTimeImmutable();
        $shopParameters = $this->shopParametersRepository->findAll()[0];
        $cotisationPrice = $shopParameters->getContribution();
        $cotisation = [];
        if ($userSession) {
            $user = $this->userRepository->find($userSession->getId());
            $userCotisationEndDate = $user->getMembershipContributionEndDate();
        }

        if (isset($userCotisationEndDate) && $userCotisationEndDate->format('Y-m-d') >= $today->format('Y-m-d')) {
            $cotisation = [
                'endDate' => $userCotisationEndDate,
                'price' => 0
            ];
            $session->set('cotisation', $cotisation);
        } else {
            $newEndDate = $today->modify('+1 year');
            $cotisation = [
                'endDate' => $newEndDate,
                'price' => $cotisationPrice,
            ];
            $session->set('cotisation', $cotisation);
        }

        return $cotisation;
    }
}

/**
 * Pour injecter des services à l'intérieur du service on créé une fonction
 * __contruct() qui contient en argument les services à appeler.
 *
 * A l'inétérieur d'un service, pour appeler l'utilisateur en session
 * on doit appeler le service de sécurité :
 * https://symfony.com/doc/current/security.html#fetching-the-user-from-a-service
 *
 * Pour récupérer la session on doit accéder à la requête courante.
 * A l'intérieur d'un service, il faut injecter le service request_stack
 * pour accéder à cette requête en appelant la méthode getCurrentRequest().
 * https://symfony.com/doc/current/service_container/request.html
 *
 *
 *
 */
