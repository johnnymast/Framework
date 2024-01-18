<?php

namespace App\Framework\Auth\Http\Controllers;

use App\Framework\Auth\Factory\PasskeyCredentialFactory;
use App\Framework\Auth\Guard;
use App\Http\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Firehed\WebAuthn\ChallengeManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

use Firehed\WebAuthn\{Codecs, ArrayBufferResponseParser, RelyingPartyInterface};


class RegisterPasskeyController extends Controller
{


    /**
     * @var Guard
     */
    protected Guard $guard;

    /**
     * LoginController constructor.
     *
     * @param EntityManager $em Inject entity manager.
     */
    public function __construct(EntityManager $em)
    {
        $this->guard = new Guard();
        $settings = config('auth.login');

        parent::__construct($em);
    }

    /**
     * Request passkey registration.
     *
     * @param Response $response The response Object.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @return Response
     */
    public function precentLinkRequestData(Response $response): Response
    {
        $challengeManager = app()->resolve(ChallengeManagerInterface::class);
        $challenge = $challengeManager->createChallenge();

        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($challenge->getBase64()));
        return $response;
    }

    /**
     * Verify the passkey registration.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param Response                                 $response The response Object.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return Response
     */
    public function verifyLinkRequestData(Request $request, Response $response): Response
    {

        try {

            $challengeManager = app()->resolve(ChallengeManagerInterface::class);
            $rp = app()->resolve(RelyingPartyInterface::class);

            $user = $request->getAttribute('user');
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $parser = new ArrayBufferResponseParser();
            $createResponse = $parser->parseCreateResponse($data);

            $credential = $createResponse->verify($challengeManager, $rp);

            $codec = new Codecs\Credential();
            $encodedCredential = $codec->encode($credential);

            PasskeyCredentialFactory::create(
                storage_id: $credential->getStorageId(),
                credential: $encodedCredential,
                user: $user
            );

            $response = $response->withStatus(200);
            $response->getBody()->write("HTTP/1.1 200 OK");


        } catch (\Exception $e) {
            log_error($e->getMessage());
            $response = $response->withStatus(403);
            $response->getBody()->write('HTTP/1.1 403 Unauthorized');
        }

        return $response;
    }


}
