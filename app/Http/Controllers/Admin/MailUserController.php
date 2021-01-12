<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\MailUserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use JetBrains\PhpStorm\ArrayShape;

class MailUserController extends Controller
{
    public function __construct(
        private MailUserRepository $mailUserRepository,
        private Factory $validatorFactory,
    ){}

    public function view(): Response
    {
        $mailUsers = $this->mailUserRepository->findAll(10);

        $links = str_replace('pagination', 'pagination pagination-sm row justify-content-center',
                             $mailUsers->onEachSide(1)->links()->toHtml());

        $data = [
            'title' => 'Mail Users',
            'controller' => 'admin',
            'mailUsers' => $mailUsers,
            'links' => $links,
        ];

        return response()->view('admin.mailUser.index', $data);
    }

    protected function redirectTo(): RedirectResponse
    {
        return redirect(route('admin.mail.users'));
    }

    /**
     * @throws ValidationException
     */
    public function create(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rulesCreate());

        $createArray = [
            'domain' => $data['domain'],
            'password' => $this->encryptPasswordSHA512($data['password']),
            'email' => $data['email'],
            'forward' => $data['forward'],
        ];

        $this->mailUserRepository->create($createArray);


        return $this->redirectTo();
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rulesUpdate($request->get('id')));

        $mailUser = $this->mailUserRepository->get((int) $data['id']);

        $mailUser->setDomain($data['domain']);
        $mailUser->setEmail($data['email']);
        $mailUser->setForward($data['forward']);

        $mailUser->save();


        return $this->redirectTo();
    }

    /**
     * @throws ValidationException
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rulesUpdatePassword());

        $mailUser = $this->mailUserRepository->get((int) $data['id']);
        $mailUser->setPassword($this->encryptPasswordSHA512($data['password']));
        $mailUser->save();

        return $this->redirectTo();
    }

    /**
     * @throws ValidationException
     */
    public function delete(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rulesDelete());

        $this->mailUserRepository->delete((int) $data['id']);

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

    #[ArrayShape(['domain' => "string", 'password' => "string", 'email' => "string", 'forward' => "string"])]
    protected function rulesCreate(): array
    {
        return [
            'domain' => 'string|required',
            'password' => 'string|required',
            'email' => 'string|email|required|unique:mail_users',
            'forward' => 'nullable',
        ];
    }

    #[ArrayShape(['id' => "string", 'domain' => "string", 'email' => "string", 'forward' => "string"])]
    public function rulesUpdate(string $id): array
    {
        return [
            'id' => 'integer|required',
            'domain' => 'string|required',
            'email' => 'string|email|required|unique:mail_users,email,' . $id,
            'forward' => 'nullable',
        ];
    }

    #[ArrayShape(['id' => "string", 'password' => "string"])]
    public function rulesUpdatePassword(): array
    {
        return [
            'id' => 'integer|required',
            'password' => 'string|required',
        ];
    }

    #[ArrayShape(['id' => "string"])]
    protected function rulesDelete(): array
    {
        return ['id' => 'integer|required'];
    }
}
