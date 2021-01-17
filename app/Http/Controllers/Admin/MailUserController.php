<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\MailUserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Factory;

class MailUserController extends Controller
{
    public function __construct(
        private MailUserRepository $mailUserRepository,
        private Factory $validatorFactory,
    ) {
    }

    public function view(): Response
    {
        $mailUsers = $this->mailUserRepository->findAll(10);

        $links = str_replace('pagination', 'pagination pagination-sm row justify-content-center',
                             $mailUsers->onEachSide(1)->links()->toHtml());

        $viewData = [
            'title' => 'Mail Users',
            'controller' => 'admin',
            'mailUsers' => $mailUsers,
            'links' => $links,
        ];

        return response()->view('admin.mailUser.index', $viewData);
    }

    protected function redirectTo(): RedirectResponse
    {
        return redirect(route('admin.mail.users'));
    }

    public function create(Request $request): RedirectResponse
    {
        $formData = $request->validate($this->rulesCreate());

        $createArray = [
            'domain' => $formData['domain'],
            'password' => $this->encryptPasswordSHA512($formData['password']),
            'email' => $formData['email'],
            'forward' => $formData['forward'],
        ];

        $this->mailUserRepository->create($createArray);


        return $this->redirectTo();
    }

    public function update(Request $request): RedirectResponse
    {
        $formData = $request->validate($this->rulesUpdate($request->get('id')));

        $mailUser = $this->mailUserRepository->get((int) $formData['id']);

        $mailUser->setDomain($formData['domain']);
        $mailUser->setEmail($formData['email']);
        $mailUser->setForward($formData['forward']);

        $mailUser->save();


        return $this->redirectTo();
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $formData = $request->validate($this->rulesUpdatePassword());

        $mailUser = $this->mailUserRepository->get((int) $formData['id']);
        $mailUser->setPassword($this->encryptPasswordSHA512($formData['password']));
        $mailUser->save();

        return $this->redirectTo();
    }

    public function delete(Request $request): RedirectResponse
    {
        $formData = $request->validate($this->rulesDelete());

        $this->mailUserRepository->delete((int) $formData['id']);

        return $this->redirectTo();
    }

    private function encryptPasswordSHA512(string $password):string
    {
        $saltLength = 50;

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $salt = '';
        for ($i = 0; $i < $saltLength; $i++) {
            $salt .= $characters[rand(0, $charactersLength - 1)];
        }

        return crypt( $password, '$6$' . $salt );
    }

    protected function rulesCreate(): array
    {
        return [
            'domain' => 'string|required',
            'password' => 'string|required',
            'email' => 'string|email|required|unique:mail_users',
            'forward' => 'nullable',
        ];
    }

    public function rulesUpdate(string $id): array
    {
        return [
            'id' => 'integer|required',
            'domain' => 'string|required',
            'email' => 'string|email|required|unique:mail_users,email,' . $id,
            'forward' => 'nullable',
        ];
    }

    public function rulesUpdatePassword(): array
    {
        return [
            'id' => 'integer|required',
            'password' => 'string|required',
        ];
    }

    protected function rulesDelete(): array
    {
        return ['id' => 'integer|required'];
    }
}
