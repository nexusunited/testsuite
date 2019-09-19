<?php

namespace Pyz\Zed\RestAuth\Business\Model\Processor;

use Pyz\Zed\RestAuth\RestAuthConfigInterface;
use Symfony\Component\HttpFoundation\Request;

class BasicAuthProcessor implements AuthProcessorInterface
{
    /**
     * @var \Shared\Zed\RestAuth\RestAuthConfigInterface
     */
    protected $config;

    /**
     * @param \Shared\Zed\RestAuth\RestAuthConfigInterface $authConfig
     */
    public function __construct(RestAuthConfigInterface $authConfig)
    {
        $this->config = $authConfig;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return bool
     */
    public function isAuth(Request $request): bool
    {
        $authOk = false;
        foreach ($this->getValidUsers() as $validUser) {
            if ($validUser['username'] === $request->server->get('PHP_AUTH_USER') &&
                $validUser['password'] === $request->server->get('PHP_AUTH_PW')
            ) {
                $authOk = true;
            }
        }
        return $authOk;
    }

    /**
     * @return array
     */
    private function getValidUsers(): array
    {
        return $this->config->getValidUsers();
    }
}
