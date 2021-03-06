<?php

namespace Controllers;



use BusinessLogic\Authentication\LoginUserService;
use BusinessLogic\Authentication\RegisterNewUserCommand;
use BusinessLogic\Authentication\VerifyUserCommand;
use Config\Constants;
use Config\Settings;
use Controllers\ViewModels\BaseModel;
use ReCaptcha\ReCaptcha;
use ReCaptcha\RequestMethod\CurlPost;

class AuthenticationController {
    private $registerNewUserCommand;
    private $loginUserService;
    private $verifyUserCommand;

    public function __construct(RegisterNewUserCommand $registerNewUserCommand,
                                LoginUserService $loginUserService,
                                VerifyUserCommand $verifyUserCommand) {
        $this->registerNewUserCommand = $registerNewUserCommand;
        $this->loginUserService = $loginUserService;
        $this->verifyUserCommand = $verifyUserCommand;
    }

    public function viewSignInPage(\Twig_Environment $twig, BaseModel $model): string {
        if (isset($_GET['redirectLocation'])) {
            $model->redirectLocation = $_GET['redirectLocation'];
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $model->redirectLocation = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
        }

        return Renderer::render('user/login.twig', $twig, $model);
    }


    public function registerUser(string $name,
                          string $email,
                          string $password,
                          string $confirmPassword,
                          string $recaptchaResponse,
                          \Twig_Environment $twig): void {
        $settings = new Settings();
        $recaptcha = new ReCaptcha($settings->recaptchaSiteSecret, new CurlPost());
        $response = $recaptcha->verify($recaptchaResponse, $_SERVER['REMOTE_ADDR']);

        if (!$response->isSuccess()) {
            throw new RecaptchaFailedException();
        }

        if ($password !== $confirmPassword) {
            throw new \Exception("Passwords do not match!");
        }

        $this->registerNewUserCommand->execute($name, $email, $password, $twig);

        return;
    }

    public function loginUser(string $email,
                       string $password) {
        return $this->loginUserService->loginWithUserAndPassword($email, $password);
    }

    public function verifyUser(string $token) {
        return $this->verifyUserCommand->execute($token);
    }
}