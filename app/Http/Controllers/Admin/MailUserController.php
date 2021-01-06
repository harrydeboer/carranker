<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\MailUserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MailUserController extends Controller
{
    public function __construct(
        private MailUserRepository $mailUserRepository,
    )
    {

    }

    public function view(): Response
    {
        $mailUsers = $this->mailUserRepository->findAll(10);

        /** The links of the pagination get extra html classes to make them centered on the modelpage. */
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

    public function create(Request $request): RedirectResponse
    {
        $createArray = [
            'domain' => $request->domain,
            'password' => $this->encryptPasswordSHA512($request->password),
            'email' => $request->email,
            'forward' => $request->forward,
        ];

        $this->mailUserRepository->create($createArray);

        return redirect(route('admin.mail.users'));
    }

    public function update(Request $request): RedirectResponse
    {
        $mailUser = $this->mailUserRepository->get((int) $request->id);

        $mailUser->setDomain($request->domain);
        $mailUser->setEmail($request->email);
        $mailUser->setForward($request->forward);

        $mailUser->save();

        return redirect(route('admin.mail.users'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $mailUser = $this->mailUserRepository->get((int) $request->id);

        $mailUser->setPassword($this->encryptPasswordSHA512($request->password));

        $mailUser->save();

        return redirect(route('admin.mail.users'));
    }

    public function delete(Request $request): RedirectResponse
    {
        $this->mailUserRepository->delete((int) $request->id);

        return redirect(route('admin.mail.users'));
    }

    private function encryptPasswordSHA512(string $password):string
    {
        $saltlength = 50;

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $salt = '';
        for ($i = 0; $i < $saltlength; $i++) {
            $salt .= $characters[rand(0, $charactersLength - 1)];
        }

        return crypt( $password, '$6$' . $salt );
    }
}
